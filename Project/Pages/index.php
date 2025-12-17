<!DOCTYPE html>
<html>

<head>
    <title>Welcome</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>

<body>
    <div class="navbar">
        <?php
        include("../Libraries/navbar.php");
        include("../Libraries/loginlib.php");
        print($_SESSION["id"]);
        createnavbarelement("Dashboard", "index.php", true);
        createnavbarelement("My Stations", "stations.php", false);
        createnavbarelement("Collections", "collections.php", false);
        createnavbarelement("Friends", "friends.php", false);
        createnavbarelement("Account", "account.php", false);
        createnavbarelement("Logout", "logout.php", false);
        ?>
    </div>
    <h1>Welcome!</h1>

    <h2>Dashboard (Should)</h2>
    <p>Here current measurement data from your stations will be displayed.</p>

    <!-- Could: charts / diagrams -->
    <div class="chart-placeholder">
        <p>(Could) Diagram displaying temperature, humidity, etc.</p>
    </div>

</body>

</html>