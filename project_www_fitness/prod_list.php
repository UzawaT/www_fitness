<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Product List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
</head>
<body>
    <?php
        //connect to the database
        include "includes/connect_gymstore_db.php";
        include "includes/functions.php";

        //start the session
        session_start();
        
        //check if the user has logged in
        if (!isset($_SESSION["user_id"]))
        {
            header("Location: index.php");
            exit();
        }
    ?>
    <?php include "includes/header.php"; ?>
    <div id="main">
        <div id="user">
            <h2>Welcome, <?php echo $_SESSION["first_name"]; ?>!</h2>
            <img src="includes/image_source.php?id=<?php echo $_SESSION["user_id"] ?>" style="width:200px; height:auto;" alt="">
        </div>
        <div id="prodList">
            <h3>To start shopping, select from products below.</h3>
            <table>
            <tr>
                <th>Product Name</th>
                <th>Image</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            <?php getProductList($conn);?> 
            </table>
        </div>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>