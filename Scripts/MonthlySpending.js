const AccountNumber = sessionStorage.getItem("AccountNumber");

if(!AccountNumber) 
{
    window.location.href = "HomePage.html";
}

function goBack()
{
    window.location.href = "AccountPage.html";
}

function loadMonthlySpending()
{
    fetch(`../APIs/Transactions.php?action=MonthlySpending&AccountNumber=${AccountNumber}`)
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            displayMonthlySpending(data.monthly);
        }
        else
        {
            alert("Error loading data: " + data.error);
        }
        
    })
    .catch(err=> console.log("Fetch error: ", err));

}

function displayMonthlySpending(monthly)
{
    const tbody = document.getElementById("monthly-spending-tbody");
    tbody.innerHTML = "";
    
    monthly.forEach(row => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${row.Month}</td>
            <td>${row.Category}</td>
            <td>$${parseFloat(row.TotalSpent).toFixed(2)}</td>
            <td>${row.TotalTransactions}</td>
        `;
        tbody.appendChild(tr);
    });
}

window.addEventListener("pageshow", function(event)
{
    loadMonthlySpending();
});