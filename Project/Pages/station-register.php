<!DOCTYPE html>
<html>

<head>
    <title>Register Station</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>

<body>
    <h1>Register a Station</h1>

    <form method="post">
        <label>Serial Number
            <input type="text" name="serial_number" required minlength="7"></label><br>
        <label>Name
            <input type="text" name="name" required>
        </label><br>
        <label>Description
            <input type="text" name="description"></label><br>
        <button type="submit">Register</button>
    </form>
    <?php
    include("../Libraries/conndb.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $insertststmt = $conn->prepare("INSERT INTO stations (pk_serialNumber, name, description, fk_user_owns) VALUES (?, ?, ?, ?)");
        $insertststmt->bind_param("ssss", $_POST["serial_number"], $_POST["name"], $_POST["description"], $_SESSION["username"]);
        $insertststmt->execute();
    }
    ?>
    <p>(Could) Register via QR Code</p>

</body>

</html>