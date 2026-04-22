const BudgetID = sessionStorage.getItem("BudgetID");
const AccountNumber = sessionStorage.getItem("AccountNumber");

if (!BudgetID || !AccountNumber)
{
    window.location.href = "HomePage.html";
}

loadBudgetDetails();

function loadBudgetDetails()
{
    fetch(`../APIs/Budgets.php?action=getBudget&AccountNumber=${AccountNumber}&BudgetID=${BudgetID}`)
    .then(response => response.json())
    .then(data => {
        if (data.ok)
        {
            displayBudgetDetails(data.budget);
        }
        else
        {
            alert("Error loading budget: " + data.error);
            window.location.href = "AccountPage.html";
        }
    })
    .catch(err => {
        console.log("Fetch error: ", err);
        window.location.href = "AccountPage.html";
    });
}

function displayBudgetDetails(budget)
{
    document.getElementById("detail-category").textContent  = budget.Category  || "--";
    document.getElementById("detail-threshold").textContent = "$" + parseFloat(budget.Threshold).toFixed(2);
    document.getElementById("detail-frequency").textContent = budget.Frequency || "--";
}

function goBack()
{
    window.location.href = "AccountPage.html";
}

function openEditBudgetModal()
{
    fetch(`../APIs/Budgets.php?action=getBudget&AccountNumber=${AccountNumber}&BudgetID=${BudgetID}`)
    .then(response => response.json())
    .then(data => {
        if (data.ok)
        {
            document.getElementById("edit-budget-id").value        = data.budget.BudgetID;
            document.getElementById("edit-budget-threshold").value = data.budget.Threshold;
            document.getElementById("edit-budget-model").style.display = "flex";
        }
        else
        {
            alert("Error loading budget: " + data.error);
        }
    })
    .catch(err => console.log("Fetch error: ", err));
}

function saveEditBudget()
{
    const Threshold = document.getElementById("edit-budget-threshold").value;

    if (!Threshold || Threshold <= 0)
    {
        alert("Please enter a valid threshold greater than zero.");
        return;
    }

    const data = new FormData();
    data.append("action",    "EditBudget");
    data.append("BudgetID",  BudgetID);
    data.append("Threshold", Threshold);

    fetch('../APIs/Budgets.php', { method: 'POST', body: data })
    .then(response => response.json())
    .then(data => {
        if (data.ok)
        {
            closeAllModels();
            loadBudgetDetails();
        }
        else
        {
            alert("Failed to edit budget: " + data.error);
        }
    })
    .catch(err => console.log("Fetch error: ", err));
}

function deleteBudgetFromDetail()
{
    if (!confirm("Are you sure you want to delete this budget?")) return;

    const data = new FormData();
    data.append("action",   "DeleteBudget");
    data.append("BudgetID", BudgetID);

    fetch('../APIs/Budgets.php', { method: 'POST', body: data })
    .then(response => response.json())
    .then(data => {
        if (data.ok)
        {
            window.location.href = "AccountPage.html";
        }
        else
        {
            alert("Failed to delete budget: " + data.error);
        }
    })
    .catch(err => console.log("Fetch error: ", err));
}