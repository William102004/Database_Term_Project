const AccountNumber = sessionStorage.getItem("AccountNumber");

if(!AccountNumber) 
{
    window.location.href = "HomePage.html";
}

function goBack()
{
    window.location.href = "AccountPage.html";
}

function loadSpendingVsBudget()
{
    fetch(`../APIs/Budgets.php?action=SpendingVsBudget&AccountNumber=${AccountNumber}`)
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            displayComparison(data.summaries);
        }
        else
        {
            alert("Error loading data: " + data.error);
        }
        
    })
    .catch(err=> console.log("Fetch error: ", err));
}

function displayComparison(summaries)
{
    const tbody = document.getElementById("comparison-tbody");
    tbody.innerHTML = "";

    summaries.forEach(row => {
        const overBudget = parseFloat(row.Remaining) < 0;
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${row.Category}</td>
            <td>$${parseFloat(row.Threshold).toFixed(2)}</td>
            <td>${row.Frequency}</td>
            <td>$${parseFloat(row.TotalSpent).toFixed(2)}</td>
            <td>$${parseFloat(row.Remaining).toFixed(2)}</td>
            <td style="color: ${overBudget ? 'red' : 'green'};">${overBudget ? 'Over Budget' : 'Within Budget'}</td>
        `;
        tbody.appendChild(tr);
    });
}

window.addEventListener("pageshow", function(event)
{
    loadSpendingVsBudget();
});