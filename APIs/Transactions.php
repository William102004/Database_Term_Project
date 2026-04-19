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
            //check that transaction is possible with current account balance
            $stmt = $conn->prepare("SELECT Balance FROM Account WHERE AccountNumber = ?");
            $stmt->execute([$AccountNumber]);
            $account = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$account)
            {
                echo json_encode(["ok" => false, "error" => "Account not found"]);
                exit();
            }
            if($account["Balance"] < $Amount)
            {
                echo json_encode(["ok" => false, "error" => "Insufficient funds for transaction"]);
                exit();
            }
            //Insert transaction
            $stmt = $conn->prepare("INSERT INTO Transactions (AccountNumber, Name, Amount, Category, Date) VALUES (?,?,?,?,?)");
            $stmt->execute([$AccountNumber, $Name, $Amount, $Category, $Date]);
            $TransactionNumber = $conn->lastInsertId();

            //Update account balance
            $stmt = $conn->prepare("UPDATE Account SET Balance = Balance - ? WHERE AccountNumber = ?");
            $stmt->execute([$Amount, $AccountNumber]);
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
        $Category = $_GET["Category"] ?? '';
        $DateFrom = $_GET["DateFrom"] ?? '';
        $DateTo = $_GET["DateTo"] ?? '';
        $Name = $_GET["Name"] ?? '';
        $AmountMin = $_GET["AmountMin"] ?? '';
        $AmountMax = $_GET["AmountMax"] ?? '';
        if(empty($AccountNumber))
        {
            echo json_encode(["ok" => false, "error" => "Account Number is required to view transactions"]);
            exit();
        }
        
        if(!empty($Category) || !empty($DateFrom) || !empty($DateTo) || !empty($Name) || !empty($AmountMin) || !empty($AmountMax))
        {
            $query = "SELECT * FROM Transactions WHERE AccountNumber = ?";
            $params = [$AccountNumber];

            if(!empty($Category))
            {
                $query .= " AND Category = ?";
                $params[] = $Category;
            }
            if(!empty($DateFrom))
            {
                $query .= " AND Date >= ?";
                $params[] = $DateFrom;
            }
            if(!empty($DateTo))
            {
                $query .= " AND Date <= ?";
                $params[] = $DateTo;
            }
            if(!empty($Name))
            {
                $query .= " AND Name LIKE ?";
                $params[] = "%$Name%";
            }
            if(!empty($AmountMin))
            {
                $query .= " AND Amount >= ?";
                $params[] = $AmountMin;
            }
            if(!empty($AmountMax))
            {
                $query .= " AND Amount <= ?";
                $params[] = $AmountMax;
            }

            try
            {
                $stmt = $conn->prepare($query);
                $stmt->execute($params);
                $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(["ok" => true, "transactions" => $transactions]);
            }
            catch(PDOException $e)
            {
                echo json_encode(["ok" => false, "error" => "Error: " . $e->getMessage()]);
            }
        }    
        else
        {
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
        }
        break;
    }
    case'GetTransaction':
    {
        if(!isset($_SESSION["LoginName"]))
        {
            echo json_encode(["ok" => false, "error" => "Please Log in to view transaction"]);
            exit();
        }
        $TransactionNumber = $_GET["TransactionNumber"] ?? '';
        $AccountNumber = $_GET["AccountNumber"] ?? '';

        if(empty($TransactionNumber) || empty($AccountNumber))
        {
            echo json_encode(["ok" => false, "error" => "Transaction Number and Account Number are required to view transaction"]);
            exit();
        }

        try
        {
            $stmt = $conn->prepare("SELECT * FROM Transactions WHERE TransactionNumber = ? AND AccountNumber = ?");
            $stmt->execute([$TransactionNumber, $AccountNumber]);
            $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
            if($transaction)
            {
                echo json_encode(["ok" => true, "transaction" => $transaction]);
            }
            else
            {
                echo json_encode(["ok" => false, "error" => "Transaction not found"]);
            }
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
        $AccountNumber = $_POST["AccountNumber"] ?? '';
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
            //Check that transaction exists and get old amount and check that new transaction is possible
            $stmt = $conn->prepare("SELECT Amount FROM Transactions WHERE TransactionNumber = ? AND AccountNumber = ?");
            $stmt->execute([$TransactionNumber, $AccountNumber]);
            $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$transaction)
            {
                echo json_encode(["ok" => false, "error" => "Transaction not found"]);
                exit();
            }
            $oldAmount = $transaction["Amount"];
            //check that transaction is possible
            $stmt = $conn->prepare("SELECT Balance FROM Account WHERE AccountNumber = ?");
            $stmt->execute([$AccountNumber]);
            $account = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$account)
            {
                echo json_encode(["ok" => false, "error" => "Account not found"]);
                exit();
            }
            $balanceAfter = $account["Balance"] + $oldAmount;
            if($balanceAfter < $Amount)
            {
                echo json_encode(["ok" => false, "error" => "Insufficient funds for transaction"]);
                exit();
            }

            $stmt = $conn->prepare("UPDATE Transactions SET Name = ?, Amount = ?, Category = ?, Date = ? WHERE TransactionNumber = ? AND AccountNumber = ?");
            $stmt->execute([$Name, $Amount, $Category, $Date, $TransactionNumber, $AccountNumber]);
            echo json_encode(["ok" => true, "message" => "Transaction updated successfully"]);

            //Update account balance
            $stmt = $conn->prepare("UPDATE Account SET Balance = Balance + ? WHERE AccountNumber = ?");
            $stmt->execute([$oldAmount, $AccountNumber]);
    
            $stmt = $conn->prepare("UPDATE Account SET Balance = Balance - ? WHERE AccountNumber = ?");
            $stmt->execute([$Amount, $AccountNumber]);
            
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
        $AccountNumber = $_POST["AccountNumber"] ?? '';
        
        if(empty($TransactionNumber) || empty($AccountNumber))
        {
            echo json_encode(["ok" => false, "error" => "Transaction Number and Account Number are required"]);
            exit();
        }

        try
        {
            //Restore account balance before deleting transaction
            $stmt = $conn->prepare("SELECT Amount FROM Transactions WHERE TransactionNumber = ? AND AccountNumber = ?");
            $stmt->execute([$TransactionNumber, $AccountNumber]);
            $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
            $oldAmount = $transaction["Amount"];
            $stmt = $conn->prepare("UPDATE Account SET Balance = Balance + ? WHERE AccountNumber = ?");
            $stmt->execute([$oldAmount, $AccountNumber]);

            //Delete transaction
            $stmt = $conn->prepare("DELETE FROM Transactions WHERE TransactionNumber = ? AND AccountNumber = ?");
            $stmt->execute([$TransactionNumber, $AccountNumber]);
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
                DATE_Format(Date, '%M') AS Month,
                Category,
                SUM(Amount) AS TotalSpent,
                COUNT(*)    AS TotalTransactions
            FROM Transactions
            WHERE AccountNumber = ? AND Date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
            GROUP BY Month, Category
            ORDER BY Month DESC, TotalSpent DESC, Category, TotalTransactions DESC
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
?>