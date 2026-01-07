<!DOCTYPE html>
<html>

<head>
    <title>Friends</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>

<body>
    <div class="navbar">
        <?php
        include("../Libraries/navbar.php");
        include("../Libraries/loginlib.php");
        include("../Libraries/conndb.php");
createnavbar("Friends");
        ?>
    </div>
    <h1>Friends</h1>

    <h2>Friend Requests (Should)</h2>
    <?php

    if ($_SESSION["id"] != null) {
        $stmt = $conn->prepare("SELECT * FROM friend_requests WHERE pkfk_user_receiver = ?");
        $stmt->bind_param("i", $_SESSION["id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { ?>
                // Display each friend request
                <ul>
                    <li><?= htmlspecialchars($row['requester_id']) ?> <form method="POST"><button name="accept" value="<?= $row['id'] ?>">Accept</button> <button name="decline" value="<?= $row['id'] ?>">Decline</button></form>
                    </li>
                </ul>
            <?php
            }
        } else { ?>
            <p>No friend requests.</p>
    <?php
        }
        $stmt->close();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['decline'])) {
        $denyrequeststmt = $conn->prepare("DELETE FROM friend_requests WHERE id = ?");
        $denyrequeststmt->bind_param("i", $_POST['decline']);
        $denyrequeststmt->execute();
        $denyrequeststmt->close();
    } else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept'])) {
        $acceptstmt = $conn->prepare("INSERT INTO user_friends (user_id, friend_id) VALUES (?, ?), (?, ?)");
        // Get requester_id from friend_requests table
        $getrequesterstmt = $conn->prepare("SELECT requester_id FROM friend_requests WHERE id = ?");
        $getrequesterstmt->bind_param("i", $_POST['accept']);
        $getrequesterstmt->execute();
        $result = $getrequesterstmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $requester_id = $row['requester_id'];
            $acceptstmt->bind_param("iiii", $_SESSION['id'], $requester_id, $requester_id, $_SESSION['id']);
            $acceptstmt->execute();
        }
        // Delete the friend request
        $deleterequeststmt = $conn->prepare("DELETE FROM friend_requests WHERE id = ?");
        $deleterequeststmt->bind_param("i", $_POST['accept']);
        $deleterequeststmt->execute();

        $acceptstmt->close();
        $getrequesterstmt->close();
        $deleterequeststmt->close();
    }
    ?>

    <h2>Your Friends</h2>
    <ul>
        <li>Anna <button>End Friendship</button></li>
    </ul>
    <?php
    $endfstmt = $conn->prepare("DELETE FROM isfriend WHERE pkfk_user_user = ? AND pkfk_user_friend = ?");
    ?>
    <!-- Should: ending friendship unshares collections -->

    <h2>Add Friend</h2>
    <form method="POST">
        <!-- Should: request system, no direct add -->
        <label>Username</label>
        <input type="text" name="friend_username" required>
        <button type="submit">Send Friend Request</button>
    </form>
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["friend_username"])) {
        $fliststmt = $conn->prepare("SELECT user_id, username FROM users WHERE username LIKE CONCAT(?, '%')");
        $fliststmt->bind_param("s", $_POST["friend_username"]);
        $fliststmt->execute();
        $result = $fliststmt->get_result();
        if($result->num_rows == 0) {
            die ("<p>No users found.</p>");
        }
        while ($row = $result->fetch_assoc()) { ?>
            <form method="POST">
                <p>Found user: <?= htmlspecialchars($row['username']) ?></p>
                <button type="submit" name="send_request" value="<?= $row['user_id'] ?>">Send Friend Request</button>
            </form>
    <?php
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["send_request"])) {
        $insertfrstmt = $conn->prepare("INSERT INTO friend_requests (pkfk_user_sender, pkfk_user_receiver) VALUES (?, ?)");
        $insertfrstmt->bind_param("ii", $_SESSION["id"], $_POST["send_request"]);
        $insertfrstmt->execute();
        $insertfrstmt->close();
    }
    ?>
    <!-- Could: user chat -->
    <p>(Could) Chat with friends</p>

</body>

</html>