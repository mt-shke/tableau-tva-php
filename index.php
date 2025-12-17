<?php
require_once 'includes/tableau-functions.php';

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ajouter'])) {
        ajouterLigne($_POST['libelle'], $_POST['prix_ht'], $_POST['tva']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    
    if (isset($_POST['enregistrer'], $_POST['id_ligne'])) {
        updateLigne($_POST['id_ligne'], $_POST['libelle'], $_POST['prix_ht'], $_POST['tva']);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Récupération des données
$lignes = getToutesLignes();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tableau TVA</title>
    <link rel="stylesheet" href="index.css" />
    <script defer src="index.js"></script>
</head>
<body>
    <header>
        <h1>Tableau TVA</h1>
        <ul>
            <li><button disabled class="btn-black btn-ajouter">Ajouter une ligne</button></li>
            <li><button disabled class="btn-yellow btn-supprimer-ligne">Supprimer la ligne</button></li>
            <li><button class="btn-red btn-effacer-ligne">Effacer les champs</button></li>
        </ul>
    </header>

    <main>
        <!-- Popups -->
        <div class="popup popup-ajouter hidden">La ligne est bien ajoutée!</div>
        <div class="popup popup-supprimer hidden">La ligne est bien supprimée!</div>
        <div class="popup popup-effacer hidden">Les champs sont bien vides!</div>
        <div class='popup popup-effacer-1 hidden'>Les champs de la ligne sont bien vides!</div>
         <div class='popup popup-supprimer-1 hidden'>La ligne a été supprimée!</div>

        <div>
            <div class="title">
                <h2>Libellé</h2>
                <h2>Prix HT</h2>
                <h2 class="tva">TVA</h2>
                <h2>Prix TTC</h2>
                <div>
                    <span>Enregistrer</span><span>Calculer</span><span>Effacer</span><span>Supprimer</span>
                </div>
            </div>
            <hr class="hr-black hr"/>

            <ul class="ul-list">
                <?php while ($row = $lignes->fetch_assoc()): ?>
                    <li data-id="<?= $row['id'] ?>">
                        <form method="POST" class="form-ligne list">
                            <input type="hidden" name="id_ligne" value="<?= $row['id'] ?>">

                            <label class="border-list">
                                <input name="libelle" placeholder="libellé" 
                                       value="<?= htmlspecialchars($row['libelle']) ?>" required>
                            </label>

                            <label class="border-list">
                                <input type="number" step="0.01" name="prix_ht" class="prixht" 
                                       placeholder="prix HT" value="<?= $row['prix_ht'] ?>" required>
                            </label>

                            <select name="tva" class="border-list tva">
                                <option value="20" <?= ($row['tva'] == 20) ? 'selected' : '' ?>>20%</option>
                                <option value="5.5"<?= ($row['tva'] == 5.5) ? 'selected' : '' ?>>5.5%</option>
                            </select>

                            <label class="border-list">
                                <input type="number" step="0.01" name="prix_ttc" class="prixttc" 
                                       placeholder="prix TTC" value="<?= $row['prix_ttc'] ?>" required>
                            </label>

                            <div class="list-button-container">
                                <button type="submit" name="enregistrer" class="btn-dark btn-enregistrer">
                                    Enregistrer
                                </button>
                                <button type="button" class="btn-green btn-calculer">Calculer</button>
                                <button type="button" class="btn-yellow btn-effacer">Effacer</button>
                                <a href="supprimer.php?id=<?= $row['id'] ?>" class="btn-red btn-supprimer">
                                    Supprimer
                                </a>
                            </div>
                        </form>
                    </li>
                <?php endwhile; ?>

                <!-- Formulaire d'ajout -->
                <li>
                    <form method="POST" class="ajout-form list line">
                        <label>
                            <input type="text" name="libelle" placeholder="Nouveau libellé" required>
                        </label>
                        <label>
                            <input type="number" step="0.01" name="prix_ht" placeholder="Prix HT" required>
                        </label>
                        <select name="tva">
                            <option value="20">20%</option>
                            <option value="5.5">5.5%</option>
                        </select>
                        <button type="submit" name="ajouter" class="btn-blue">Ajouter</button>
                    </form>
                </li>
            </ul>

            <hr />
            <div class="list">
                <div></div><div></div>
                <div class="total">TOTAL:</div>
                <label class="border-list">
                    <input type="number" class="totalttc" placeholder="Prix total TTC">
                </label>
                <div></div>
            </div>
        </div>
    </main>
</body>
</html>
