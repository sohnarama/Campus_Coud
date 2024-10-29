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
$resultats = getLitsBySexeAndNiveau();

// Récupérer les données de lits et les totaux
$lits = $resultats['lits'];
$totaux = $resultats['totaux'];
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
                    <th>Niveau</th>
                    <th>Garçons</th>
                    <th>Filles</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($lits as $etablissement => $niveaux):
                    foreach ($niveaux as $niveau => $data): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($etablissement); ?></td>
                            <td><?php echo htmlspecialchars($niveau); ?></td>
                            <td><?php echo htmlspecialchars($data['garçons']); ?></td>
                            <td><?php echo htmlspecialchars($data['filles']); ?></td>
                            <td><?php echo htmlspecialchars($data['total']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <tr>
                    <td><strong>Total</strong></td>
                    <td></td>
                    <td><strong><?php echo htmlspecialchars($totaux['garçons']); ?></strong></td>
                    <td><strong><?php echo htmlspecialchars($totaux['filles']); ?></strong></td>
                    <td><strong><?php echo htmlspecialchars($totaux['total']); ?></strong></td>
                </tr>
            </tbody>
        </table>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
