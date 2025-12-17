<?php
require_once 'config/db.php';

/**
 * Ajoute une nouvelle ligne au tableau
 */
function ajouterLigne($libelle, $prix_ht, $tva) {
    global $connection;
    
    $libelle  = trim($libelle);
    $prix_ht  = (float) $prix_ht;
    $tva      = (float) $tva;
    $prix_ttc = $prix_ht * (1 + $tva / 100);

    $sql  = "INSERT INTO tableau (libelle, prix_ht, tva, prix_ttc) VALUES (?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sddd", $libelle, $prix_ht, $tva, $prix_ttc);
    $stmt->execute();
    
    return $connection->insert_id;
}

/**
 * Met à jour une ligne existante
 */
function updateLigne($id, $libelle, $prix_ht, $tva) {
    global $connection;
    
    $id       = (int) $id;
    $libelle  = trim($libelle);
    $prix_ht  = (float) $prix_ht;
    $tva      = (float) $tva;
    $prix_ttc = $prix_ht * (1 + $tva / 100);

    $sql  = "UPDATE tableau SET libelle=?, prix_ht=?, tva=?, prix_ttc=? WHERE id=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sdddi", $libelle, $prix_ht, $tva, $prix_ttc, $id);
    return $stmt->execute();
}

/**
 * Récupère toutes les lignes
 */
function getToutesLignes() {
    global $connection;
    
    $sql    = "SELECT * FROM tableau ORDER BY id ASC";
    $result = $connection->query($sql);
    
    if (!$result) {
        die("Invalid query: " . $connection->error);
    }
    
    return $result;
}
?>
