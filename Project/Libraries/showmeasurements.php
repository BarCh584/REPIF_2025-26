    <?php
    include("../Libraries/conndb.php");
    $selectmeasstmt = $conn->prepare(
        "SELECT temperature, humidity, pressure, light, gas, timestamp 
        FROM measurements 
        WHERE fk_station_records = ? 
        ORDER BY timestamp DESC"
    );
    $selectmeasstmt->bind_param("s", $_POST["station"]);
    $selectmeasstmt->execute();
    $resultmeas = $selectmeasstmt->get_result();
    while ($rowmeas = $resultmeas->fetch_assoc()) {
        echo "<tr>
            <td>" . $rowmeas['timestamp'] . "</td>
            <td>" . $rowmeas['temperature'] . " °C</td>
            <td>" . $rowmeas['humidity'] . " %</td>
            <td>" . $rowmeas['pressure'] . " hPa</td>
            <td>" . $rowmeas['light'] . " lx</td>
            <td>" . $rowmeas['gas'] . " ppm</td>
        </tr>";
    }
    ?>