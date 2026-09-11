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


// Get all assets and the users they are assigned to

$sql = "SELECT assets.id,
               assets.asset_tag,
               assets.name,
               assets.category,
               assets.brand,
               assets.model,
               assets.status,
               assets.created_at,
               users.name AS assigned_user

        FROM assets

        LEFT JOIN users
        ON assets.assigned_to = users.id

        ORDER BY assets.created_at DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>

<html>

<head>
    <link rel="stylesheet" href="../assets/css/style.css">

    <title>Asset Inventory</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        h1 {
            margin-bottom: 10px;
        }

        .top-links {
            margin-bottom: 20px;
        }

        .top-links a {
            margin-right: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #1f2937;
            color: white;
        }

        tr:hover {
            background: #f5f5f5;
        }

    </style>

</head>


<body>
    <div class="navbar">

    <a href="dashboard.php">
        🛠️ Dashboard
    </a>

    <a href="manage_assets.php">
        💻 Assets
    </a>

    <a href="tickets.php">
        🎫 Tickets
    </a>

    <a href="../auth/logout.php">
        🚪 Logout
    </a>

</div>

<div class="container">

    <h1>IT Asset Inventory 💻</h1>

    <div class="top-links">

        <a href="dashboard.php">
            ← Dashboard
        </a>

        <a class="btn" href="add_asset.php">
    ➕ Add Asset
</a>

    </div>


   <?php if ($result->num_rows > 0): ?>

<div class="table-container">

<table>

    <tr>

        <th>ID</th>
        <th>Asset Tag</th>
        <th>Name</th>
        <th>Category</th>
        <th>Brand</th>
        <th>Model</th>
        <th>Assigned To</th>
        <th>Status</th>
        <th>Added</th>
        <th>Action</th>

    </tr>


    <?php while ($asset = $result->fetch_assoc()): ?>

    <tr>

        <td>
            <?php echo $asset["id"]; ?>
        </td>

        <td>
            <?php echo htmlspecialchars($asset["asset_tag"]); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($asset["name"]); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($asset["category"]); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($asset["brand"]); ?>
        </td>

        <td>
            <?php echo htmlspecialchars($asset["model"]); ?>
        </td>

        <td>
            <?php
            if ($asset["assigned_user"]) {
                echo htmlspecialchars($asset["assigned_user"]);
            } else {
                echo "Not Assigned";
            }
            ?>
        </td>

        <td>
            <?php echo htmlspecialchars($asset["status"]); ?>
        </td>

        <td>
            <?php echo $asset["created_at"]; ?>
        </td>

        <td>

            <a href="edit_asset.php?id=<?php echo $asset["id"]; ?>">
                Edit
            </a>

            |

            <a
                href="delete_asset.php?id=<?php echo $asset["id"]; ?>"
                onclick="return confirm('Are you sure you want to delete this asset?');"
            >
                Delete
            </a>

        </td>

    </tr>

    <?php endwhile; ?>

</table>
</div>

<?php else: ?>

    <p>No assets have been registered yet.</p>

<?php endif; ?>

</div>
</body>

</html>

<?php

$conn->close();

?>