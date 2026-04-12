function register()
{
    const data = new FormData();
    data.append('action', 'register');
    data.append('LoginName', document.getElementById('LoginName').value);
    data.append('Name', document.getElementById('Name').value);
    data.append('LoginPassword', document.getElementById('LoginPassword').value);

    fetch('../APIs/Users.php', {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            alert("Registered Successfully you may now login to your account");
            window.location.href = 'LoginPage.html';
        }
        else
        {
            alert("Error: " + data.error);
        }
    })
}

function login()
{
    const data = new FormData();
    data.append('action', 'login');
    data.append('LoginName', document.getElementById('LoginName').value);
    data.append('LoginPassword', document.getElementById('LoginPassword').value);
    
    fetch('../APIs/Users.php', {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            sessionStorage.setItem("LoginName", data.LoginName);
            sessionStorage.setItem("Name",      data.Name);
            alert("Login Successful");
            window.location.href = "HomePage.html";
        }
        else
        {
            alert("Error: " + data.error);
        }
    });
}
function ForgotPassword()
{
    const data = new FormData();
    data.append('action', 'ForgotPassword');
    data.append('LoginName', document.getElementById('LoginName').value);
    data.append('NewPassword', document.getElementById('NewPassword').value);
    
    fetch('../APIs/Users.php', {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(data => {
        if(data.ok)
        {
            alert("Password Updated Successfully you may now login to your account");
            window.location.href = 'LoginPage.html';
        }
        else
        {
            alert("Error: " + data.error);
        }
    });
}

