<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>

<body>
    <h1>Register</h1>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required><br>

        <label>Password</label>
        <input type="password" name="password" required><br>
        <label>Confirm Password</label>
        <input type="password" name="confirmpassword" required><br>

        <label>Email</label>
        <input type="email" name="email" required><br>

        <label>Full Name</label>
        <input type="text" name="fullname" required><br>

        <button>Create Account</button>
    </form>
    <?php
    include("../Libraries/loginlib.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        form($_POST["username"], $_POST["fullname"], $_POST["email"], $_POST["password"], $_POST["confirmpassword"]);
    }
    function form($username, $fullname, $email, $password, $confirmpassword)
    {
        global $conn;
        if (!isset($username, $fullname, $email, $password, $confirmpassword)) {
            die("Please fill all in all of the fields!");
        }
        if ($password != $confirmpassword) {
            die("<p>Passwords do not match!</p>");
        }
        if (userAlreadyExists($username)) {
            die("<p>Username already exists!</p>");
        }
        if(emailAlreadyExists($email)) {
            die("<p>Email already registered!</p>");
        }
        $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
        $registerstmt = $conn->prepare("INSERT INTO users (username, password, email, full_name) VALUES (?, ?, ?, ?)");
        $registerstmt->bind_param("ssss", $username, $hashedpassword, $email, $fullname);
        $registerstmt->execute();
        echo "<p>You have been registered sucessfully!</p>";
        header("Location: login.php");
    }

    ?>
</body>

</html>