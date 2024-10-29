<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Quotas</title>
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
    <div class="container mt-4">
        <h2>
            <center>Liste des Quotas</center>
        </h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Établissement</th>
                    <th>Niveau De La Formation</th>
                    <th>Garçons</th>
                    <th>Filles</th>
                    <th>Total Quotas Par Niveau</th>
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
                <tr>
                    <td rowspan="<?php echo $rowCount; ?>" style="text-align: center; vertical-align: middle;">
                        <?php echo htmlspecialchars($etablissement); ?>
                    </td>
                    <td><?php echo htmlspecialchars($niveau); ?></td>
                    <td><?php echo htmlspecialchars($data['garçons']); ?></td>
                    <td><?php echo htmlspecialchars($data['filles']); ?></td>
                    <td><?php echo htmlspecialchars($data['total']); ?></td>
                </tr>
            <?php
                $firstRow = false; // Ne plus afficher l'établissement pour les lignes suivantes
            else: // Pour les lignes suivantes
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($niveau); ?></td>
                    <td><?php echo htmlspecialchars($data['garçons']); ?></td>
                    <td><?php echo htmlspecialchars($data['filles']); ?></td>
                    <td><?php echo htmlspecialchars($data['total']); ?></td>
                </tr>
            <?php
            endif;
        endforeach; ?>

        <!-- Afficher les totaux par établissement -->
        <tr>
            <td><strong>Total quota <?php echo htmlspecialchars($etablissement); ?></strong></td>
            <td></td>
            <td><strong>
                    <?php
                    echo htmlspecialchars($totauxParEtablissement[$etablissement]['garçons'] ?? 0);
                    ?>
                </strong></td>
            <td><strong>
                    <?php
                    echo htmlspecialchars($totauxParEtablissement[$etablissement]['filles'] ?? 0);
                    ?>
                </strong></td>
            <td><strong>
                    <?php
                    $totalEtablissement = ($totauxParEtablissement[$etablissement]['garçons'] ?? 0) +
                        ($totauxParEtablissement[$etablissement]['filles'] ?? 0);
                    echo htmlspecialchars($totalEtablissement);
                    ?>
                </strong></td>
        </tr>

    <?php endforeach; ?>
    <tr>
        <td><strong>Total Global</strong></td>
        <td></td>
        <td><strong><?php echo htmlspecialchars($totaux['garçons']); ?></strong></td>
        <td><strong><?php echo htmlspecialchars($totaux['filles']); ?></strong></td>
        <td><strong><?php echo htmlspecialchars($totaux['total']); ?></strong></td>
    </tr>
</tbody>


        </table>
    </div>
</body>