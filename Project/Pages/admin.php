<!DOCTYPE html>
<html>

<head>
    <title>Admin - Users</title>
    <link rel="stylesheet" href="../Styles/styles.css">
</head>

<body>
    <h1>Admin: User Management</h1>

    <button>Create New User</button>
    <ul>
        <form method="POST">
            <input type="text" name="search_user" placeholder="Search by username">
            <button>Search</button>
            <form>
                <?php
                include("../Libraries/conndb.php");
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $search_user = $_POST['search_user'];
                    $getuserstmt = $conn->prepare('SELECT pk_username FROM users WHERE pk_usernamer = ?');
                    $getuserstmt->bind_param('s', $search_user);
                    $getuserstmt->execute();
                    $getresults = $getuserstmt->get_result();
                    while ($row = $getresults->fetch_assoc()) {?>
                        <li><?= htmlspecialchars($row["username"]) ?></li>    
                    <?php
                    }
                    $getuserstmt->close();
                }
                ?>
    </ul>
    <!--
<table>
    <tr><th>Username</th><th>Email</th><th>Admin?</th><th>Actions</th></tr>
    <tr>
        <td>User123</td><td>u@mail.com</td><td>No</td>
        <td>
            <button>Edit</button>
            <button>Delete</button>
            <button>Make Admin</button>
        </td>
    </tr>
</table>-->

</body>

</html>