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

if (!isset($_GET["id"])) {
    die("User ID is missing.");
}

$user_id = intval($_GET["id"]);

/* Get user details */
$sql = "SELECT id, name, email, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();


/* Update role */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $new_role = $_POST["role"];

    if ($new_role != "admin" && $new_role != "staff") {
        die("Invalid role.");
    }

    $update_sql = "UPDATE users SET role = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);

    $update_stmt->bind_param("si", $new_role, $user_id);

    if ($update_stmt->execute()) {

        header("Location: manage_users.php");
        exit();

    } else {

        echo "Error updating role.";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Edit User Role</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 25px;
        }

        .user-info {
            margin-bottom: 20px;
        }

        .user-info p {
            margin: 8px 0;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #0056b3;
        }

        .back {
            display: block;
            margin-top: 15px;
            text-align: center;
            text-decoration: none;
            color: #007bff;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>✏️ Edit User Role</h2>

    <div class="user-info">

        <p>
            <strong>Name:</strong>
            <?php echo htmlspecialchars($user["name"]); ?>
        </p>

        <p>
            <strong>Email:</strong>
            <?php echo htmlspecialchars($user["email"]); ?>
        </p>

        <p>
            <strong>Current Role:</strong>
            <?php echo htmlspecialchars($user["role"]); ?>
        </p>

    </div>

    <form method="POST">

        <label for="role">Select New Role</label>

        <select name="role" id="role" required>

            <option value="staff"
                <?php if ($user["role"] == "staff") echo "selected"; ?>>
                Staff
            </option>

            <option value="admin"
                <?php if ($user["role"] == "admin") echo "selected"; ?>>
                Admin
            </option>

        </select>

        <button type="submit">
            💾 Save Changes
        </button>

    </form>

    <a class="back" href="manage_users.php">
        ← Back to Manage Users
    </a>

</div>

</body>

</html>