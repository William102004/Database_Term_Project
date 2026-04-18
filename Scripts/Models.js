const UserName = sessionStorage.getItem("Name");

if(!UserName)
{
    window.location.href = "LoginPage.html";
}

// Set initials circle and name
const initials = UserName.split(" ").map(w => w[0]).join("").toUpperCase().slice(0, 2);
document.getElementById("user-circle").textContent       = initials;
document.getElementById("user-name-display").textContent = UserName;



//Toggle user circle menu
function toggleUserMenu()
{
    const menu = document.getElementById("user-menu");
    const isOpen = menu.style.display === "block";
    document.querySelectorAll(".dropdown-menu").forEach(m => m.style.display = "none");
    if(!isOpen) menu.style.display = "block";
}

//Close menus when clicking outside
document.addEventListener("click", function(e)
{
    const clickedInsideMenu = e.target.closest(".dropdown-menu");
    const clickedEditCircle = e.target.closest(".edit-circle");
    const clickedUserCircle = e.target.closest(".user-circle");

    if(!clickedInsideMenu && !clickedEditCircle && !clickedUserCircle)
    {
        document.querySelectorAll(".dropdown-menu").forEach(m => m.style.display = "none");
    }
});

//Change User Name
function openChangeNameModel()
{
    closeAllModels();
    document.getElementById("change-name-model").style.display = "flex";
}

function changeName()
{
    const newName = document.getElementById("new-user-name").value.trim();

    if(!newName)
    {
        alert("Please enter a name.");
        return;
    }

    const data = new FormData();
    data.append("action", "UpdateUserName");
    data.append("Name",   newName);

    fetch('../APIs/Users.php', { 
        method: 'POST', 
        body: data
     })
    .then(Response => Response.json())
    .then(data => {
        if(data.ok)
        {
            //Update Name and Display of Name and initial
            sessionStorage.setItem("Name", newName);
            document.getElementById("user-name-display").textContent = newName;
            const updatedInitials = newName.split(" ").map(w => w[0]).join("").toUpperCase().slice(0, 2);
            document.getElementById("user-circle").textContent = updatedInitials;
            closeAllModels();
        }
        else
        {
            alert("Error: " + data.error);
        }
    });
}

// Change Password
function openChangePasswordModel()
{
    closeAllModels();
    document.getElementById("change-password-model").style.display = "flex";
}

function changePassword()
{
    //Get new Password
    const newPassword = document.getElementById("new-password").value;

    //Ask for input
    if(!newPassword)
    {
        alert("Please enter a new password.");
        return;
    }

    //Add minimum password length
    if(newPassword.length < 6)
    {
        alert("Password must be at least 6 characters.");
        return;
    }

    //Update password calling action from php file
    const data = new FormData();
    data.append("action",        "UpdateUserPasswordLoggedIN");
    data.append("LoginPassword", newPassword);

    fetch('../APIs/Users.php', { method: 'POST', body: data })
    .then(Response => Response.json())
    .then(data => {
        if(data.ok)
        {
            alert("Password changed successfully.");
            closeAllModels();
        }
        else
        {
            alert("Error: " + data.error);
        }
    });
}

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

//Close all models
function closeAllModels()
{
    document.querySelectorAll(".model").forEach(m => m.style.display = "none");
}


//Toggle edit circle menu
function toggleEditMenu(id)
{
    const menu = document.getElementById("edit-menu-" + id);
    const isOpen = menu.style.display === "block";
    document.querySelectorAll(".dropdown-menu").forEach(m => m.style.display = "none");
    if(!isOpen) menu.style.display = "block";
}