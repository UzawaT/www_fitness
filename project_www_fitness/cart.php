<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
</head>
<body>
    <?php
        session_start();

        include "includes/connect_gymstore_db.php";

        //check if the user is logged in
        if (!isset($_SESSION["user_id"]))
        {
            header("Location: index.php");
            exit();
        }

        include "includes/functions.php";

        //complete the purchase
        if($_SERVER["REQUEST_METHOD"] === "POST")
        {
            date_default_timezone_set("America/New_York");
            $cart = $_SESSION["cart"];
            $date = date("Y-m-d H:i:s");
            $total = $_POST["total"];
            if ($total > 0) 
            {
                $qry_purchase = "update cart 
                set checked_out = 1, date_checked_out = ?, total_sales = ?
                where cart_id = ?";
                $stmt = $conn->prepare($qry_purchase);
                $stmt->bind_param("sdi", $date, $total, $cart);
                $result_purchase = $stmt->execute();

                if($result_purchase)
                {
                    $_SESSION["cart"] = null;
                    header("Location: prod_list.php");
                }
            }

            else
            {
                echo "<p>No item in the cart.</p>";
                echo "<p><a href='cart.php'>Return</a></p>";
            }
            
        }
    ?>
    <?php include "includes/header.php"; ?>
    <div id="main">
        <h2>Cart</h2>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Image</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php
                if (isset($_SESSION["cart"]))
                {
                    getCartItems($conn, $_SESSION["cart"]);
                    $total = getTotalSales($conn, $_SESSION["cart"]);
                }
                else
                {
                    $total = 0;
                }
            ?>
        </table>
        <h3><?php echo "Total: $".number_format($total, 2); ?></h3>
        <form action="" method="post">
            <input type="hidden" name="total" value="<?php echo $total ?>">
            <button type="submit">Purchase</button>
        </form>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>