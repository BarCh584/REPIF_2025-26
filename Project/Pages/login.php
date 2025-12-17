<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>

<body>
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
    include("../Libraries/loginlib.php");
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $user = trim($_POST['username']);
        $password = $_POST['password'];
        $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $hashed_password_from_db = $row['password'];

            if (password_verify($password, $hashed_password_from_db)) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $user;
                print($row['id']);
                print(var_dump($row));
                //header("Location: index.php");
                //exit;
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