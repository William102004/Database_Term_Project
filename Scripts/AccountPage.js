const AccountNumber = sessionStorage.getItem("AccountNumber");


if(!AccountNumber)
{
    window.location.href = "HomePage.html";
}


function openAddTransactionModel(AccountNumber) 
{
    closeAllModels();
    document.getElementById("add-transaction-model").style.display = "flex";   
}

function openEditTransactionModel(TransactionNumber, AccountNumber)
{
    closeAllModels();
    document.getElementById("update-transaction-number").value = TransactionNumber;
    document.getElementById("update-transaction-model").style.display = "flex";
}


function loadTransactions()
{
    const Name = document.getElementById("filter-name").value;
    const Category = document.getElementById("filter-category").value;
    const AmountMin = document.getElementById("filter-amount-min").value;
    const AmountMax = document.getElementById("filter-amount-max").value;
    const DateFrom = document.getElementById("filter-date-from").value;
    const DateTo = document.getElementById("filter-date-to").value;

    const params = new URLSearchParams();
    params.append("AccountNumber", AccountNumber);
    if(Name) params.append("Name", Name);
    if(Category) params.append("Category", Category);
    if(AmountMin) params.append("AmountMin", AmountMin);
    if(AmountMax) params.append("AmountMax", AmountMax);
    if(DateFrom) params.append("DateFrom", DateFrom);
    if(DateTo) params.append("DateTo", DateTo);

    fetch(`../APIs/Transactions.php?action=GetTransactions&${params.toString()}`)
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            displayTransactions(data.transactions);
        }
        else
        {
            alert("Failed to load transactions: " + data.error);
        }
    });
}

function clearFilters()
{
    document.getElementById("filter-name").value = "";
    document.getElementById("filter-category").value = "";
    document.getElementById("filter-amount-min").value = "";
    document.getElementById("filter-amount-max").value = "";
    document.getElementById("filter-date-from").value = "";
    document.getElementById("filter-date-to").value = "";
    loadTransactions();
}

