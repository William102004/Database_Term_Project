if(!sessionStorage.getItem("LoginName"))
{
    window.location.href = "LoginPage.html";
}

const Name = sessionStorage.getItem("Name");
const LoginName = sessionStorage.getItem("LoginName");

//Delete User
function deleteUser()
{
    //Prompt user to confirm
    if(!confirm("Are you sure? This will permanently delete your account and all your data.")) return;

    //Follow through on action with Delete action in php file
    const data = new FormData();
    data.append("action", "DeleteUser");

    fetch('../APIs/Users.php', { method: 'POST', body: data })
    .then(Response=> Response.json())
    .then(data => {
        if(data.ok)
        {
            sessionStorage.clear();
            window.location.href = "LoginPage.html";
        }
        else
        {
            alert("Error: " + data.error);
        }
    });
}

//Load accounts
function loadAccounts()
{
    fetch('../APIs/Account.php?action=GetAccounts')
    .then(Response => Response.json())
    .then(data => {
        if(data.ok)
        {
            displayAccounts(data.accounts);
        }
        else
        {
            alert("Error: " + data.error);
        }
    });
}

//Display accounts in table 
function displayAccounts(accounts)
{
    const tbody = document.getElementById("accounts-tbody");
    tbody.innerHTML = "";

    if(accounts.length === 0)
    {
        tbody.innerHTML = `<tr><td colspan="3">No accounts yet. Add one to start</td></tr>`;
        return;
    }

    accounts.forEach(account => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td class="account-row-name"    onclick="goToAccount(${account.AccountNumber})">${account.AccountName}</td>
            <td class="account-row-balance" onclick="goToAccount(${account.AccountNumber})">$${parseFloat(account.Balance).toFixed(2)}</td>
            <td class="account-row-edit">
                <div class="edit-wrapper">
                    <div class="edit-circle" onclick="toggleEditMenu(${account.AccountNumber})">⋯</div>
                    <div class="dropdown-menu" id="edit-menu-${account.AccountNumber}" style="display:none;">
                        <button onclick="openUpdateBalanceModel(${account.AccountNumber})">Update Balance</button>
                        <button onclick="openUpdateAccountNameModel(${account.AccountNumber}, '${account.AccountName}')">Update Name</button>
                        <button class="delete-btn" onclick="deleteAccount(${account.AccountNumber})">Delete Account</button>
                    </div>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

//Go to page of a specific account
function goToAccount(accountNumber)
{
    sessionStorage.setItem("AccountNumber", accountNumber);
    window.location.href = "AccountPage.html";
}


//Add Account
function openAddAccountModel()
{
    closeAllModels();
    document.getElementById("add-account-model").style.display = "flex";
}

//Insert an account into table
function insertAccount()
{
    //Get account name and balance for display
    const accountName = document.getElementById("add-account-name").value.trim();
    const balance = document.getElementById("add-account-balance").value;

    //Require user to fill out name and balance
    if(!accountName || balance === "")
    {
        alert("Please fill out all fields.");
        return;
    }

    //Use Insert account action in php file
    const data = new FormData();
    data.append("action",      "InsertAccount");
    data.append("AccountName", accountName);
    data.append("Balance",     balance);

    fetch('../APIs/Account.php', { method: 'POST', body: data })
    .then(Response => Response.json())
    .then(data => {
        if(data.ok)
        {
            document.getElementById("add-account-name").value = "";
            document.getElementById("add-account-balance").value = "";
            closeAllModels();
            loadAccounts(); //reload accounts
        }
        else
        {
            alert("Error: " + data.error);
        }
    })
    .catch(err => console.log("Fetch error:", err));
}

// Update Balance
function openUpdateBalanceModel(accountNumber)
{
    closeAllModels();
    document.getElementById("balance-account-number").value = accountNumber;
    document.getElementById("update-balance-model").style.display = "flex";
}


function updateBalance()
{
    //Get balance and account number
    const accountNumber = document.getElementById("balance-account-number").value;
    const balance = document.getElementById("new-balance").value;

    //Require balance to be entered
    if(balance === "")
    {
        alert("Please enter a balance.");
        return;
    }

    //Perform Update balance action in php file
    const data = new FormData();
    data.append("action",        "UpdateBalance");
    data.append("AccountNumber", accountNumber);
    data.append("Balance",       balance);

    fetch('../APIs/Account.php', { method: 'POST', body: data })
    .then(Response => Response.json())
    .then(data => {
        if(data.ok)
        {
            //close models and reload accounts with new data
            closeAllModels();
            loadAccounts();
        }
        else
        {
            alert("Error: " + data.error);
        }
    });
}

//Update Account Name
function openUpdateAccountNameModel(accountNumber, currentName)
{
    closeAllModels();
    document.getElementById("edit-account-number").value               = accountNumber;
    document.getElementById("new-account-name").value                  = currentName;
    document.getElementById("update-account-name-model").style.display = "flex";
}

function updateAccountName()
{
    //get new account name
    const accountNumber = document.getElementById("edit-account-number").value;
    const accountName = document.getElementById("new-account-name").value.trim();

    //Require new account name be put
    if(!accountName)
    {
        alert("Please enter a name.");
        return;
    }

    //Use UpdateAccountName action in php file
    const data = new FormData();
    data.append("action",        "UpdateAccountName");
    data.append("AccountNumber", accountNumber);
    data.append("AccountName",   accountName);

    fetch('../APIs/Account.php', { method: 'POST', body: data })
    .then(Response => Response.json())
    .then(data => {
        if(data.ok)
        {
            //Close models and reload update accoun table
            closeAllModels();
            loadAccounts();
        }
        else
        {
            alert("Error: " + data.error);
        }
    });
}

// Delete Account
function deleteAccount(accountNumber)
{
    //Ask user to confirm if they want to delete said account
    if(!confirm("Delete this account? All transactions and budgets will also be deleted.")) return;

    //Use DeleteAccount action from php file
    const data = new FormData();
    data.append("action",        "DeleteAccount");
    data.append("AccountNumber", accountNumber);

    fetch('../APIs/Account.php', { method: 'POST', body: data })
    .then(Response => Response.json())
    .then(data => {
        if(data.ok)
        {
            //reload account table
            loadAccounts();
        }
        else
        {
            alert("Error: " + data.error);
        }
    });
}


loadAccounts();