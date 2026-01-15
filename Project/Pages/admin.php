<?php
session_start();
require "../Libraries/loginlib.php";
require("../Libraries/navbar.php");
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}
$user = null;
$error = null;
if (isset($_GET['clear'])) {
    header("Location: admin.php");
    exit;
}
if (!empty($_GET['username'])) {
    $stmt = $conn->prepare("
        SELECT pk_username, firstName, lastName, email, role
        FROM users
        WHERE pk_username = ?
    ");
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
    } else {
        $error = "User not found.";
    }
}
if (isset($_POST['action'])) {

    switch ($_POST['action']) {

        case 'toggle_admin':
            if ($_POST['username'] !== $_SESSION['username']) {
                $stmt = $conn->prepare("
                    UPDATE users
                    SET role = IF(role='Admin','User','Admin')
                    WHERE pk_username = ?
                ");
                $stmt->bind_param("s", $_POST['username']);
                $stmt->execute();
            }
            break;

        case 'delete_user':
            if ($_POST['username'] !== $_SESSION['username']) {

                $conn->begin_transaction();

                // delete measurements
                $stmt = $conn->prepare("
                    DELETE m FROM measurements m
                    JOIN stations s ON s.pk_serialNumber = m.fk_station_records
                    WHERE s.fk_user_owns = ?
                ");
                $stmt->bind_param("s", $_POST['username']);
                $stmt->execute();

                // delete stations
                $stmt = $conn->prepare("
                    DELETE FROM stations WHERE fk_user_owns = ?
                ");
                $stmt->bind_param("s", $_POST['username']);
                $stmt->execute();

                // delete user
                $stmt = $conn->prepare("
                    DELETE FROM users WHERE pk_username = ?
                ");
                $stmt->bind_param("s", $_POST['username']);
                $stmt->execute();

                $conn->commit();
            }
            break;

        case 'delete_station':
            $stmt = $conn->prepare("
                DELETE FROM stations WHERE pk_serialNumber = ?
            ");
            $stmt->bind_param("s", $_POST['serial']);
            $stmt->execute();
            break;

        case 'delete_measurement':
            $stmt = $conn->prepare("
                DELETE FROM measurements WHERE pk_measurement = ?
            ");
            $stmt->bind_param("i", $_POST['mid']);
            $stmt->execute();
            break;

        case 'delete_collection':
            $stmt = $conn->prepare("
                DELETE FROM collections WHERE pk_collection = ?
            ");
            $stmt->bind_param("i", $_POST['cid']);
            $stmt->execute();
            break;
    }

    $username = $_POST['username'] ?? $_GET['username'] ?? '';
    header("Location: admin.php?username=" . urlencode($username));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['createaccount'])) {
    form($_POST["username"], $_POST["firstName"], $_POST["lastName"], $_POST["email"], $_POST["password"], $_POST["confirmPassword"]);
}
function form($username, $firstname, $lastname, $email, $password, $confirmpassword)
{
    global $conn;
    if (!isset($username, $firstname, $lastname, $email, $password, $confirmpassword)) {
        die("Please fill all in all of the fields!");
    }
    if ($password != $confirmpassword) {
        die("<p>Passwords do not match!</p>");
    }
    if (userAlreadyExists($username)) {
        die("<p>Username already exists!</p>");
    }
    if (emailAlreadyExists($email)) {
        die("<p>Email already registered!</p>");
    }
    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
    $registerstmt = $conn->prepare("INSERT INTO users (pk_username, password, email, firstName, lastName) VALUES (?, ?, ?, ?, ?)");
    $registerstmt->bind_param("sssss", $username, $hashedpassword, $email, $firstname, $lastname);
    $registerstmt->execute();
    echo "<p>You have been registered sucessfully!</p>";
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>
<script src="../Libraries/JS/jquery-3.7.1.min.js"></script>
<script>
    function updateUserField(input) {
        const field = input.dataset.field;
        const value = input.value;
        const username = "<?= htmlspecialchars($user['pk_username'] ?? '') ?>";

        $.post(
                "../Libraries/updateuserinfo.php", {
                    username: username,
                    field: field,
                    value: value
                }
            ).done(() => {
                alert("Update successful");
            })
            .fail(() => {
                alert("Update failed");
            });
    }

    function updateStationField(input) {
        const field = input.dataset.field;
        const value = input.value;
        const serial = input.dataset.serial;

        $.post(
            "../Libraries/updatestationinfo.php", {
                serial: serial,
                field: field,
                value: value
            }
        ).done(() => {
            alert("Station updated successfully");
        }).fail((xhr) => {
            alert("Update failed: " + xhr.responseText);
        });
    }
</script>

<body class="light-theme">

    <div class="navbar">
        <?php createnavbar("Admin") ?>
    </div>
    <h1>Admin Panel</h1>
    <hr>
    <form method="GET">
        <input type="text" name="username" placeholder="Enter exact username" value="<?= htmlspecialchars($_GET['username'] ?? '') ?>" required>
        <button type="submit">Load User</button>
        <button type="submit" name="clear" value="1">Clear</button>
    </form>
    <?php if ($error) { ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php } ?>
    <?php if ($user) { ?>
        <hr>
        <h2>User Information</h2>
        <table border="1">
            <tr>
                <th>Username</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <tr>
                <td>
                    <input type="text" value="<?= htmlspecialchars($user['pk_username']) ?>" disabled>
                </td>
                <td>
                    <input type="text" value="<?= htmlspecialchars($user['firstName']) ?>" data-field="firstName" onblur="updateUserField(this)">
                    <input type="text" value="<?= htmlspecialchars($user['lastName']) ?>" data-field="lastName" onblur="updateUserField(this)">
                </td>
                <td>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" data-field="email" onblur="updateUserField(this)">
                </td>

                <td><?= htmlspecialchars($user['role']) ?></td>
                <td>
                    <?php if ($user['pk_username'] !== $_SESSION['username']) { ?>
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="username" value="<?= $user['pk_username'] ?>">
                            <button name="action" value="toggle_admin">Toggle Admin</button>
                        </form>
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="username" value="<?= $user['pk_username'] ?>">
                            <button name="action" value="delete_user" onclick="return confirm('Delete this user and ALL their data?')">
                                Delete User
                            </button>
                        </form>
                    <?php } else { ?>
                        (You)
                    <?php } ?>
                </td>
            </tr>
        </table>
        <h2>Stations</h2>
        <table border="1">
            <tr>
                <th>Serial</th>
                <th>Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            <?php
            $stmt = $conn->prepare("SELECT pk_serialNumber, name, description FROM stations WHERE fk_user_owns = ?");
            $stmt->bind_param("s", $user['pk_username']);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($s = $res->fetch_assoc()) {
            ?>
                <tr>
                    <td>
                        <input type="text" value="<?= htmlspecialchars($s['pk_serialNumber']) ?>" disabled>
                    </td>
                    <td>
                        <input type="text" value="<?= htmlspecialchars($s['name']) ?>" data-field="name" data-serial="<?= htmlspecialchars($s['pk_serialNumber']) ?>" onblur="updateStationField(this)">
                    </td>
                    <td>
                        <input type="text" value="<?= htmlspecialchars($s['description']) ?>" data-field="description" data-serial="<?= htmlspecialchars($s['pk_serialNumber']) ?>" onblur="updateStationField(this)">
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="serial" value="<?= $s['pk_serialNumber'] ?>">
                            <input type="hidden" name="username" value="<?= $user['pk_username'] ?>">
                            <button name="action" value="delete_station">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <h2>Measurements</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Temp</th>
                <th>Humidity</th>
                <th>Pressure</th>
                <th>Light</th>
                <th>Gas</th>
                <th>Timestamp</th>
                <th>Action</th>
            </tr>

            <?php
            $stmt = $conn->prepare("SELECT m.* FROM measurements m JOIN stations s ON s.pk_serialNumber = m.fk_station_records WHERE s.fk_user_owns = ? ORDER BY m.timestamp DESC");
            $stmt->bind_param("s", $user['pk_username']);
            $stmt->execute();
            $res = $stmt->get_result();

            while ($m = $res->fetch_assoc()) {
            ?>
                <tr>
                    <td><?= $m['pk_measurement'] ?></td>
                    <td><?= $m['temperature'] ?></td>
                    <td><?= $m['humidity'] ?></td>
                    <td><?= $m['pressure'] ?></td>
                    <td><?= $m['light'] ?></td>
                    <td><?= $m['gas'] ?></td>
                    <td><?= $m['timestamp'] ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="mid" value="<?= $m['pk_measurement'] ?>">
                            <input type="hidden" name="username" value="<?= $user['pk_username'] ?>">
                            <button name="action" value="delete_measurement">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <h2>Collections</h2>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Action</th>
            </tr>

            <?php
            $stmt = $conn->prepare("SELECT pk_collection, name, description FROM collections WHERE fk_user_creates = ?");
            $stmt->bind_param("s", $user['pk_username']);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($c = $res->fetch_assoc()) {
            ?>
                <tr>
                    <td><?= htmlspecialchars($c['name']) ?></td>
                    <td><?= htmlspecialchars($c['description']) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="cid" value="<?= $c['pk_collection'] ?>">
                            <input type="hidden" name="username" value="<?= $user['pk_username'] ?>">
                            <button name="action" value="delete_collection">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>

    <?php } else { ?>
        <br>
        <h2>Or create a new user account</h2>
        <form method="POST">
            <input required type="text" name="username" placeholder="username">
            <input required type="text" name="firstName" placeholder="First Name">
            <input required type="text" name="lastName" placeholder="Last Name">
            <input required type="email" name="email" placeholder="Email">
            <input required type="password" name="password" placeholder="Password">
            <input required type="password" name="confirmPassword" placeholder="Confirm Password">
            <input required type="submit" name="createaccount" value="Create new user account">
        </form>
    <?php }

    ?>

</body>

</html>