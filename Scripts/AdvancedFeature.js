const AccountNumber = sessionStorage.getItem("AccountNumber");

if (!AccountNumber)
{
    window.location.href = "HomePage.html";
}

const CATEGORY_COLORS = {
    Food:           "#e74c3c",
    Transportation: "#3498db",
    Entertainment:  "#9b59b6",
    Utilities:      "#f39c12",
    Groceries:      "#27ae60"
};

let spendingChart = null;

function goBack()
{
    window.location.href = "AccountPage.html";
}

function loadAnalysis()
{
    fetch(`../APIs/Transactions.php?action=MonthlySpending&AccountNumber=${AccountNumber}`)
    .then(response => response.json())
    .then(data => {
        if (data.ok)
        {
            renderChart(data.monthly);
            renderCategorySummary(data.monthly);
        }
        else
        {
            alert("Error loading analysis data: " + data.error);
        }
    })
    .catch(err => console.log("Fetch error: ", err));
}

function renderChart(monthly)
{
    // Data comes in descending month order — reverse for chronological display
    const months     = [...new Set(monthly.map(r => r.Month))].reverse();
    const categories = [...new Set(monthly.map(r => r.Category))];

    const datasets = categories.map(cat => {
        const color = CATEGORY_COLORS[cat] || "#95a5a6";
        return {
            label: cat,
            data: months.map(month => {
                const row = monthly.find(r => r.Month === month && r.Category === cat);
                return row ? parseFloat(row.TotalSpent) : 0;
            }),
            backgroundColor: color,
            borderColor:      color,
            borderWidth: 1
        };
    });

    const ctx = document.getElementById("spending-chart").getContext("2d");

    if (spendingChart) spendingChart.destroy();

    spendingChart = new Chart(ctx, {
        type: "bar",
        data: { labels: months, datasets },
        options: {
            responsive: true,
            plugins: {
                legend: { position: "top" }
            },
            scales: {
                x: { stacked: false },
                y: {
                    stacked: false,
                    beginAtZero: true,
                    ticks: {
                        callback: value => "$" + value.toFixed(2)
                    }
                }
            }
        }
    });
}

function renderCategorySummary(monthly)
{
    const totals = {};
    monthly.forEach(row => {
        if (!totals[row.Category])
        {
            totals[row.Category] = { spent: 0, transactions: 0 };
        }
        totals[row.Category].spent        += parseFloat(row.TotalSpent);
        totals[row.Category].transactions += parseInt(row.TotalTransactions);
    });

    const tbody = document.getElementById("category-summary-tbody");
    tbody.innerHTML = "";

    const sorted = Object.entries(totals).sort((a, b) => b[1].spent - a[1].spent);

    if (sorted.length === 0)
    {
        tbody.innerHTML = "<tr><td colspan='3'>No transaction data found.</td></tr>";
        return;
    }

    sorted.forEach(([category, info]) => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${category}</td>
            <td>$${info.spent.toFixed(2)}</td>
            <td>${info.transactions}</td>
        `;
        tbody.appendChild(tr);
    });
}

window.addEventListener("pageshow", function()
{
    loadAnalysis();
});