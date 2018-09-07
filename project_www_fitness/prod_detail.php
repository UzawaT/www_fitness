<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Product Detail</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
</head>
<body>
    <?php
        //check if product id has been supplied and the user is logged in
        session_start();
        
        if (!isset($_GET["id"])||!isset($_SESSION["user_id"]))
        {
            header("Location: index.php");
            exit();
        }

        $id = $_GET["id"];

        //connect to the database
        include "includes/connect_gymstore_db.php";

        //retrieve product information
        $query = "select prod_name, price, image_file, description
            from product join prod_price on product.price_id = prod_price.price_id
            where prod_id = '$id'";

        $result = $conn->query($query);
        
        if ($result)
        {
            $prodInfo = $result->fetch_object();
        }
        else
        {
            echo "user not found.";
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST")
        {
            $user = $_POST["user_id"];
            $prod = $_POST["prod_id"];
            
            if (empty($_POST["quantity"]))
            {
                echo "Quantity must be entered.";
            }
            else
            {
                $qty = $_POST["quantity"];
            }

            if (!isset($_SESSION["cart"]))
            {
                //add to cart table
                $qry_cart = "insert into cart (user_id) values('$user')";

                $result_cart = $conn->query($qry_cart);

                //add into cart_item table
                $cart = $conn->insert_id;

                $qry_item = "insert into cart_item(prod_id, cart_id, quantity)
                    values('$prod', '$cart', '$qty')";
                
                $result_item = $conn->query($qry_item);

                if ($result_item)
                {
                    $_SESSION["cart"] = $cart;
                    header("Location: cart.php");
                }
                else
                {
                    echo $conn->error;
                    echo "<a href='prod_list.php'>Return</a>";
                }
            }
            else
            {
                $cart = $_SESSION["cart"];
                
                $qry_item = "insert into cart_item(prod_id, cart_id, quantity)
                    values('$prod', '$cart', '$qty')";
                
                $result_item = $conn->query($qry_item);

                if ($result_item)
                {
                    header("Location: cart.php");
                }
                else
                {
                    echo $conn->error;
                    echo "<a href='prod_list.php'>Return</a>";
                }
            }
        }
    ?>
    <?php include "includes/header.php"; ?>
    <div id="main">
        <h2>Product Detail</h2>
        <img src="images/<?php echo $prodInfo->image_file; ?>" alt="">
        <br>
        <p><b>Product Name:</b> <?php echo $prodInfo->prod_name;?></p>
        <p><b>Price:</b> $<?php echo number_format($prodInfo->price, 2);?></p>
        <b>Description: </b>
        <p><?php echo $prodInfo->description;?></p>
        <form action="" method="post">
            <input type="hidden" name="prod_id" value="<?php echo $id; ?>">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION["user_id"];?>">
            <b>Quantity:</b><br>
            <input type="number" name="quantity" id="quantity" min="1" required>
            <br>
            <button type="submit">Add to Cart</button>
        </form>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>