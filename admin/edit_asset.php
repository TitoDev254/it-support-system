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


// Check if asset ID was provided

if (!isset($_GET["id"])) {
    die("Asset ID not provided.");
}

$asset_id = $_GET["id"];


// Get asset information

$sql = "SELECT * FROM assets WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $asset_id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {
    die("Asset not found.");
}

$asset = $result->fetch_assoc();

$stmt->close();


// Get users

$sql_users = "SELECT id, name FROM users ORDER BY name ASC";

$result_users = $conn->query($sql_users);


// Update asset

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $asset_tag = $_POST["asset_tag"];
    $name = $_POST["name"];
    $category = $_POST["category"];
    $brand = $_POST["brand"];
    $model = $_POST["model"];
    $assigned_to = $_POST["assigned_to"];
    $status = $_POST["status"];


    $sql_update = "UPDATE assets

                   SET asset_tag = ?,
                       name = ?,
                       category = ?,
                       brand = ?,
                       model = ?,
                       assigned_to = ?,
                       status = ?

                   WHERE id = ?";


    $stmt_update = $conn->prepare($sql_update);


    $stmt_update->bind_param(
        "sssssssi",
        $asset_tag,
        $name,
        $category,
        $brand,
        $model,
        $assigned_to,
        $status,
        $asset_id
    );


    if ($stmt_update->execute()) {

        header("Location: manage_assets.php");

        exit();

    } else {

        echo "Failed to update asset.";

    }


    $stmt_update->close();
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Edit Asset</title>

</head>

<body>

    <h1>Edit IT Asset 💻</h1>

    <a href="manage_assets.php">
        ← Back to Assets
    </a>

    <br><br>


    <form method="POST">

        <label>Asset Tag:</label><br>

        <input
            type="text"
            name="asset_tag"
            value="<?php echo htmlspecialchars($asset["asset_tag"]); ?>"
            required
        >

        <br><br>


        <label>Asset Name:</label><br>

        <input
            type="text"
            name="name"
            value="<?php echo htmlspecialchars($asset["name"]); ?>"
            required
        >

        <br><br>


        <label>Category:</label><br>

        <select name="category" required>

            <?php

            $categories = [
                "Laptop",
                "Desktop",
                "Printer",
                "Monitor",
                "Router",
                "Phone",
                "Other"
            ];

            foreach ($categories as $category):

            ?>

                <option
                    value="<?php echo $category; ?>"
                    <?php
                    if ($asset["category"] == $category)
                        echo "selected";
                    ?>
                >

                    <?php echo $category; ?>

                </option>

            <?php endforeach; ?>

        </select>

        <br><br>


        <label>Brand:</label><br>

        <input
            type="text"
            name="brand"
            value="<?php echo htmlspecialchars($asset["brand"]); ?>"
            required
        >

        <br><br>


        <label>Model:</label><br>

        <input
            type="text"
            name="model"
            value="<?php echo htmlspecialchars($asset["model"]); ?>"
            required
        >

        <br><br>


        <label>Assigned To:</label><br>

        <select name="assigned_to" required>

            <?php while ($user = $result_users->fetch_assoc()): ?>

                <option
                    value="<?php echo $user["id"]; ?>"
                    <?php
                    if ($asset["assigned_to"] == $user["id"])
                        echo "selected";
                    ?>
                >

                    <?php echo htmlspecialchars($user["name"]); ?>

                </option>

            <?php endwhile; ?>

        </select>

        <br><br>


        <label>Status:</label><br>

        <select name="status" required>

            <?php

            $statuses = [
                "Available",
                "Assigned",
                "Maintenance",
                "Retired"
            ];

            foreach ($statuses as $status):

            ?>

                <option
                    value="<?php echo $status; ?>"
                    <?php
                    if ($asset["status"] == $status)
                        echo "selected";
                    ?>
                >

                    <?php echo $status; ?>

                </option>

            <?php endforeach; ?>

        </select>

        <br><br>


        <button type="submit">
            Save Changes
        </button>

    </form>

</body>

</html>