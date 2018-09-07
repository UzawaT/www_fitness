<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Purchase History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
</head>
<body>
    <?php
        session_start();

        //check if the user is logged in
        if (!isset($_SESSION["user_id"]))
        {
            header("Location: index.php");
            exit();
        }

        include "includes/connect_gymstore_db.php";
        include "includes/functions.php";

    ?>
    <?php include "includes/header.php"; ?>
    <div id="main">
        <h2>Purchase History</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php getPurchaseHistory($conn, $_SESSION["user_id"]); ?>
        </table>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>