function displayTransactions(transactions)
{
    const tbody = document.getElementById("transactions-tbody");
    tbody.innerHTML = "";
    
    if(transactions.length === 0)
    {
        tbody.innerHTML = "<tr><td colspan='5'>No transactions found.</td></tr>";
        return;
    }
    transactions.forEach(transaction => {
     const tr = document.createElement("tr");
     tr.innerHTML = `
            <td class="transaction-row-name"    onclick="goToTransactionDetails(${transaction.TransactionNumber})">${transaction.Name}</td>
            <td class="transaction-row-balance" onclick="goToTransactionDetails(${transaction.TransactionNumber})">$${parseFloat(transaction.Amount).toFixed(2)}</td>
            <td class="transaction-row-category" onclick="goToTransactionDetails(${transaction.TransactionNumber})">${transaction.Category}</td>
            <td class="transaction-row-date" onclick="goToTransactionDetails(${transaction.TransactionNumber})">${transaction.Date}</td>
            <td class="transaction-row-edit">
                <div class="edit-wrapper">
                    <div class="edit-circle" onclick="toggleEditMenu(${transaction.TransactionNumber})">⋯</div>
                    <div class="dropdown-menu" id="edit-menu-${transaction.TransactionNumber}" style="display:none;">
                    <button onclick="openEditTransactionModel(${transaction.TransactionNumber})">Edit</button>
                    <button class="delete-btn" onclick="DeleteTransaction(${transaction.TransactionNumber}, ${sessionStorage.getItem("AccountNumber")})">Delete</button>
                    </div>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });

}
function InsertTransaction()
{
    const Name = document.getElementById("add-transaction-name").value;
    const Amount = document.getElementById("add-transaction-amount").value;
    const Category = document.getElementById("add-transaction-category-options").value;
    const TransactionDate = document.getElementById("add-transaction-date").value;

    if(!Name || !Amount || !TransactionDate || !Category)
    {
        alert("Please fill in all fields.");
        return;
    }

    const data = new FormData();
    data.append("action", "InsertTransaction");
    data.append("AccountNumber", AccountNumber);
    data.append("Name", Name);
    data.append("Amount", Amount);
    data.append("Category", Category);
    data.append("Date", TransactionDate);

    fetch('../APIs/Transactions.php', {method : 'POST', body: data})
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            document.getElementById("add-transaction-name").value = "";
            document.getElementById("add-transaction-amount").value = "";
            document.getElementById("add-transaction-category-options").value = "";
            document.getElementById("add-transaction-date").value = "";
            closeAllModels();
            loadTransactions();
        }
        else
        {
            alert("Failed to add transaction: " + data.error);
        }
    })
    .catch(err => console.log("Fetch error: ",err));
}
function EditTransaction()
{
    const TransactionNumber = document.getElementById("update-transaction-number").value;
    const Name = document.getElementById("update-transaction-name").value;
    const Amount = document.getElementById("update-transaction-amount").value;
    const Category = document.getElementById("update-transaction-category-options").value;
    const TransactionDate = document.getElementById("update-transaction-date").value;

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
            loadTransactions();
        }
        else
        {
            alert("Failed to edit transaction: " + data.error);
        }
    })
    .catch(err => console.log("Fetch error: ",err));

}

function goToTransactionDetails(TransactionNumber)
{
    sessionStorage.setItem("TransactionNumber", TransactionNumber);
    window.location.href = "TransactionPage.html";
}

function DeleteTransaction(TransactionNumber, AccountNumber)
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
            loadTransactions();
        }
        else
        {
            alert("Failed to delete transaction: " + data.error);
        }
    })
    .catch(err => console.log("Fetch error: ",err));
}

function openAddBudgetModel(AccountNumber)
{
    closeAllModels();
    document.getElementById("add-budget-model").style.display = "flex";
}

function openEditBudgetModel(BudgetID, AccountNumber)
{
    closeAllModels();
    document.getElementById("update-budget-id").value = BudgetID;
    document.getElementById("update-budget-model").style.display = "flex";
}

function loadBudgets()
{
    fetch(`../APIs/Budgets.php?action=GetBudgets&AccountNumber=${sessionStorage.getItem("AccountNumber")}`)
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            displayBudgets(data.budgets);
        }
        else
        {
            alert("Failed to load budgets: " + data.error);
        }
    });
}

function displayBudgets(budgets)
{
    const tbody = document.getElementById("budget-tbody");
    tbody.innerHTML = "";

    if(budgets.length === 0)
    {
        tbody.innerHTML = "<tr><td colspan='4'>No budgets found.</td></tr>";
        return;
    }

    budgets.forEach(budget => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td class="budget-row-category" onclick="toggleEditMenu('budget-${budget.BudgetID}')">${budget.Category}</td>
            <td class="budget-row-threshold"  onclick="goToBudgetDetails(${budget.BudgetID})">$${parseFloat(budget.Threshold).toFixed(2)}</td>
            <td class="budget-row-frequency" onclick="goToBudgetDetails(${budget.BudgetID})">${budget.Frequency}</td>
            <td class="budget-row-edit">
                <div class="edit-wrapper">
                    <div class="edit-circle" onclick="toggleEditMenu('budget-${budget.BudgetID}')">⋯</div>
                    <div class="dropdown-menu" id="edit-menu-budget-${budget.BudgetID}" style="display:none;">
                    <button onclick="openEditBudgetModel(${budget.BudgetID})">Edit</button>
                    <button class="delete-btn" onclick="DeleteBudget(${budget.BudgetID}, ${sessionStorage.getItem("AccountNumber")})">Delete</button>
                    </div>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
    
}

function goToBudgetDetails(BudgetID)
{
    sessionStorage.setItem("BudgetID", BudgetID);
    window.location.href = "BudgetPage.html";
}

function InsertBudget()
{
    const Category = document.getElementById("add-budget-category-options").value;
    const Threshold = document.getElementById("add-budget-threshold").value;
    const Frequency = document.getElementById("add-budget-frequency-options").value;

    if(!Category || !Threshold || !Frequency)
    {
        alert("Please fill in all fields.");
        return;
    }
    
    const data = new FormData();
    data.append("action", "InsertBudget");
    data.append("AccountNumber", AccountNumber);
    data.append("Category", Category);
    data.append("Threshold", Threshold);
    data.append("Frequency", Frequency);

    fetch('../APIs/Budgets.php', {method : 'POST', body: data})
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            document.getElementById("add-budget-category-options").value = "";
            document.getElementById("add-budget-threshold").value = "";
            document.getElementById("add-budget-frequency-options").value = "";
            closeAllModels();
            loadBudgets();
        }
        else
        {
            alert("Failed to add budget: " + data.error);
        }
    })
    .catch(err => console.log("Fetch error: ",err));
}

function EditBudget()
{
    const BudgetID = document.getElementById("update-budget-id").value;
    const Threshold = document.getElementById("update-budget-threshold").value;

    if(!Threshold || Threshold <= 0 || !BudgetID)
    {
        alert("Please enter a valid threshold.");
        return;
    }

    const data = new FormData();
    data.append("action", "EditBudget");
    data.append("BudgetID", BudgetID);
    data.append("Threshold", Threshold);

    fetch('../APIs/Budgets.php', {method : 'POST', body: data})
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            closeAllModels();
            loadBudgets();
        }
        else
        {
            alert("Failed to edit budget: " + data.error);
        } 
    })
    .catch(err => console.log("Fetch error: ",err));
}

function DeleteBudget(BudgetID, AccountNumber)
{
    if(!confirm("Are you sure you want to delete this budget?")) return;

    const data = new FormData();
    data.append("action", "DeleteBudget");
    data.append("BudgetID", BudgetID);
    data.append("AccountNumber", AccountNumber);

    fetch('../APIs/Budgets.php', {method : 'POST', body: data})
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            loadBudgets();
        }
        else
        {
            alert("Failed to delete budget: " + data.error);
        }
    })
    .catch(err => console.log("Fetch error: ",err));
}

function loadAccount()
{
    fetch(`../APIs/Account.php?action=read_one&AccountNumber=${AccountNumber}`)
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            document.getElementById("account-name").textContent = data.account.AccountName;
            document.getElementById("account-balance").textContent = "Balance: $" + parseFloat(data.account.Balance).toFixed(2);
        }
        else
        {
            alert("Failed to load account details: " + data.error);
        }
    })
    .catch(err => console.log("Fetch error: ",err));
}

loadTransactions();
loadBudgets();
loadAccount();
