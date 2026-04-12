<?php
session_start();
require_once "../dbconnect.php";
header("Content-Type: application/json; charset=utf-8");

$action = $_POST['action'] ?? $_GET['action'] ?? '';


// the 'InsertAccount' function creates an account
// POST: action=InsertAccount, LoginName, AccountName, Balance

if ($action == "InsertAccount") {
   
     if(!isset($_SESSION["LoginName"]))
    {
        echo json_encode(["ok" => false, "error" => "Please Log in"]);
        exit();
    }

    $loginName = $_SESSION["LoginName"];
    $AccountName = $_POST["AccountName"] ?? '';
    $Balance = $_POST["Balance"] ?? '';

    if($Balance < 0)
    {
        echo json_encode(["ok" => false, "error" => "Balance cannot be negative"]);
        exit();
    }
    if(empty($AccountName) || empty($Balance))
    {
        echo json_encode(["ok" => false, "error" => "All fields are required"]);
        exit();
    }

    $query = "INSERT INTO Account (LoginName,AccountName, Balance) VALUES (?, ?,?)";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$loginName, $AccountName, $Balance]);
        $accountNumber = $conn->lastInsertId();
        echo json_encode([
            "ok" => true,
            "message" => "Account created.",
            "AccountNumber" => $accountNumber
        ]);
    } catch (PDOException $e) {
        echo json_encode(["ok" => false, "error" => $e->getMessage()]);
    }


// the 'GetAccounts' showcases all accounts (optional filter by LoginName)
// 

} else if ($action == "GetAccounts") {
    
    if(!isset($_SESSION["LoginName"]))
    {
        echo json_encode(["ok" => false, "error" => "Please Log in"]);
        exit();
    }
    $loginName = $_SESSION['LoginName'] ?? '';

    if ($loginName) {
        $query = "SELECT * FROM Account WHERE LoginName = ?";
        try {
            $stmt = $conn->prepare($query);
            $stmt->execute([$loginName]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(["ok" => true, "accounts" => $rows]);
        } catch (PDOException $e) {
            echo json_encode(["ok" => false, "error" => $e->getMessage()]);
        }
    } 

// See an account through the 'AccountNumber'

} else if ($action == "read_one") {
    $accountNumber = $_GET['AccountNumber'] ?? '';

    $query = "SELECT * FROM Account WHERE AccountNumber = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$accountNumber]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(["ok" => true, "account" => $row]);
    } catch (PDOException $e) {
        echo json_encode(["ok" => false, "error" => $e->getMessage()]);
    }


// UPDATE - Update account balance


} else if ($action == "UpdateBalance") {
   
    if(!isset($_SESSION["LoginName"]))
    {
        echo json_encode(["ok" => false, "error" => "Please Log in"]);
        exit();
    }
    $LoginName = $_SESSION["LoginName"];
    $AccountNumber = $_POST["AccountNumber"] ?? '';
    $Balance = $_POST["Balance"] ?? '';

    if(empty($AccountNumber) || empty($Balance))
    {
        echo json_encode(["ok" => false, "error" => "All fields are required"]);
        exit();
    }

    $query = "UPDATE Account SET Balance = ? WHERE AccountNumber = ? AND LoginName = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$Balance, $AccountNumber, $LoginName]);
        echo json_encode(["ok" => true, "message" => "Account updated."]);
    } catch (PDOException $e) {
        echo json_encode(["ok" => false, "error" => $e->getMessage()]);
    }


// DELETE - Delete an account - needs account number

} else if ($action == "DeleteAccount") {

    if(!isset($_SESSION["LoginName"]))
    {
        echo json_encode(["ok" => false, "error" => "Please Log in"]);
        exit();
    }

    $LoginName = $_SESSION["LoginName"];
    $accountNumber = $_POST['AccountNumber'] ?? '';

    if(empty($accountNumber))
    {
        echo json_encode(["ok" => false, "error" => "Account Number is required"]);
        exit();
    }

    $query = "DELETE FROM Account WHERE AccountNumber = ? AND LoginName = ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$accountNumber, $LoginName]);
        echo json_encode(["ok" => true, "message" => "Account deleted."]);
    } catch (PDOException $e) {
        echo json_encode(["ok" => false, "error" => $e->getMessage()]);
    }


// SEARCH - Search accounts by 'LoginName'

} else if ($action == "search") {
    $q    = $_GET['q'] ?? '';
    $like = "%" . $q . "%";

    $query = "SELECT * FROM Account WHERE LoginName LIKE ?";
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$like]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["ok" => true, "accounts" => $rows]);
    } catch (PDOException $e) {
        echo json_encode(["ok" => false, "error" => $e->getMessage()]);
    }

}
else if($action == 'UpdateAccountName')
{
        if(!isset($_SESSION["LoginName"]))
        {
            echo json_encode(["ok" => false, "error" => "Please Log in"]);
            exit();
        }
        $LoginName = $_SESSION["LoginName"];
        $AccountNumber = $_POST["AccountNumber"] ?? '';
        $AccountName = $_POST["AccountName"] ?? '';

        if(empty($AccountNumber) || empty($AccountName))
        {
            echo json_encode(["ok" => false, "error" => "All fields are required"]);
            exit();
        }

        try
        {
            $stmt = $conn->prepare("UPDATE Account SET AccountName = ? WHERE LoginName = ? AND AccountNumber = ?");
            $stmt->execute([$AccountName, $LoginName, $AccountNumber]);
            echo json_encode(["ok" => true, "message" => "Account name updated successfully"]);
        }
        catch(PDOException $e)
        {
            echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
        }
} 

else {
    echo json_encode(["ok" => false, "error" => "Invalid action."]);
}

$conn = null;
?>
