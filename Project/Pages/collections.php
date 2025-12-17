<!DOCTYPE html>
<html>
<head>
    <title>Collections</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>
<body>
    <div class="navbar">
        <?php
        include("../Libraries/navbar.php");
        createnavbarelement("Dashboard", "index.php", false);
        createnavbarelement("My Stations", "stations.php", false);
        createnavbarelement("Collections", "collections.php", true);
        createnavbarelement("Friends", "friends.php", false);
        createnavbarelement("Account", "account.php", false);
        createnavbarelement("Logout", "logout.php", false);
        ?>
    </div>
<h1>Your Collections</h1>

<button>Create Collection</button>

<ul>
    <li>
        <b>Collection 1</b> (Station A – from X to Y)
        <button>Rename</button>
        <button>Delete</button>
        <button>Share</button>
    </li>
</ul>

</body>
</html>
