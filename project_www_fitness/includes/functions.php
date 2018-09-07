<?php
function formatInput($inputText)
{
    $inputText = trim($inputText);
    $inputText = stripslashes($inputText);
    $inputText = htmlspecialchars($inputText);

    return $inputText;
}

function getProductList($db)
{
    $query = "select prod_id, prod_name, image_file, price
        from product inner join prod_price on product.price_id = prod_price.price_id";

    $result = $db->query($query);

    if ($result->num_rows > 0)
    {
        for ($i=0; $i < $result->num_rows; $i++)
        {
            $product = $result->fetch_assoc();

            echo "<tr>";
            echo "<td>".$product["prod_name"]."</td>";
            echo "<td><img src='images/".$product["image_file"]."' style='width:100px; height:auto' alt=''></td>";
            echo "<td>$".number_format($product["price"], 2)."</td>";
            echo "<td><a href='prod_detail.php?id=".$product["prod_id"]."'>View Detail</a>";
            echo "</tr>";
        }
    }
}

function getCartItems($db, $id)
{
    $qry = "select prod_name, price, image_file, quantity, item_id, cart.cart_id, checked_out
        from cart_item inner join cart on cart.cart_id = cart_item.cart_id
        inner join product on product.prod_id = cart_item.prod_id 
        inner join prod_price on prod_price.price_id = product.price_id
        where cart.cart_id = $id";

    $result = $db->query($qry);

    if ($result->num_rows > 0)
    {
        for ($i=0; $i < $result->num_rows; $i++)
        { 
            $item = $result->fetch_assoc();

            echo "<tr>";
            echo "<td>".$item["prod_name"]."</td>";
            echo "<td><img src='images/".$item["image_file"]."' style='width:100px; height:auto' alt=''></td>";
            echo "<td>$".number_format($item["price"], 2)."</td>";
            echo "<td>".$item["quantity"]."</td>";
            echo "<td>$".number_format($item["price"] * $item["quantity"], 2)."</td>";

            if (!$item["checked_out"])
            {
                echo "<td><a href='delete_item.php?id=".$item["item_id"]."' onclick = 'return confirm("."\"Are you sure?\"".")'>Remove</a></td>";
            }
            
            echo "</tr>";
        }
    }
}

function getTotalSales($db, $id)
{
    $total = 0;

    $qry = "select price, quantity
        from cart_item inner join cart on cart.cart_id = cart_item.cart_id
        inner join product on product.prod_id = cart_item.prod_id 
        inner join prod_price on prod_price.price_id = product.price_id
        where cart.cart_id = $id";

    $result = $db->query($qry);

    if ($result->num_rows > 0)
    {
        for ($i=0; $i < $result->num_rows; $i++)
        { 
            $item = $result->fetch_assoc();
            $total += $item["price"] * $item["quantity"];
        }
    }

    return $total;
}

function getPurchaseHistory($db, $id)
{
    $qry_history = "select date_checked_out, total_sales, cart_id from cart 
        where user_id = '$id' and checked_out = 1";

    $result = $db->query($qry_history);

    if ($result->num_rows > 0)
    {

        for ($i=0; $i < $result->num_rows; $i++)
        { 
            $purchase = $result->fetch_assoc();

            echo "<tr>";
            echo "<td>".$purchase["date_checked_out"]."</td>";
            echo "<td>$".number_format($purchase["total_sales"], 2)."</td>";
            echo "<td><a href='purchase_detail.php?id=".$purchase["cart_id"]."'>View Detail</a>";
            echo "</tr>";
        }
    }
}
?>