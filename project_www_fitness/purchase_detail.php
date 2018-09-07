<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Purchase Detail</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
</head>
<body>
    <?php
        //check if the user has logged in
        session_start();

        if (!isset($_GET["id"])||!isset($_SESSION["user_id"]))
        {
            header("Location: index.php");
            exit();
        }

        include "includes/connect_gymstore_db.php";
        include "includes/functions.php";
    ?>
    <?php include "includes/header.php"; ?>
    <div id="main">
        <h2>Purchase Detail</h2>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Image</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php
                if (isset($_GET["id"]))
                {
                    getCartItems($conn, $_GET["id"]);
                }
            ?>
        </table>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>