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

if (!isset($_SESSION['username'])) {
    die("You must be logged in.");
}

/* -------------------------------------------------
   Helper: normalize usernames (alphabetical order)
--------------------------------------------------*/
function normalizeUsers($a, $b) {
    return ($a < $b) ? [$a, $b] : [$b, $a];
}

/* -------------------------------------------------
   HANDLE POST ACTIONS
--------------------------------------------------*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /* Send friend request */
    if (isset($_POST['send_request'])) {
        [$u1, $u2] = normalizeUsers($_SESSION['username'], $_POST['send_request']);

        $stmt = $conn->prepare("
            INSERT IGNORE INTO isfriend (user_one, user_two, status, action_user)
            VALUES (?, ?, 'pending', ?)
        ");
        $stmt->bind_param("sss", $u1, $u2, $_SESSION['username']);
        $stmt->execute();
        $stmt->close();
    }

    /* Accept request */
    if (isset($_POST['accept'])) {
        $stmt = $conn->prepare("UPDATE isfriend SET status = 'accepted' WHERE user_one = ? AND user_two = ?");
        $stmt->bind_param("ss", $_POST['u1'], $_POST['u2']);
        $stmt->execute();
        $stmt->close();
    }

    /* Decline request */
    if (isset($_POST['decline'])) {
        $stmt = $conn->prepare("DELETE FROM isfriend WHERE user_one = ? AND user_two = ?");
        $stmt->bind_param("ss", $_POST['u1'], $_POST['u2']);
        $stmt->execute();
        $stmt->close();
    }

    /* Unfriend */
    if (isset($_POST['unfriend'])) {
        $stmt = $conn->prepare("DELETE FROM isfriend WHERE user_one = ? AND user_two = ?");
        $stmt->bind_param("ss", $_POST['u1'], $_POST['u2']);
        $stmt->execute();
        $stmt->close();
    }
}
?>
</div>

<h1>Friends</h1>
<h2>Friend Requests</h2>

<?php
$stmt = $conn->prepare("SELECT * FROM isfriend WHERE status = 'pending' AND action_user != ? AND (user_one = ? OR user_two = ?)");
$stmt->bind_param("sss", $_SESSION['username'], $_SESSION['username'], $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>No friend requests.</p>";
}

while ($row = $result->fetch_assoc()) {
    $sender = ($row['user_one'] === $_SESSION['username']) ? $row['user_two'] : $row['user_one'];
?>
    <p>
        <?= htmlspecialchars($sender) ?>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="u1" value="<?= $row['user_one'] ?>">
            <input type="hidden" name="u2" value="<?= $row['user_two'] ?>">
            <button name="accept">Accept</button>
            <button name="decline">Decline</button>
        </form>
    </p>
<?php
}
$stmt->close();
?>
<h2>Your Friends</h2>
<?php
$stmt = $conn->prepare("SELECT * FROM isfriend WHERE status = 'accepted' AND (user_one = ? OR user_two = ?)");
$stmt->bind_param("ss", $_SESSION['username'], $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>You have no friends yet.</p>";
}

while ($row = $result->fetch_assoc()) {
    $friend = ($row['user_one'] === $_SESSION['username'])
        ? $row['user_two']
        : $row['user_one'];
?>
    <p>
        <?= htmlspecialchars($friend) ?>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="u1" value="<?= $row['user_one'] ?>">
            <input type="hidden" name="u2" value="<?= $row['user_two'] ?>">
            <button name="unfriend">Unfriend</button>
        </form>
    </p>
<?php
}
$stmt->close();
?>
<h2>Add Friend</h2>
<form method="POST">
    <label>Username</label>
    <input type="text" name="search_user" required>
    <button type="submit">Search</button>
</form>
<?php
if (isset($_POST['search_user'])) {
    $stmt = $conn->prepare("
        SELECT pk_username FROM users
        WHERE pk_username = ?
    ");
    $stmt->bind_param("s", $_POST['search_user']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['pk_username'] !== $_SESSION['username']) {
?>
            <form method="POST">
                <p>Found user: <?= htmlspecialchars($row['pk_username']) ?></p>
                <button name="send_request" value="<?= $row['pk_username'] ?>">
                    Send Friend Request
                </button>
            </form>
<?php
        }
    } else {
        echo "<p>User not found.</p>";
    }
    $stmt->close();
}
?>

</body>
</html>
