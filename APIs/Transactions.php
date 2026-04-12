<?php
session_start();
require_once "../dbconnect.php";
header("Content-Type: application/json; charset=UTF-8");

$action = $_POST["action"] ?? $_GET["action"] ?? '';

switch($action)
{
    case'InsertTransaction':
    {
        if(!isset($_SESSION["LoginName"]))
        {
            echo json_encode(["ok" => false, "error" => "Please Log in"]);
            exit();
        }
        $AccountNumber = $_POST["AccountNumber"] ?? '';
        $Name = $_POST["Name"] ?? '';
        $Amount = $_POST["Amount"] ?? '';
        $Category = $_POST["Category"] ?? '';
        $Date = $_POST["Date"] ?? '';

        if(empty($AccountNumber) || empty($Name) || empty($Amount) || empty($Category) || empty($Date))
        {
            echo json_encode(["ok" => false, "error" => "All fields are required"]);
            exit();
        }

        if($Amount <= 0)
        {
            echo json_encode(["ok" => false, "error" => "Transaction Amount cannot be zero or negative"]);
            exit();
        }

        try
        {
            $stmt = $conn->prepare("INSERT INTO Transactions (AccountNumber, Name, Amount, Category, Date) VALUES (?,?,?,?,?)");
            $stmt->execute([$AccountNumber, $Name, $Amount, $Category, $Date]);
            $TransactionNumber = $conn->lastInsertId();
            echo json_encode(["ok" => true, "message" => "Transaction added successfully", "TransactionNumber" => $TransactionNumber]);
        }
        catch(PDOException $e)
        {
            echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
        }
        break;
    }
    case'GetTransactions':
    {
        if(!isset($_SESSION["LoginName"]))
        {
            echo json_encode(["ok" => false, "error" => "Please Log in to view transactions"]);
            exit();
        }
        $AccountNumber = $_GET["AccountNumber"] ?? '';

        if(empty($AccountNumber))
        {
            echo json_encode(["ok" => false, "error" => "Account Number is required to view transactions"]);
            exit();
        }

        try
        {
            $stmt = $conn->prepare("SELECT * FROM Transactions WHERE AccountNumber = ?");
            $stmt->execute([$AccountNumber]);
            $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(["ok" => true, "transactions" => $transactions]);
        }
        catch(PDOException $e)
        {
            echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
        }
        break;
    }
    case'EditTransaction':
    {
        if(!isset($_SESSION["LoginName"]))
        {
            echo json_encode(["ok" => false, "error" => "Please Log in to edit transaction"]);
            exit();
        }
        $TransactionNumber = $_POST["TransactionNumber"] ?? '';
        $Name = $_POST["Name"] ?? '';
        $Amount = $_POST["Amount"] ?? '';
        $Category = $_POST["Category"] ?? '';
        $Date = $_POST["Date"] ?? '';

        if(empty($TransactionNumber) || empty($Name) || empty($Amount) || empty($Category) || empty($Date))
        {
            echo json_encode(["ok" => false, "error" => "All fields are required"]);
            exit();
        }

        if($Amount <= 0)
        {
            echo json_encode(["ok" => false, "error" => "Transaction Amount cannot be zero or negative"]);
            exit();
        }

        try
        {
            $stmt = $conn->prepare("UPDATE Transactions SET Name = ?, Amount = ?, Category = ?, Date = ? WHERE TransactionNumber = ?");
            $stmt->execute([$Name, $Amount, $Category, $Date, $TransactionNumber]);
            echo json_encode(["ok" => true, "message" => "Transaction updated successfully"]);
        }
        catch(PDOException $e)
        {
            echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
        }
        break;
    }
    case'DeleteTransaction':
    {
        if(!isset($_SESSION["LoginName"]))
        {
            echo json_encode(["ok" => false, "error" => "Please Log in"]);
            exit();
        }
        $TransactionNumber = $_POST["TransactionNumber"] ?? '';
        
        if(empty($TransactionNumber))
        {
            echo json_encode(["ok" => false, "error" => "Transaction Number is required"]);
            exit();
        }

        try
        {
            $stmt = $conn->prepare("DELETE FROM Transactions WHERE TransactionNumber = ?");
            $stmt->execute([$TransactionNumber]);
            echo json_encode(["ok" => true, "message" => "Transaction deleted successfully"]);
        }
        catch(PDOException $e)
        {
            echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
        }
        break;

    }
    case'MonthlySpending':
    {
        if(!isset($_SESSION["LoginName"]))
        {
            echo json_encode(["ok" => false, "error" => "Please Log in"]);
            exit();
        }
        $AccountNumber = $_GET["AccountNumber"] ?? '';

        if(empty($AccountNumber))
        {
            echo json_encode(["ok" => false, "error" => "Account Number is required"]);
            exit();
        }

        try
        {
            $stmt = $conn->prepare(
            "SELECT 
                DATE_Format(Date, '%Y-%m') AS Month,
                Category,
                SUM(Amount) AS TotalSpent,
                COUNT(*)    AS TotalTransactions
            FROM Transactions
            WHERE AccountNumber = ?
            GROUP BY Month, Category
            ORDER BY Month DESC, Category
            ");
            $stmt->execute([$AccountNumber]);
            $monthly = $stmt->fetchall(PDO::FETCH_ASSOC);
            echo json_encode(["ok" => true, "monthly" => $monthly]);
        }
        catch(PDOException $e)
        {
              echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);   
        }
        break;

    }
}