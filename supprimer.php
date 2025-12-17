<?php
require_once 'includes/tableau-functions.php';

if (isset($_GET['id'])) {
    global $connection;
    $id = (int) $_GET['id'];
    
    $sql = "DELETE FROM tableau WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: index.php");
exit;
?>
