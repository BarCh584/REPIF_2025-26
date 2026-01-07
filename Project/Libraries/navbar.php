<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

function createnavbar($isselected)
{
    $websites = [
        "Dashboard"   => "index.php",
        "Stations"    => "stations.php",
        "Collections" => "collections.php",
        "Friends"     => "friends.php",
        "Account"     => "account.php",
    ];

    if (isset($_SESSION["id"])) {
        $websites["Logout"] = "logout.php";
    } else {
        $websites["Login"] = "login.php";
    }

    foreach ($websites as $name => $url) {
        $class = ($isselected === $name)
            ? 'nav-child selected'
            : 'nav-child';

        echo '<a href="' . htmlspecialchars($url) . '" class="' . $class . '">'
            . htmlspecialchars($name) .
            '</a>';
    }
}
?>
