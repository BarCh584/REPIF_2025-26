<!DOCTYPE html>
<html>

<head>
    <title>Measurements</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>

<body class="light-theme">
    <script src="../Libraries/JS/jquery-3.7.1.min.js"></script>
    <?php
    include("../Libraries/navbar.php");
    include("../Libraries/conndb.php");
    include("../Libraries/createmeasurement.php");
    ?>
    <?= createnavbar("Measurements"); ?>
    <h1>Measurements</h1>
    <?php
    $selectstationsstmt = $conn->prepare("SELECT * FROM stations WHERE fk_user_owns = ?");
    $selectstationsstmt->bind_param("i", $_SESSION['username']);
    $selectstationsstmt->execute();
    $result = $selectstationsstmt->get_result();
    ?>
    <div class="form-card">
        <div class="form-group">
    <select name="stationslist" id="stationslist">
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['pk_serialNumber'] . "'>" . htmlspecialchars($row['name']) . "</option>";
        }
        ?>
    </select>
    </div>
    <button type="submit" value="Create measurement" id="createMeasurementButton">Create Measurement</button>
    </div>
    <table id="tablemeasurements">
        <tr>
            <th>Time</th>
            <th>Temperature</th>
            <th>Humidity</th>
            <th>Pressure</th>
            <th>Light</th>
            <th>Gas</th>
        </tr>

    </table>
    <script>
        function start() {
            $.post("../Libraries/createmeasurement.php", {
                stationslist: $("#stationslist").val()
            }, function(data) {
                $("#tablemeasurements").append(data);
            });
        }

        $("#createMeasurementButton").click(start);
    </script>

</body>

</html>