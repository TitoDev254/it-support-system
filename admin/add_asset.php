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

$message = "";


// Get users for the dropdown

$sql_users = "SELECT id, name FROM users ORDER BY name ASC";

$result_users = $conn->query($sql_users);


// Add asset

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $asset_tag = $_POST["asset_tag"];
    $name = $_POST["name"];
    $category = $_POST["category"];
    $brand = $_POST["brand"];
    $model = $_POST["model"];
    $assigned_to = $_POST["assigned_to"];
    $status = $_POST["status"];


    $sql = "INSERT INTO assets
            (asset_tag, name, category, brand, model, assigned_to, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssssis",
        $asset_tag,
        $name,
        $category,
        $brand,
        $model,
        $assigned_to,
        $status
    );


    if ($stmt->execute()) {

        $message = "Asset added successfully!";

    } else {

        $message = "Failed to add asset: " . $conn->error;

    }

    $stmt->close();
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Add Asset - IT Support System</title>

</head>

<body>

    <h1>Add IT Asset 💻</h1>

    <a href="dashboard.php">← Back to Dashboard</a>

    <br><br>

    <?php if ($message != ""): ?>

        <p>
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>


    <form method="POST">

        <label>Asset Tag:</label><br>

        <input
            type="text"
            name="asset_tag"
            placeholder="AST-001"
            required
        >

        <br><br>


        <label>Asset Name:</label><br>

        <input
            type="text"
            name="name"
            placeholder="Laptop"
            required
        >

        <br><br>


        <label>Category:</label><br>

        <select name="category" required>

            <option value="">-- Select Category --</option>

            <option value="Laptop">Laptop</option>

            <option value="Desktop">Desktop</option>

            <option value="Printer">Printer</option>

            <option value="Monitor">Monitor</option>

            <option value="Router">Router</option>

            <option value="Phone">Phone</option>

            <option value="Other">Other</option>

        </select>

        <br><br>


        <label>Brand:</label><br>

        <input
            type="text"
            name="brand"
            placeholder="HP"
            required
        >

        <br><br>


        <label>Model:</label><br>

        <input
            type="text"
            name="model"
            placeholder="EliteBook 840 G3"
            required
        >

        <br><br>


        <label>Assigned To:</label><br>

        <select name="assigned_to" required>

            <option value="">-- Select User --</option>

            <?php while ($user = $result_users->fetch_assoc()): ?>

                <option value="<?php echo $user["id"]; ?>">

                    <?php echo htmlspecialchars($user["name"]); ?>

                </option>

            <?php endwhile; ?>

        </select>

        <br><br>


        <label>Status:</label><br>

        <select name="status" required>

            <option value="Available">Available</option>

            <option value="Assigned">Assigned</option>

            <option value="Maintenance">Maintenance</option>

            <option value="Retired">Retired</option>

        </select>

        <br><br>


        <button type="submit">
            Add Asset
        </button>

    </form>

</body>

</html>