<?php
session_start();
require_once "../dbconnect.php";
header("Content-Type: application/json; charset=UTF-8");

$action = $_POST["action"] ?? $_GET["action"] ?? '';

switch($action)
{
    case 'register':
        $LoginName = $_POST["LoginName"] ?? '';
        $Name = $_POST["Name"] ?? '';
        $LoginPassword = $_POST["LoginPassword"] ?? '';

        if(empty($LoginName) || empty($Name) || empty($LoginPassword))
        {
            echo json_encode(["ok" => false, "error" => "Fill out all fields"]);
            exit();
        }

        try
        {
            $check = $conn->prepare("SELECT * FROM Users WHERE LoginName = ?");
            $check->execute([$LoginName]);
            if($check->fetch())
            {
                echo json_encode(["ok" => false, "error" => "Username already taken. Try again"]);
                exit();
            }
            
            $hashed = password_hash($LoginPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO Users (LoginName, Name, LoginPassword) VALUES (?, ?, ?)");
            $stmt->execute([$LoginName, $Name, $hashed]);
            echo json_encode(["ok" => true, "message" => "User registered successfully"]);
        }
        catch(PDOException $e)
        {
            echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
        }
        break;  
    
    case 'login':
        $LoginName = $_POST["LoginName"] ?? '';
        $LoginPassword = $_POST["LoginPassword"] ?? '';
        

        if(empty($LoginName) || empty($LoginPassword))
        {
            echo json_encode(["ok" => false, "error" => "Fill out all fields"]);
            exit();
        }

        try
        {
            $stmt = $conn->prepare("SELECT * FROM Users WHERE LoginName = ?");
            $stmt->execute([$LoginName]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($user && password_verify($LoginPassword, $user["LoginPassword"]))
            {
                session_regenerate_id(true);
                $_SESSION["LoginName"] = $user["LoginName"];
                $_SESSION["Name"] = $user["Name"];
                echo json_encode(["ok" => true, "message" => "Login successful", "LoginName" => $user["LoginName"], "Name" => $user["Name"]]);          
            }
            else
            {
                echo json_encode(["ok" => false, "error" => "Invalid username or password"]);
            }
        }
        catch(PDOException $e)
        {
            echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
            exit();
        }
        break;
    
    case 'DeleteUser':
       
        if(isset($_SESSION["LoginName"]))
        {
            $LoginName = $_SESSION["LoginName"];
            try
            {
                $stmt = $conn->prepare("DELETE FROM Users WHERE LoginName = ?");
                $stmt->execute([$LoginName]);

                session_destroy();
                echo json_encode(["ok" => true, "message" => "User deleted successfully"]);
            }
            catch(PDOException $e)
            {
                echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
            }
        }
        else
        {
            echo json_encode(["ok" => false, "error" => "Login to account to delete it"]);
        }
        break;
        
    case 'GetUser':
        if(isset($_GET["LoginName"]))
        {
            $LoginName = $_GET["LoginName"];
            try
            {
                $stmt = $conn->prepare("SELECT LoginName, Name FROM Users WHERE LoginName = ?");
                $stmt->execute([$LoginName]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if($user)
                {
                    echo json_encode(["ok" => true, "user" => $user]);
                }
                else
                {
                    echo json_encode(["ok" => false, "error" => "User not found"]);
                }
            }
            catch(PDOException $e)
            {
                echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
            }
        }
        else
        {
            echo json_encode(["ok" => false, "error" => "Login to account to get it"]);
        }
        break;
    
    case 'UpdateUserName':
    {
        
        if(isset($_SESSION["LoginName"]))
        {
            $Name = $_POST["Name"] ?? '';
            if(empty($Name))
            {
                echo json_encode(["ok" => false, "error" => "Fill out all fields"]);
                exit();
            }
            $LoginName = $_SESSION["LoginName"];
            try
            {
            $stmt = $conn->prepare("UPDATE Users SET Name = ? WHERE LoginName = ?");
            $stmt->execute([$Name, $LoginName]);
            echo json_encode(["ok" => true, "message" => "User name updated successfully"]);
            }
            catch(PDOException $e)
            {
                echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
            }
        }
        else
        {
            echo json_encode(["ok" => false, "error" => "Login to account to update Name"]);
        }
        break;
    }
    case 'UpdateUserPasswordLoggedIN':
    {
        if(isset($_SESSION["LoginName"]))
        {
            $LoginPassword = $_POST["LoginPassword"] ?? '';
            if(empty($LoginPassword))
            {
                echo json_encode(["ok" => false, "error" => "Fill out all fields"]);
                exit();
            }
            $LoginName = $_SESSION["LoginName"];
            try
            {
                $hashed = password_hash($LoginPassword, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE Users SET LoginPassword = ? WHERE LoginName = ?");
                $stmt->execute([$hashed, $LoginName]);
                echo json_encode(["ok" => true, "message" => "User password updated successfully"]);
           
            }
            catch(PDOException $e)
            {
                echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
            }
        }
        else
        {
            echo json_encode(["ok" => false, "error" => "Login to account to update password"]);
        }
        break;
    }
    case 'ForgotPassword':
    {
        $LoginName = $_POST["LoginName"] ?? '';
        $NewPassword = $_POST["NewPassword"] ?? '';
        if(empty($LoginName) || empty($NewPassword))
        {
            echo json_encode(["ok" => false, "error" => "Fill out all fields"]);
            exit();
        }
        try
        {
            $stmt = $conn->prepare("SELECT * FROM Users WHERE LoginName = ?");
            $stmt->execute([$LoginName]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($user)
            {
                try
                {
                    $hashed = password_hash($NewPassword, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE Users SET LoginPassword = ? WHERE LoginName = ?");
                    $stmt->execute([$hashed, $LoginName]);
                    echo json_encode(["ok" => true, "message" => "User password updated successfully"]);
                }
                catch(PDOException $e)
                {
                    echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
                }
            }
            else
            {
                echo json_encode(["ok" => false, "error" => "User not found"]);
            }
        }
        catch(PDOException $e)
        {
            echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
            exit();
        }
        break;
    }
    case 'logout':
    {
        session_destroy();
        echo json_encode(["ok" => true, "message" => "Logout successful"]);
        break;
    }
}


$conn = null;
?>