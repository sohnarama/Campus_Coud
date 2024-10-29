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
// function getLitsBySexeAndNiveau() {
//     global $connexion;

//     // Requête SQL pour récupérer le nombre de lits par sexe et niveau
//     $sql = "
//     SELECT 
//       e.niveauFormation,
//       l.sexe,
//       COUNT(*) AS nombre_lits
//   FROM 
//       codif_etudiant e
//   LEFT JOIN 
//       codif_quota q ON e.niveauFormation = q.niveauFormation
//   INNER JOIN 
//       Codif_lit l ON q.id_lit_q = l.id_lit
//   GROUP BY 
//       e.niveauFormation, l.sexe;  
//   ";


//     // Préparation de la requête
//     $stmt = $connexion->prepare($sql);

//     // Vérification de la préparation de la requête
//     if ($stmt === false) {
//         die('Erreur de préparation de la requête : ' . htmlspecialchars($connexion->error));
//     }

//     // Liaison des paramètres
//     // $stmt->bind_param('s', $etablissement);

//     // Exécution de la requête
//     if (!$stmt->execute()) {
//         die('Erreur lors de l\'exécution de la requête : ' . htmlspecialchars($stmt->error));
//     }

//     // Récupération des résultats
//     $result = $stmt->get_result();

//     // Vérification de la récupération des résultats
//     if ($result === false) {
//         die('Erreur lors de la récupération des résultats : ' . htmlspecialchars($stmt->error));
//     }

//     // return $result->fetch_all(MYSQLI_ASSOC);
//     while ($row = $result->fetch_assoc()) {
//         // Faites quelque chose avec les données, par exemple :
//         echo "Niveau : " . $row['niveauFormation'] . " - Garçons : " . $row['nombre_lits'] . " - Filles : " . $row['nombre_lits'] . "<br>";
//     }
// }
function getLitsBySexeAndNiveau()
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
            $totalGarçons += $nombre_lits; // Accumuler le total des garçons
        } elseif ($sexe === 'F') {
            $lits[$etablissement][$niveau]['filles'] += $nombre_lits;
            $totalFilles += $nombre_lits; // Accumuler le total des filles
        }

        // Calcul du total
        $lits[$etablissement][$niveau]['total'] = $lits[$etablissement][$niveau]['garçons'] + $lits[$etablissement][$niveau]['filles'];
        $totalLits += $lits[$etablissement][$niveau]['total']; // Accumuler le total général
    }

    // Retourner le tableau de résultats et les totaux
    return ['lits' => $lits, 'totaux' => ['garçons' => $totalGarçons, 'filles' => $totalFilles, 'total' => $totalLits]];
}


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
        $totalLits += $lits[$etablissement][$niveau]['total'];
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


?>
