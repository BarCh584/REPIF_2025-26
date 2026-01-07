<!DOCTYPE html>
<html>
<head>
    <title>My Account</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>
<body>
        <div class="navbar">
        <?php
        include("../Libraries/navbar.php");
        createnavbar("Account");
        ?>
    </div>
<h1>Edit Your Account</h1>

<form>
    <label>Username</label><input type="text">
    <label>Password</label><input type="password">
    <label>Email</label><input type="email">
    <label>First Name</label><input type="text">
    <label>Last Name</label><input type="text">
    <button>Save</button>
</form>

</body>
</html>
