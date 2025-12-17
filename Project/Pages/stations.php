<!DOCTYPE html>
<html>

<head>
    <title>My Stations</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>

<body>
    <div class="navbar">
        <?php
        include("../Libraries/navbar.php");
        createnavbarelement("Dashboard", "index.php", false);
        createnavbarelement("My Stations", "stations.php", true);
        createnavbarelement("Collections", "collections.php", false);
        createnavbarelement("Friends", "friends.php", false);
        createnavbarelement("Account", "account.php", false);
        createnavbarelement("Logout", "logout.php", false);
        ?>
    </div>
    <h1>Your Stations</h1>

    <ul>
        <li>
            <b>Station A</b> – description<br>
            <button>Edit name/description</button>
        </li>
        <li>
            <b>Station B</b> – description<br>
            <button>Edit name/description</button>
        </li>
    </ul>

    <a href="station-register.html">Register new station by Serial Number</a>

</body>

</html>