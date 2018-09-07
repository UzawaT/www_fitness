<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
</head>
<body>
    <?php
        //connect to database
        include "includes/connect_gymstore_db.php";

        //include external function file
        include "includes/functions.php";

        if ($_SERVER["REQUEST_METHOD"] === "POST")
        {
            //retrieve values from form
            $fname = formatInput($_POST["fname"]);
            $mid = formatInput($_POST["mid"]);
            $lname = formatInput($_POST["lname"]);
            $email = formatInput($_POST["email"]);
            $phone = formatInput($_POST["phone"]);
            $user = formatInput($_POST["user"]);
            $pass = formatInput($_POST["password"]);
            $confirm = formatInput($_POST["confirm"]);

            //check to see if the required fields are filled out
            if (empty($fname)||empty($lname)||empty($email)||empty($user)||empty($pass)||empty($confirm))
            {
                echo "There is required field not entered.";
                echo "<br>";
                echo "<a href='register.php'>Return</a>";
                return;
            }

            //check if the password match
            if ($pass != $confirm)
            {
                echo "Password doesn't match.";
                echo "<br>";
                echo "<a href='register.php'>Return</a>";
                return;
            }

            //encrypt the password
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $hash = $conn->real_escape_string($hash);

            //add into database
            $query_user = "insert into gym_user(first_name, middle_initial, last_name, email, phone, user_name, user_pass) 
                values(?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($query_user);
            $stmt->bind_param("sssssss", $fname, $mid, $lname, $email, $phone, $user, $hash);
            $result = $stmt->execute();

            if (isset($_FILES["image"]["tmp_name"]))
            {
                $fileinfo = finfo_open(FILEINFO_MIME_TYPE);

                if (finfo_file($fileinfo, $_FILES["image"]["tmp_name"]) == "image/jpeg")
                {
                    $id = $conn->insert_id;

                    $image_data = $conn->escape_string(file_get_contents($_FILES["image"]["tmp_name"]));
                    $image_prop = getimagesize($_FILES["image"]["tmp_name"]);
                    $image_type = $image_prop["mime"];
                    $image_name = $_FILES["image"]["name"];

                    //add into database
                    $query_img = "insert into user_image(filename, image_type, image_data, user_id)
                        values(?, ?, ?, ?)";
                    $stmt = $conn->prepare($query_img);
                    $stmt->bind_param("ssbi", $image_name, $image_type, $image_data, $id);
                    $result_img = $stmt->execute();

                    if(!$result_img)
                    {
                        echo "Error with image upload";
                        echo "<br>";
                        echo "<a href='register.php'>Return</a>";
                    }
                }
                else
                {
                    echo "Invalid file type.";
                }
            }

            if($result)
            {
                header("Location: index.php");
            }
            else
            {
                echo "Error with user sign up";
                echo "<br>";
                echo "<a href='register.php'>Return</a>";
            }
        }
    ?>
    <div id="header">
        <h1>World Wide Workout Fitness Online Store</h1>
    </div>
    <div id="main">
        <h1>Register</h1>
        <p><h3><a href="index.php">Log in</a></h3></p>
        <form method="post" enctype="multipart/form-data">
            <label for="fname">First Name:</label>
            <input type="text" name="fname" id="fname" required>
            <br>
            <label for="mid">Middle Initial:</label>
            <input type="text" name="mid" id="mid" maxlength="1">
            <br>
            <label for="lname">Last Name:</label>
            <input type="text" name="lname" id="lname" required>
            <br>
            <label for="email">Email Address:</label>
            <input type="email" name="email" id="email" required>
            <br>
            <label for="phone">Phone No:</label>
            <input type="tel" name="phone" id="phone" placeholder="123-456-7890" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
            <br>
            <label for="user">User Name:</label>
            <input type="text" name="user" id="user" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <br>
            <label for="confirm">Confirm:</label>
            <input type="password" name="confirm" id="confirm" required>
            <br>
            <label for="image">Profile Image:</label>
            <input type="file" name="image" id="image">
            <br>
            <button type="submit">Register</button>
        </form>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>