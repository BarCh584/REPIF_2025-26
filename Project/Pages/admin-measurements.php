<!DOCTYPE html>
<html>
<head>
    <title>Admin - Measurements</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>
<body>
<h1>Admin: All Measurements</h1>

<form>
    <label>Start Date</label><input type="datetime-local">
    <label>End Date</label><input type="datetime-local">
    <button>Filter</button>
</form>

<table>
    <tr><th>Station</th><th>Time</th><th>Value</th><th>Delete</th></tr>
    <tr>
        <td>Station A</td><td>2025-01-01 10:00</td><td>24°C</td>
        <td><button>Delete</button></td>
    </tr>
</table>

</body>
</html>
