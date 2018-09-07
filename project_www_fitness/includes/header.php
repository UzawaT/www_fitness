<div id="header">
    <h1>World Wide Workout Fitness Online Store</h1>
    <nav>
    <ul>
        <li class="navItem"><a href="prod_list.php">Browse Products</a></li>
        <li class="navItem"><a href="edit_user.php?id=<?php echo $_SESSION["user_id"];?>">Edit User Information</a></li>
        <li class="navItem"><a href="edit_profile_image.php?id=<?php echo $_SESSION["user_id"];?>">Change Profile Picture</a></li>
        <li class="navItem"><a href="change_password.php?id=<?php echo $_SESSION["user_id"];?>">Change Password</a></li>
        <li class="navItem"><a href="cart.php">Shopping Cart</a></li>
        <li class="navItem"><a href="purchase_history.php">Purchase History</a></li>
        <li class="navItem"><a href="logout.php">Log out</a></li>
    </ul>
    </nav>
</div>
