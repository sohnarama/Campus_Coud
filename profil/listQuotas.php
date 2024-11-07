<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Metas, liens CSS et scripts -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../assets/css/vendor.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link rel="stylesheet" href="../assets/css/login.css" />
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <!-- script================================================== -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="../assets/bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../assets/js/modernizr.js"></script>
    <script src="../assets/js/pace.min.js"></script>
    <!-- Lien vers les icônes Fontawesome -->
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</head>

<?php
include('../traitement/fonction.php');
$resultats = getLitsBySexeAndNiveau2();

$lits = $resultats['lits'];
$totaux = $resultats['totaux'];

// Vérifiez si 'totauxParEtablissement' existe
if (isset($resultats['totauxParEtablissement'])) {
    $totauxParEtablissement = $resultats['totauxParEtablissement'];
} else {
    $totauxParEtablissement = []; // Initialiser par défaut
}

// Pour déboguer
//print_r($resultats); // Vérifiez le contenu des résultats

?>

<body>
    <?php include('../head.php'); ?>
    <div class="container mt-2">
        <h1>
            <br>
            <center>Liste des Quotas</center> <br>
        </h1>
        <table class="table table-bordered" style="border: 2px solid black;">
            <thead>
                <tr style="background-color: #d5e2ef; border: 1px solid black;">
                    <th style="border: 1px solid black;">
                        <h4>Facultés</h4>
                    </th>
                    <th style="border: 1px solid black;">
                        <h4>Niveaux Et Formations</h4>
                    </th>
                    <th style="border: 1px solid black;">
                        <h4>Garçons</h4>
                    </th>
                    <th style="border: 1px solid black;">
                        <h4>Filles</h4>
                    </th>
                    <th style="border: 1px solid black;">
                        <h4>Total Quotas Par Niveau</h4>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($lits as $etablissement => $niveaux):
                    $firstRow = true; // Indicateur pour le premier niveau
                    $rowCount = count($niveaux); // Compter le nombre de niveaux
                    foreach ($niveaux as $niveau => $data):
                        if ($firstRow): // Si c'est le premier niveau, afficher l'établissement
                ?>
                            <tr style="background-color: #3777b0; border: 1px solid black;">
                                <td rowspan="<?php echo $rowCount; ?>" style="text-align: center; vertical-align: middle; font-size: 16px; border: 1px solid black;">
                                    <h4><?php echo htmlspecialchars($etablissement); ?></h4>
                                </td>
                                <td style="border: 1px solid black;">
                                    <h4><?php echo htmlspecialchars($niveau); ?></h4>
                                </td>
                                <td style="border: 1px solid black;">
                                    <h4><?php echo htmlspecialchars($data['garçons']); ?></h4>
                                </td>
                                <td style="border: 1px solid black;">
                                    <h4><?php echo htmlspecialchars($data['filles']); ?></h4>
                                </td>
                                <td style="border: 1px solid black;">
                                    <h4><?php echo htmlspecialchars($data['total']); ?></h4>
                                </td>
                            </tr>
                        <?php
                            $firstRow = false; // Ne plus afficher l'établissement pour les lignes suivantes
                        else: // Pour les lignes suivantes
                        ?>
                            <tr style="background-color: #3777b0; border: 1px solid black;">
                                <td style="border: 1px solid black;">
                                    <h4><?php echo htmlspecialchars($niveau); ?></h4>
                                </td>
                                <td style="border: 1px solid black;">
                                    <h4><?php echo htmlspecialchars($data['garçons']); ?></h4>
                                </td>
                                <td style="border: 1px solid black;">
                                    <h4><?php echo htmlspecialchars($data['filles']); ?></h4>
                                </td>
                                <td style="border: 1px solid black;">
                                    <h4><?php echo htmlspecialchars($data['total']); ?></h4>
                                </td>
                            </tr>
                    <?php
                        endif;
                    endforeach; ?>

                    <!-- Afficher les totaux par établissement -->
                    <tr style="background-color: #d5e2ef; border: 1px solid black;">
                        <td style="border: 1px solid black;"><strong>
                                <h4>Total quota <?php echo htmlspecialchars($etablissement); ?></h4>
                            </strong></td>
                        <td style="border: 1px solid black;"></td>
                        <td style="border: 1px solid black;"><strong><?php echo htmlspecialchars($totauxParEtablissement[$etablissement]['garçons'] ?? 0); ?></strong></td>
                        <td style="border: 1px solid black;"><strong><?php echo htmlspecialchars($totauxParEtablissement[$etablissement]['filles'] ?? 0); ?></strong></td>
                        <td style="border: 1px solid black;"><strong>
                                <?php
                                $totalEtablissement = ($totauxParEtablissement[$etablissement]['garçons'] ?? 0) +
                                    ($totauxParEtablissement[$etablissement]['filles'] ?? 0);
                                echo htmlspecialchars($totalEtablissement);
                                ?>
                            </strong></td>
                    </tr>

                <?php endforeach; ?>
                <tr style="background-color:#d5e2ef; border: 1px solid black;">
                    <td style="border: 1px solid black;"><strong>
                            <h4>Total Global</h4>
                        </strong></td>
                    <td style="border: 1px solid black;"></td>
                    <td style="border: 1px solid black;"><strong><?php echo htmlspecialchars($totaux['garçons']); ?></strong></td>
                    <td style="border: 1px solid black;"><strong><?php echo htmlspecialchars($totaux['filles']); ?></strong></td>
                    <td style="border: 1px solid black;"><strong><?php echo htmlspecialchars($totaux['total']); ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php include('../foot.html'); ?>

</body>
<script src="../../assets/js/script.js"></script>
<script src="../../assets/js/jquery-3.2.1.min.js"></script>
<script src="../../assets/js/plugins.js"></script>
<script src="../../assets/js/main.js"></script>

</html>