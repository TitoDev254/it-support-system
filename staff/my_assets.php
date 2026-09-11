<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION["role"] != "staff") {
    die("Access denied. Staff only.");
}

require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

$sql = "SELECT id, asset_tag, name, category, brand, model, status, created_at
        FROM assets
        WHERE assigned_to = ?
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

```
<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Assigned Assets | IT Support System</title>

<link rel="stylesheet" href="../assets/css/style.css">
```

</head>

<body>

<!-- NAVIGATION -->

<div class="navbar">

```
<a href="dashboard.php">
    🛠️ IT Support System
</a>

<a href="create_ticket.php">
    📝 Report Issue
</a>

<a href="my_tickets.php">
    🎫 My Tickets
</a>

<a href="my_assets.php">
    💻 My Assets
</a>

<a href="../auth/logout.php">
    🚪 Logout
</a>
```

</div>

<!-- MAIN CONTENT -->

<div class="container">

```
<div class="header">

    <h1>💻 My Assigned Assets</h1>

    <p>
        View the IT equipment currently assigned to you.
    </p>

</div>


<div class="table-container">

    <?php if ($result->num_rows > 0): ?>

        <table>

            <thead>

                <tr>

                    <th>Asset Tag</th>

                    <th>Name</th>

                    <th>Category</th>

                    <th>Brand</th>

                    <th>Model</th>

                    <th>Status</th>

                </tr>

            </thead>

            <tbody>

                <?php while ($asset = $result->fetch_assoc()): ?>

                    <tr>

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

$asset_status = $asset["status"];

if ($asset_status == "Available") {
    $asset_status_class = "status-available";
} elseif ($asset_status == "Assigned") {
    $asset_status_class = "status-assigned";
} elseif ($asset_status == "Maintenance") {
    $asset_status_class = "status-maintenance";
} else {
    $asset_status_class = "";
}

?>

<span class="status <?php echo $asset_status_class; ?>">
    <?php echo htmlspecialchars($asset_status); ?>
</span>
                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    <?php else: ?>

        <div class="actions">

            <h3>📭 No Assets Assigned</h3>

            <p>
                You currently have no IT assets assigned to you.
            </p>

        </div>

    <?php endif; ?>

</div>


<br>

<a href="dashboard.php">
    ← Back to Dashboard
</a>
```

</div>

</body>

</html>

<?php

$stmt->close();

$conn->close();

?>
