<?php
session_start();
require_once "../dbconnect.php";
header("Content-Type: application/json; charset=UTF-8");

$action = $_POST["action"] ?? $_GET["action"] ?? '';

switch($action)
{
    case 'AnalyzeSpending':
    {
        if(!isset($_SESSION["LoginName"]))
        {
            echo json_encode(["ok" => false, "error" => "Please log in"]);
            exit();
        }

        $AccountNumber = $_GET["AccountNumber"] ?? '';
        if(empty($AccountNumber))
        {
            echo json_encode(["ok" => false, "error" => "Account number is required"]);
            exit();
        }

        try
        {
            // Pull all monthly spending per category, oldest to newest
            $stmt = $conn->prepare(
                "SELECT DATE_FORMAT(Date, '%Y-%m') AS Month,
                        Category,
                        SUM(Amount) AS TotalSpent,
                        COUNT(*)    AS TotalTransactions
                 FROM Transactions
                 WHERE AccountNumber = ?
                 GROUP BY Month, Category
                 ORDER BY Month ASC"
            );
            $stmt->execute([$AccountNumber]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Pull budgets for this account
            $bStmt = $conn->prepare("SELECT Category, Threshold, Frequency FROM Budget WHERE AccountNumber = ?");
            $bStmt->execute([$AccountNumber]);
            $budgets = [];
            foreach($bStmt->fetchAll(PDO::FETCH_ASSOC) as $b)
                $budgets[$b['Category']] = ['Threshold' => (float)$b['Threshold'], 'Frequency' => $b['Frequency']];

            // Group spending: byCategory[category][month] = amount
            $byCategory = [];
            $monthSet   = [];
            foreach($rows as $row)
            {
                $byCategory[$row['Category']][$row['Month']] = (float)$row['TotalSpent'];
                $monthSet[$row['Month']] = true;
            }

            $allMonths = array_keys($monthSet);
            sort($allMonths);

            if(count($allMonths) === 0)
            {
                echo json_encode(["ok" => true, "analysis" => [], "currentMonth" => null, "prevMonth" => null]);
                exit();
            }

            $currentMonth = $allMonths[count($allMonths) - 1];
            $prevMonth    = count($allMonths) >= 2 ? $allMonths[count($allMonths) - 2] : null;

            $analysis = [];

            foreach($byCategory as $category => $monthData)
            {
                // Only analyze categories with spending in the current month
                if(!isset($monthData[$currentMonth])) continue;

                $currSpend = $monthData[$currentMonth];
                $prevSpend = ($prevMonth !== null && isset($monthData[$prevMonth])) ? $monthData[$prevMonth] : null;

                // Historical average across all months except the current one
                $historicalVals = [];
                foreach($allMonths as $m)
                {
                    if($m !== $currentMonth && isset($monthData[$m]))
                        $historicalVals[] = $monthData[$m];
                }
                $avgSpend = count($historicalVals) > 0
                    ? array_sum($historicalVals) / count($historicalVals)
                    : null;

                // Month-over-month % change
                $changePercent = null;
                if($prevSpend !== null && $prevSpend > 0)
                    $changePercent = (($currSpend - $prevSpend) / $prevSpend) * 100;

                // Change vs historical average (used when no previous month exists)
                $changeVsAvg = null;
                if($avgSpend !== null && $avgSpend > 0)
                    $changeVsAvg = (($currSpend - $avgSpend) / $avgSpend) * 100;

                // Budget proximity
                $threshold = isset($budgets[$category]) ? $budgets[$category]['Threshold'] : null;
                $budgetPct = ($threshold && $threshold > 0) ? ($currSpend / $threshold) * 100 : null;

                // ── Risk scoring algorithm ──────────────────────────────
                $riskLevel = "Low";
                $flags     = [];

                // Signal 1: month-over-month spike
                if($changePercent !== null)
                {
                    if($changePercent >= 75)
                    {
                        $riskLevel = "High";
                        $flags[]   = "Spending spiked " . round($changePercent) . "% vs last month";
                    }
                    elseif($changePercent >= 25)
                    {
                        if($riskLevel === "Low") $riskLevel = "Medium";
                        $flags[] = "Spending up " . round($changePercent) . "% vs last month";
                    }
                    elseif($changePercent < 0)
                    {
                        $flags[] = "Spending down " . round(abs($changePercent)) . "% vs last month";
                    }
                }
                // Signal 2: no prior month but noticeably above personal average
                elseif($changeVsAvg !== null && $changePercent === null)
                {
                    if($changeVsAvg >= 50)
                    {
                        if($riskLevel === "Low") $riskLevel = "Medium";
                        $flags[] = "Spending " . round($changeVsAvg) . "% above your historical average";
                    }
                }

                // Signal 3: budget proximity (can upgrade risk level)
                if($budgetPct !== null)
                {
                    if($budgetPct >= 100)
                    {
                        $riskLevel = "High";
                        $flags[]   = "Over budget — " . round($budgetPct) . "% of threshold used";
                    }
                    elseif($budgetPct >= 80)
                    {
                        if($riskLevel === "Low") $riskLevel = "Medium";
                        $flags[] = round($budgetPct) . "% of budget threshold reached";
                    }
                    else
                    {
                        $flags[] = round($budgetPct) . "% of budget threshold used";
                    }
                }
                else
                {
                    $flags[] = "No budget set for this category";
                }
                // ── End risk scoring ────────────────────────────────────

                $analysis[] = [
                    'Category'        => $category,
                    'CurrentSpend'    => round($currSpend, 2),
                    'PreviousSpend'   => $prevSpend  !== null ? round($prevSpend, 2)  : null,
                    'AvgSpend'        => $avgSpend   !== null ? round($avgSpend,  2)  : null,
                    'ChangePercent'   => $changePercent !== null ? round($changePercent, 1) : null,
                    'BudgetThreshold' => $threshold,
                    'BudgetPercent'   => $budgetPct  !== null ? round($budgetPct,  1)  : null,
                    'RiskLevel'       => $riskLevel,
                    'Flags'           => $flags,
                    'Feedback'        => buildFeedback($riskLevel, $category, $prevSpend, $changePercent, $budgetPct)
                ];
            }

            // Sort: High first, then Medium, then Low
            $riskOrder = ['High' => 0, 'Medium' => 1, 'Low' => 2];
            usort($analysis, function($a, $b) use ($riskOrder)
            {
                return $riskOrder[$a['RiskLevel']] - $riskOrder[$b['RiskLevel']];
            });

            echo json_encode([
                "ok"           => true,
                "analysis"     => $analysis,
                "currentMonth" => $currentMonth,
                "prevMonth"    => $prevMonth
            ]);
        }
        catch(PDOException $e)
        {
            echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
        }
        break;
    }
}

function buildFeedback($riskLevel, $category, $prevSpend, $changePercent, $budgetPct)
{
    if($riskLevel === "High")
    {
        if($budgetPct !== null && $budgetPct >= 100)
            return "You have exceeded your $category budget this period. Reduce spending immediately to recover.";
        if($changePercent !== null && $changePercent >= 75)
            return "A sharp spike in $category spending was detected. Review recent transactions to determine if this is a one-time event or an emerging habit.";
        return "Your $category spending is at high risk. Both your spending trend and budget proximity require immediate attention.";
    }
    if($riskLevel === "Medium")
    {
        if($budgetPct !== null && $budgetPct >= 80)
            return "You are approaching your $category budget limit. At the current pace you may exceed it before the period ends.";
        if($changePercent !== null && $changePercent >= 25)
            return "Your $category spending has risen noticeably this month. Assess whether this reflects a one-time cost or a shift in spending behavior.";
        return "Your $category spending is trending upward. Monitor this category closely over the coming weeks.";
    }
    // Low
    if($prevSpend !== null && $changePercent !== null && $changePercent < 0)
        return "Your $category spending decreased this month — good financial discipline.";
    if($prevSpend === null)
        return "First month of $category data recorded. A baseline has been established for future trend analysis.";
    return "Your $category spending is stable. No action required.";
}

$conn = null;
?>
