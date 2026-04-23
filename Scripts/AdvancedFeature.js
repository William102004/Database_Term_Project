const AccountNumber = sessionStorage.getItem("AccountNumber");

if (!AccountNumber)
{
    window.location.href = "HomePage.html";
}

let trendChart = null;

const RISK_STYLES = {
    High:   { bg: "#e74c3c", text: "white" },
    Medium: { bg: "#f39c12", text: "white" },
    Low:    { bg: "#27ae60", text: "white" }
};

function goBack()
{
    window.location.href = "AccountPage.html";
}

function loadAnalysis()
{
    fetch(`../APIs/AdvancedFeature.php?action=AnalyzeSpending&AccountNumber=${AccountNumber}`)
    .then(response => response.json())
    .then(data => {
        if (data.ok)
        {
            renderPeriodLabel(data.currentMonth, data.prevMonth);
            renderSummaryCards(data.analysis);
            renderChart(data.analysis);
            renderTable(data.analysis);
        }
        else
        {
            alert("Error loading analysis: " + data.error);
        }
    })
    .catch(err => console.log("Fetch error: ", err));
}

function renderPeriodLabel(current, prev)
{
    const el = document.getElementById("period-label");
    if (!current)
    {
        el.textContent = "No spending data found for this account.";
        return;
    }
    el.textContent = prev
        ? "Comparing " + current + " to " + prev
        : current + " — baseline month (no prior data to compare yet)";
}

function renderSummaryCards(analysis)
{
    const counts = { High: 0, Medium: 0, Low: 0 };
    analysis.forEach(row => counts[row.RiskLevel]++);
    document.getElementById("count-high").textContent   = counts.High;
    document.getElementById("count-medium").textContent = counts.Medium;
    document.getElementById("count-low").textContent    = counts.Low;
}

function renderChart(analysis)
{
    // Only show chart when there is a previous month to compare against
    const comparable = analysis.filter(r => r.PreviousSpend !== null);
    if (comparable.length === 0)
    {
        document.getElementById("chart-section").style.display = "none";
        return;
    }

    document.getElementById("chart-section").style.display = "block";

    const labels   = comparable.map(r => r.Category);
    const prevData = comparable.map(r => r.PreviousSpend);
    const currData = comparable.map(r => r.CurrentSpend);

    const ctx = document.getElementById("trend-chart").getContext("2d");
    if (trendChart) trendChart.destroy();

    trendChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels,
            datasets: [
                {
                    label: "Previous Month",
                    data: prevData,
                    backgroundColor: "#95a5a6",
                    borderColor:     "#7f8c8d",
                    borderWidth: 1
                },
                {
                    label: "Current Month",
                    data: currData,
                    backgroundColor: "#2c3e50",
                    borderColor:     "#1a252f",
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: "top" } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: v => "$" + parseFloat(v).toFixed(2) }
                }
            }
        }
    });
}

function renderTable(analysis)
{
    const tbody = document.getElementById("analysis-tbody");
    tbody.innerHTML = "";

    if (analysis.length === 0)
    {
        tbody.innerHTML = "<tr><td colspan='7'>No spending data found for this account.</td></tr>";
        return;
    }

    analysis.forEach(row => {
        const style = RISK_STYLES[row.RiskLevel];

        const changeText = row.ChangePercent !== null
            ? (row.ChangePercent >= 0 ? "+" : "") + row.ChangePercent + "%"
            : "—";

        const changeStyle = row.ChangePercent === null ? "" :
            row.ChangePercent > 0
                ? "color:#e74c3c; font-weight:bold;"
                : "color:#27ae60; font-weight:bold;";

        const budgetText = row.BudgetPercent !== null
            ? row.BudgetPercent + "% of $" + parseFloat(row.BudgetThreshold).toFixed(2)
            : "No budget";

        const flagsHtml = row.Flags
            .map(f => `<div style="font-size:0.8rem; color:#7f8c8d; margin-top:3px;">• ${f}</div>`)
            .join("");

        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td style="font-weight:bold; color:#2c3e50;">${row.Category}</td>
            <td>$${parseFloat(row.CurrentSpend).toFixed(2)}</td>
            <td>${row.PreviousSpend !== null ? "$" + parseFloat(row.PreviousSpend).toFixed(2) : "—"}</td>
            <td style="${changeStyle}">${changeText}</td>
            <td>${budgetText}</td>
            <td>
                <span style="background:${style.bg}; color:${style.text}; padding:4px 10px; border-radius:12px; font-size:0.85rem; font-weight:bold; white-space:nowrap;">
                    ${row.RiskLevel}
                </span>
            </td>
            <td>
                <div style="font-size:0.9rem; color:#34495e;">${row.Feedback}</div>
                ${flagsHtml}
            </td>
        `;
        tbody.appendChild(tr);
    });
}

window.addEventListener("pageshow", function()
{
    loadAnalysis();
});
