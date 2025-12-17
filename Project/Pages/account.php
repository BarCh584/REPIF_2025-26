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
        createnavbarelement("Dashboard", "index.php", false);
        createnavbarelement("My Stations", "stations.php", false);
        createnavbarelement("Collections", "collections.php", false);
        createnavbarelement("Friends", "friends.php", false);
        createnavbarelement("Account", "account.php", true);
        createnavbarelement("Logout", "logout.php", false);
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
