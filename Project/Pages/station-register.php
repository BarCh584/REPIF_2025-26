<!DOCTYPE html>
<html>
<head>
    <title>Register Station</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>
<body>
<h1>Register a Station</h1>

<form method="post">
    <label>Serial Number</label>
    <input type="text" name="serial_number" required>
    <input type="text" name="name" required>
    <input type="text" name="description">
    <button type="submit">Register</button>
</form> 
<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
     
}
?>
<p>(Could) Register via QR Code</p>

</body>
</html>
