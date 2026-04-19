// Get data from session storage
const TransactionNumber = sessionStorage.getItem("TransactionNumber");
const AccountNumber = sessionStorage.getItem("AccountNumber");

// Check if transaction number exists
if(!TransactionNumber || !AccountNumber)
{
    window.location.href = "HomePage.html";
}

// Load transaction details on page load
loadTransactionDetails();

// Load transaction details
function loadTransactionDetails()
{
    const params = new URLSearchParams();
    params.append("TransactionNumber", TransactionNumber);
    params.append("AccountNumber", AccountNumber);

    fetch(`../APIs/Transactions.php?action=GetTransaction&${params.toString()}`)
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            displayTransactionDetails(data.transaction);
        }
        else
        {
            alert("Error loading transaction: " + data.error);
            window.location.href = "AccountPage.html";
        }
    })
    .catch(err => {
        console.log("Fetch error: ", err);
        alert("Failed to load transaction");
        window.location.href = "AccountPage.html";
    });
}

// Display transaction details
function displayTransactionDetails(transaction)
{
    document.getElementById("detail-name").textContent = transaction.Name || "--";
    document.getElementById("detail-amount").textContent = "$" + parseFloat(transaction.Amount).toFixed(2);
    document.getElementById("detail-category").textContent = transaction.Category || "--";
    document.getElementById("detail-date").textContent = transaction.Date || "--";
}

// Go back to account page
function goBack()
{
    window.location.href = "AccountPage.html";
}

// Open edit transaction modal
function openEditTransactionModal()
{
    // Fetch the current transaction data and populate the form
    const params = new URLSearchParams();
    params.append("TransactionNumber", TransactionNumber);
    params.append("AccountNumber", AccountNumber);

    fetch(`../APIs/Transactions.php?action=GetTransaction&${params.toString()}`)
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            const transaction = data.transaction;
            document.getElementById("edit-transaction-name").value = transaction.Name;
            document.getElementById("edit-transaction-amount").value = transaction.Amount;
            document.getElementById("edit-transaction-category-options").value = transaction.Category;
            document.getElementById("edit-transaction-date").value = transaction.Date;
            document.getElementById("edit-transaction-model").style.display = "flex";
        }
        else
        {
            alert("Error loading transaction: " + data.error);
        }
    })
    .catch(err => console.log("Fetch error: ", err));
}

// Save edited transaction
function saveEditTransaction()
{
    const Name = document.getElementById("edit-transaction-name").value;
    const Amount = document.getElementById("edit-transaction-amount").value;
    const Category = document.getElementById("edit-transaction-category-options").value;
    const TransactionDate = document.getElementById("edit-transaction-date").value;

    if(!Name || !Amount || !TransactionDate || !Category)
    {
        alert("Please fill in all fields.");
        return;
    }

    const data = new FormData();
    data.append("action", "EditTransaction");
    data.append("Name", Name);
    data.append("Amount", Amount);
    data.append("Category", Category);
    data.append("Date", TransactionDate);
    data.append("TransactionNumber", TransactionNumber);
    data.append("AccountNumber", AccountNumber);

    fetch('../APIs/Transactions.php', {method : 'POST', body: data})
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            closeAllModels();
            loadTransactionDetails();
        }
        else
        {
            alert("Failed to edit transaction: " + data.error);
        }
    })
    .catch(err => console.log("Fetch error: ", err));
}

// Delete transaction from detail page
function deleteTransactionFromDetail()
{
    if(!confirm("Are you sure you want to delete this transaction?")) return;

    const data = new FormData();
    data.append("action", "DeleteTransaction");
    data.append("TransactionNumber", TransactionNumber);
    data.append("AccountNumber", AccountNumber);

    fetch('../APIs/Transactions.php', {method : 'POST', body: data})
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            window.location.href = "AccountPage.html";
        }
        else
        {
            alert("Failed to delete transaction: " + data.error);
        }
    })
    .catch(err => console.log("Fetch error: ", err));
}