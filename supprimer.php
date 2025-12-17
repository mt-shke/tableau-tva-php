<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "tableau";
    
    $connection = new mysqli($servername, $username, $password, $database);
    
    $sql = "DELETE FROM tableau WHERE id = $id";
    
    if ($connection->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Erreur suppression : " . $connection->error;
    }
    $connection->close();
}
?>