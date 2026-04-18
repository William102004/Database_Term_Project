<?php
session_start();
require_once "../dbconnect.php";
header("Content-Type: application/json; charset=UTF-8");

$action = $_POST["action"] ?? $_GET["action"] ?? '';

switch($action)
{
    case'InsertBudget':
    {
        if(!isset($_SESSION["LoginName"]))
        {
            echo json_encode(["ok" => false, "error" => "Please Log in to create a budget"]);
            exit();
        }
        $AccountNumber = $_POST["AccountNumber"] ?? '';
        $Category = $_POST["Category"] ?? '';
        $Threshold = $_POST["Threshold"] ?? '';
        $Frequency = $_POST["Frequency"] ?? '';

        if(empty($AccountNumber) || empty($Category) || empty($Threshold) || empty($Frequency))
        {
            echo json_encode(["ok" => false, "error" => "All fields are required to create a budget"]);
            exit();
        }

        if($Threshold <= 0)
        {
            echo json_encode(["ok" => false, "error" => "Budget Threshold must be greater than zero"]);
            exit();
        }

        try
        {
            $check = $conn->prepare("SELECT BudgetID FROM Budget WHERE AccountNumber = ? AND Category = ? AND Frequency = ?");
            $check->execute([$AccountNumber, $Category, $Frequency]);
            if($check->fetch())
            {
                echo json_encode(["ok" => false, "error" => "A $Frequency budget for $Category already exists"]);
                exit();
            }
            $stmt = $conn->prepare("INSERT INTO Budget (AccountNumber, Category, Threshold, Frequency) VALUES (?,?,?,?)");
            $stmt->execute([$AccountNumber, $Category, $Threshold, $Frequency]);
            $BudgetID = $conn->lastInsertId();
            echo json_encode(["ok" => true, "message" => "Budget created successfully","BudgetNumber" => $BudgetNumber]);
        }
        catch(PDOException $e)
        {
            echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
        }
        break;
    }
    case'GetBudgets':
    {
        if(!isset($_SESSION["LoginName"]))
        {
            echo json_encode(["ok" => false, "error" => "Please Log in to create a budget"]);
            exit();
        }
        
        $AccountNumber = $_GET["AccountNumber"] ?? '';

        if(empty($AccountNumber))
        {
            echo json_encode(["ok" => false, "error" => "Account Number is required to view budgets"]);
            exit();
        }

        try
        {
            $stmt = $conn->prepare("SELECT * FROM Budget WHERE AccountNumber = ?");
            $stmt->execute([$AccountNumber]);
            $budgets = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(["ok" => true, "budgets" => $budgets]);
        }
        catch(PDOException $e)
        {
            echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
        }
        break;
    }
    case'getBudget':
    {
        if(!isset($_SESSION["LoginName"]))
        {
            echo json_encode(["ok" => false, "error" => "Please Log in to create a budget"]);
            exit();
        }
        
        $AccountNumber = $_GET["AccountNumber"] ?? '';
        $BudgetID = $_GET["BudgetID"] ?? '';

        if(empty($AccountNumber))
        {
            echo json_encode(["ok" => false, "error" => "Account Number is required to view budgets"]);
            exit();
        }

        try
        {
            $stmt = $conn->prepare("SELECT * FROM Budget WHERE AccountNumber = ? AND BudgetID = ?");
            $stmt->execute([$AccountNumber, $BudgetID]);
            $budget = $stmt->fetch(PDO::FETCH_ASSOC);
            if($budget)
            {
                echo json_encode(["ok" => true, "budget" => $budget]);
            }
            else
            {
                echo json_encode(["ok" => false, "error" => "Budget not found"]);
            }
        }
        catch(PDOException $e)
        {
            echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
        }
        break;
    }
    case'EditBudget':
    {
        if(!isset($_SESSION["LoginName"]))
        {
            echo json_encode(["ok" => false, "error" => "Please Log in to edit a budget"]);
            exit();
        }
        $BudgetID = $_POST["BudgetID"] ?? '';
        $Threshold = $_POST["Threshold"] ?? '';
        if(empty($BudgetID) || empty($Threshold))
        {
            echo json_encode(["ok" => false, "error" => "All fields are required to edit a budget"]);
            exit();
        }
        if($Threshold <= 0)
        {
            echo json_encode(["ok" => false, "error" => "Budget Threshold must be greater than zero"]);
            exit();
        }
        try
        {
            $stmt = $conn->prepare("UPDATE Budget SET Threshold = ? WHERE BudgetID = ?");
            $stmt->execute([$Threshold, $BudgetID]);
            echo json_encode(["ok" => true, "message" => "Budget updated successfully"]);
        }
        catch(PDOException $e)
        {
            echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
        }
        break;
    }
    case'DeleteBudget':
    {
        if(!isset($_SESSION["LoginName"]))
        {
            echo json_encode(["ok" => false, "error" => "Please Log in to create a budget"]);
            exit();
        }
        $BudgetID = $_POST["BudgetID"] ?? '';
        if(empty($BudgetID))
        {
            echo json_encode(["ok" => false, "error" => "Budget ID is required to delete a budget"]);
            exit();
        }
        
        try
        {
            $stmt = $conn->prepare("DELETE FROM Budget WHERE BudgetID = ?");
            $stmt->execute([$BudgetID]);
            echo json_encode(["ok" => true, "message" => "Budget deleted successfully"]);
        }
        catch(PDOException $e)
        {
            echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
        }
        break;
    }
    case'SpendingVsBudget':
    {
        if(!isset($_SESSION["LoginName"]))
        {
            echo json_encode(["ok" => false, "error" => "Please Log in"]);
            exit();
        }
        $AccountNumber = $_GET["AccountNumber"] ?? '';

        if(empty($AccountNumber))
        {
            echo json_encode(["ok" => false, "error" => "Account Number is required"]);
            exit();
        }

        try
        {
  
            $stmt = $conn->prepare(
                "SELECT 
                    Budget.Category,
                    Budget.Threshold,
                    Budget.Frequency,
                    COALESCE(SUM(Transactions.Amount),0) AS TotalSpent,
                    Budget.Threshold - COALESCE(SUM(Transactions.Amount),0) AS Remaining    
                FROM Budget     
                LEFT JOIN Transactions
                    ON Budget.AccountNumber = Transactions.AccountNumber AND Budget.Category = Transactions.Category
                WHERE Budget.AccountNumber = ?
                GROUP BY Budget.BudgetID, Budget.Category, Budget.Threshold, Budget.Frequency
                ");
            $stmt->execute([$AccountNumber]);
            $Summaries = $stmt->fetchall(PDO::FETCH_ASSOC);
            echo json_encode(["ok"=> true, "summaries" => $summaries]);
        }
        catch(PDOException $e)
        {
            echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]); 
        }
        break;

    }

}

?>