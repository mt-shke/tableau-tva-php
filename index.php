<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Document</title>
      <link rel="stylesheet" href="index.css" />
      <script
			defer
			src="index.js"
		></script>
   </head>
   <body>
      <header>
         <h1>Tableau TVA</h1>
         <ul>
            <li>
               <button disabled class='btn-black btn-ajouter'>Ajouter une ligne</button>
            </li>
            <li>
               <button disabled class='btn-yellow btn-supprimer-ligne'>Supprimer la ligne</button>
            </li>
            <li>
               <button class='btn-red btn-effacer-ligne'>Effacer les champs</button>
            </li>
         </ul>
      </header>
      <main>
         <div class='popup popup-ajouter hidden'>La ligne est bien ajoutée!</div>
         <div class='popup popup-supprimer hidden'>La ligne est bien supprimée!</div>
         <div class='popup popup-effacer hidden'>Les champs des lignes du tableau sont bien vides!</div>
         <div class='popup popup-effacer-1 hidden'>Les champs de la ligne sont bien vides!</div>
         <div class='popup popup-supprimer-1 hidden'>La ligne a été supprimée!</div>
         <div>
            <div class="title">
               <h2>Libellé</h2>
               <h2>Prix HT</h2>
               <h2 class="tva">TVA</h2>
               <h2>Prix TTC</h2>
               <div>
                  <span>Calculer</span>
                  <span>Effacer</span>
                  <span>Supprimer</span>
               </div>
            </div>
            <hr class='hr-black hr'/>
            <ul class='ul-list'>
                <?php 
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "tableau";
              
                $connection = new mysqli($servername, $username, $password, $database);
              if ($connection->connect_error) {
                die("Connection failed: " .$connection->connect_error);
              }
     

              $sql = "SELECT * FROM tableau";
              $result = $connection->query($sql);

              if (!$result) {
                die("Invalid query: " . $connection->error);
              }

              if (isset($_POST['ajouter'])) {
    $libelle = $_POST['libelle'];
    $prix_ht = floatval($_POST['prix_ht']);
    $tva = floatval($_POST['tva']);
    $prix_ttc = $prix_ht * (1 + $tva / 100);
    
    $sql_insert = "INSERT INTO tableau (libelle, prix_ht, tva, prix_ttc) VALUES (?, ?, ?, ?)";
    $stmt = $connection->prepare($sql_insert);
    $stmt->bind_param("sddd", $libelle, $prix_ht, $tva, $prix_ttc);
    $stmt->execute();
    $nouveau_id = $connection->insert_id; // Récupère l'ID automatique
    
    // Redirection pour éviter double soumission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
    
}

if (isset($_POST['enregistrer']) && isset($_POST['id_ligne'])) {
    $id = intval($_POST['id_ligne']);
    $libelle = trim($_POST['libelle']);
    $prix_ht = floatval($_POST['prix_ht']);
    $tva = floatval($_POST['tva']);
    $prix_ttc = floatval($_POST['prix_ttc']);
    
    $sql = "UPDATE tableau SET libelle=?, prix_ht=?, tva=?, prix_ttc=? WHERE id=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sdddi", $libelle, $prix_ht, $tva, $prix_ttc, $id);
    
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

              while($row = $result->fetch_assoc()) 
                echo "
      <li class='' data-id='" . $row['id'] . "'>
         <form method='POST' class='form-ligne list'>
            <input type='hidden' name='id_ligne' value='" . $row['id'] . "'>
            <label class='border-list'><input name='libelle' placeholder='libellé' value='" . htmlspecialchars($row['libelle']) . "' required></label>
            <label class='border-list'><input type='number' step='0.01' name='prix_ht' class='prixht' placeholder='prix HT' value='" . $row['prix_ht'] . "' required></label>
            <select name='tva' class='border-list tva'>
                <option value='20' " . (($row['tva'] == 20.00) ? 'selected' : '') . ">20%</option>
                <option value='5.5' " . (($row['tva'] == 5.50) ? 'selected' : '') . ">5.5%</option>
            </select>
            <label class='border-list'><input type='number' step='0.01' name='prix_ttc' class='prixttc' placeholder='prix TTC' value='" . $row['prix_ttc'] . "' required></label>
            <div class='list-button-container'>
                <button type='submit' name='enregistrer' class='btn-dark btn-enregistrer'>Enregistrer</button>
                <button type='button' class='btn-green btn-calculer'>Calculer</button>
                <button type='reset' class='btn-yellow btn-effacer'>Effacer</button>
                <a href='supprimer.php?id=" . $row['id'] . "' class='btn-red btn-supprimer' onclick='return confirm(\"Confirmer ?\")'>Supprimer</a>
            </div>
         </form>
      </li>
                ";
              

              ?>

   <form method="POST" class="ajout-form list line">
               <label>
    <input type="text" name="libelle" placeholder="Nouveau libellé" required> </label>
    <label><input type="number" step="0.01" name="prix_ht" placeholder="Prix HT" required></label>
    <select name="tva">
        <option value="20">20%</option>
        <option value="5.5">5.5%</option>
    </select>
    <button type="submit" name="ajouter" class="btn-blue">Ajouter</button>
   </form>
             
            
            </ul>
            <hr />
            <div class='list'>
               <div></div>
               <div></div>
               <div class='total'>TOTAL:</div>
               <label class='border-list'><input type="number" class='totalttc' placeholder="Prix total TTC"></input></label>
               <div></div>
            </div>
         </div>
        
      </main>
   </body>
</html>
