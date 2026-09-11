<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION["role"] != "admin") {
    die("Access denied. Admins only.");
}

require_once "../config/database.php";


// Get all users

$sql = "SELECT id, name, email, role
        FROM users
        ORDER BY id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Users</title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <style>

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #1f2937;
            color: white;
        }

    </style>

</head>

<body>

<div class="navbar">

    <a href="dashboard.php">🛠️ Dashboard</a>

    <a href="manage_users.php">👥 Users</a>

    <a href="manage_assets.php">💻 Assets</a>

    <a href="tickets.php">🎫 Tickets</a>

    <a href="../auth/logout.php">🚪 Logout</a>

</div>


<div class="container">

    <h1>Manage Users 👥</h1>

    <a class="btn" href="dashboard.php">
        ← Back to Dashboard
    </a>


    <?php if ($result && $result->num_rows > 0): ?>

        <table>

            <tr>

                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
<th>Action</th>

            </tr>


            <?php while ($user = $result->fetch_assoc()): ?>

                <tr>

                    <td>
                        <?php echo $user["id"]; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($user["name"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($user["email"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($user["role"]); ?>
                    </td>
                    <td>

    <a class="btn"
       href="edit_user.php?id=<?php echo $user["id"]; ?>">
        ✏️ Edit Role
    </a>

    <?php if ($user["id"] != $_SESSION["user_id"]): ?>

        <a class="btn"
           href="delete_user.php?id=<?php echo $user["id"]; ?>"
           onclick="return confirm('Are you sure you want to delete this user?');">
            🗑️ Delete
        </a>

    <?php endif; ?>

</td>

                </tr>

            <?php endwhile; ?>

        </table>

    <?php else: ?>

        <p>No users found.</p>

    <?php endif; ?>

</div>

</body>

</html>