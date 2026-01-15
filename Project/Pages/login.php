<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>

<body>
    <body class="light-theme">
        <?php
        include("../Libraries/navbar.php");
        include("../Libraries/loginlib.php");
        include("../Libraries/conndb.php");
        createnavbar("Login");
        ?>
    </div>
    <h1>Login</h1>
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required><br>
        <label>Password</label>
        <input type="password" name="password" required><br>

        <button>Login</button>
    </form>
    <p>No account? <a href="register.php">Register here</a></p>
    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $user = trim($_POST['username']);
        $password = $_POST['password'];
        $stmt = $conn->prepare("SELECT * FROM users WHERE pk_username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $hashed_password_from_db = $row['password'];

            if (password_verify($password, $hashed_password_from_db)) {
                $_SESSION['id'] = $row['user_id'];
                $_SESSION['username'] = $user;
                header("Location: index.php");
                exit;
            } else {
                echo "Invalid username or password. Try again.";
            }
        } else {
            echo "Invalid username or password. Try again.";
        }
        $stmt->close();
        $conn->close();
    }
    ?>
</body>

</html>