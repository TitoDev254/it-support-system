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
    die("Asset ID not provided.");
}

$asset_id = $_GET["id"];

$sql = "DELETE FROM assets WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $asset_id);

if ($stmt->execute()) {
    header("Location: manage_assets.php");
    exit();
} else {
    echo "Failed to delete asset.";
}

$stmt->close();
$conn->close();

?>