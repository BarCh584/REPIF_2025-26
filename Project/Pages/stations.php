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
        include("../Libraries/conndb.php");
createnavbar("Stations");
        ?>
    </div>
    <h1>Your Stations</h1>

    <ul>
        <?php
        $stmt = $conn->prepare("SELECT * FROM stations WHERE name = ?");
        $stmt->bind_param("s", $_SESSION["name"]);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) { ?>
            <li>
                <b><?= htmlspecialchars($row['name']) ?></b> – <?= htmlspecialchars($row['description']) ?><br>
                <button name="edit_station" value="<?= $row['pk_serialNumber'] ?>">Edit name/description</button>
            </li>
        <?php
        }
        ?>
    </ul>

    <a href="station-register.php">Register new station by Serial Number</a>

</body>

</html>