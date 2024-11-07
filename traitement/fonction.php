<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connectez-vous à votre base de données MySQL
function connexionBD()
{
  $connexion = mysqli_connect("localhost", "root", "", "bdcodif");
  // Vérifiez la connexion
  if ($connexion === false) {
    die("Erreur : Impossible de se connecter. " . mysqli_connect_error());
  }
  return $connexion;
}
$connexion = connexionBD();



function getLitsBySexeAndNiveau2()
{
    global $connexion;

    // Requête SQL pour récupérer le nombre de lits par sexe, niveau et établissement
    $sql = "
    SELECT 
        e.niveauFormation,
        e.etablissement,
        l.sexe,
        COUNT(DISTINCT q.id_lit_q) AS nombre_lits
    FROM 
        codif_etudiant e
    INNER JOIN 
        codif_quota q ON e.niveauFormation = q.niveauFormation
    INNER JOIN 
        Codif_lit l ON q.id_lit_q = l.id_lit
    GROUP BY 
        e.niveauFormation, e.etablissement, l.sexe;  
    ";

    // Préparation de la requête
    $stmt = $connexion->prepare($sql);

    // Vérification de la préparation de la requête
    if ($stmt === false) {
        die('Erreur de préparation de la requête : ' . htmlspecialchars($connexion->error));
    }

    // Exécution de la requête
    if (!$stmt->execute()) {
        die('Erreur lors de l\'exécution de la requête : ' . htmlspecialchars($stmt->error));
    }

    // Récupération des résultats
    $result = $stmt->get_result();

    // Vérification de la récupération des résultats
    if ($result === false) {
        die('Erreur lors de la récupération des résultats : ' . htmlspecialchars($stmt->error));
    }

    // Tableau pour stocker les données
    $lits = [];
    $totalGarçons = 0;
    $totalFilles = 0;
    $totalLits = 0;

    // Tableau pour stocker les totaux par établissement
    $totauxParEtablissement = [];

    // Stockage des résultats dans le tableau
    while ($row = $result->fetch_assoc()) {
        $niveau = $row['niveauFormation'];
        $etablissement = $row['etablissement'];
        $sexe = $row['sexe'];
        $nombre_lits = $row['nombre_lits'];

        // Initialisation si le niveau et l'établissement n'existent pas encore dans le tableau
        if (!isset($lits[$etablissement][$niveau])) {
            $lits[$etablissement][$niveau] = ['garçons' => 0, 'filles' => 0, 'total' => 0];
        }

        // Ajout du nombre de lits selon le sexe
        if ($sexe === 'G') {
            $lits[$etablissement][$niveau]['garçons'] += $nombre_lits;
            $totalGarçons += $nombre_lits;

            // Accumuler le total par établissement
            if (!isset($totauxParEtablissement[$etablissement])) {
                $totauxParEtablissement[$etablissement] = ['garçons' => 0, 'filles' => 0];
            }
            $totauxParEtablissement[$etablissement]['garçons'] += $nombre_lits;
        } elseif ($sexe === 'F') {
            $lits[$etablissement][$niveau]['filles'] += $nombre_lits;
            $totalFilles += $nombre_lits;

            // Accumuler le total par établissement
            if (!isset($totauxParEtablissement[$etablissement])) {
                $totauxParEtablissement[$etablissement] = ['garçons' => 0, 'filles' => 0];
            }
            $totauxParEtablissement[$etablissement]['filles'] += $nombre_lits;
        }

        // Calcul du total
        $lits[$etablissement][$niveau]['total'] = $lits[$etablissement][$niveau]['garçons'] + $lits[$etablissement][$niveau]['filles'];
        $totalLits = $totalGarçons + $totalFilles; // Calcul du total général
    }

    // Retourner le tableau de résultats et les totaux
    return [
        'lits' => $lits,
        'totaux' => [
            'garçons' => $totalGarçons,
            'filles' => $totalFilles,
            'total' => $totalLits,
        ],
        'totauxParEtablissement' => $totauxParEtablissement,
    ];
}
function controlSaisieQuota ($faculte){
    global $connexion;
      // Vérifier si la faculté a toutes les valeurs de nature nécessaires
      $natures_requises = ['depart', 'choix', 'validation', 'paiement'];
      $query = "SELECT DISTINCT nature FROM codif_delai WHERE faculte = ?";
  
      $stmt = $$connexion->prepare($query);
      $stmt->bind_param("s", $faculte);  // Paramètre pour la faculté
      $stmt->execute();
      $result = $stmt->get_result();
  
      // Récupérer toutes les valeurs de nature associées à cette faculté
      $natures_trouvees = [];
      while ($row = $result->fetch_assoc()) {
          $natures_trouvees[] = $row['nature'];
      }
  
      // Comparer les natures trouvées avec celles requises
      sort($natures_trouvees);
      sort($natures_requises);
  
      // Si les natures trouvées sont égales aux natures requises, la faculté est valide
      if ($natures_trouvees === $natures_requises) {
          return true; // Faculté valide
      } else {
          return false; // Faculté invalide
      }
}


function getFaculteByNiveauFormation($niveauFormation) {
    global $connexion;  // Connexion à la base de données

    // Requête pour récupérer la faculté associée au niveauFormation
    $query = "SELECT DISTINCT etablissement FROM codif_etudiant WHERE niveauFormation = ?";

    // Préparer la requête
    $stmt = $connexion->prepare($query);
    $stmt->bind_param("s", $niveauFormation);  // Paramètre pour le niveauFormation
    $stmt->execute();

    // Récupérer les résultats
    $result = $stmt->get_result();

    // Vérifier si une faculté est trouvée pour ce niveau de formation
    if ($result->num_rows > 0) {
        // Retourner la faculté (supposons que chaque niveauFormation a une seule faculté associée)
        $row = $result->fetch_assoc();
        return $row['etablissement'];  // Faculté associée à ce niveauFormation
    } else {
        // Aucun résultat trouvé pour ce niveauFormation
        return null;  // Aucun niveauFormation trouvé, retourner null
    }
}
?>
 
