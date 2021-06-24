<?php
define('START_YEAR', 1970);
define('END_YEAR', date('Y') + 10);

$monthList = array(
    1 => 'janvier',
    2 => 'février',
    3 => 'mars',
    4 => 'avril',
    5 => 'mai',
    6 => 'juin',
    7 => 'juillet',
    8 => 'août',
    9 => 'septembre',
    10 => 'octobre',
    11 => 'novembre',
    12 => 'décembre'
);
// Initialisation tableau d'erreurs
$formErrorList = [];
// On entame les vérifications
if (isset($_POST['displayCalendar'])) {
    // Vérif mois
    if (!empty($_POST['month'])) {
        if (in_array($month, $monthList)) { //array_key_exists — Vérifie si une clé existe dans un tableau
            $month = htmlspecialchars($_POST['month']); //htmlspecialchars — Convertit les caractères spéciaux en entités HTML
        } else {
            $formErrorList['month'] = 'Informations reçues non prévues';
        }
    } else {
        $formErrorList['month'] = 'Vous n\'avez pas sélectionné de mois';
    }
    // Vérif année
    if (!empty($_POST['year'])) {
        if (($year >= START_YEAR) || ($year > END_YEAR)) {

            $year = htmlspecialchars($_POST['year']);
        } else {
            $formErrorList['year'] = 'Informations reçues non prévues';
        }
    }
    $formErrorList['year'] = 'Vous n\'avez pas sélectionné d\'année';
}
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <title>Calendrier PHP</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="style/style.css" rel="stylesheet">
</head>

<body>
    <div class="mx-4">
        <h1 class="fw-lighter fs-3">TP - Partie 9 - CALENDRIER PHP</h1>
        <p class=" lead fst-italic">Faire un formulaire avec deux listes déroulantes. La première sert à choisir le mois, et le deuxième permet d'avoir l'année.</p>
    </div>
    <hr>
    <!----------------------------------------------------------FORMULAIRE--------------------------------------------------------->
    <div class="container">
        <form method="post" action="index.php">
            <div class="container">
                <div class="row">
                    <!-----------Sélection du mois----------------->
                    <select name="month" class="col form-select">
                        <option selected value="" disabled selected>Veuillez choisir un mois</option>
                        <?php
                        foreach ($monthList as $monthNumber => $monthName) {
                        ?>
                            <option value="<?= $monthNumber; ?>" <?php
                                                                    if (isset($_POST['month']) && $_POST['month'] == $monthName) {
                                                                        echo 'selected';
                                                                    }
                                                                    ?>><?= $monthName; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                    <?php
                    //verif
                    if (!empty($formErrorList['month'])) {
                    ?>
                        <p class="text-danger text-center"><?= $formErrorList['month']; ?></p>
                    <?php
                    }
                    ?>
                    <!-----------Sélection de l'année----------------->
                    <select name="year" class="col form-select">
                        <option selected value="" disabled selected>Veuillez choisir une année</option>
                        <?php
                        for ($year = START_YEAR; $year <= END_YEAR; $year++) {
                            $yearList[] = $year;
                        ?>
                            <option value="<?= $year; ?>" <?php
                                                            if (isset($_POST['year']) && $_POST['year'] == $year++) {
                                                                echo 'selected';
                                                            }
                                                            ?>>
                                <?= $year; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>

                    <?php
                    //verif
                    if (!empty($formErrorList['year'])) {
                    ?>
                        <p class="text-danger text-center"><?= $formErrorList['year']; ?></p>
                    <?php
                    }
                    ?>
                    <input type="submit" name="displayCalendar" value="Afficher" class="col-md-1 ms-4 btn btn-secondary btn-sm">
                </div>
            </div>
        </form>
    </div>

    <!------------------------------------------------------------------CALENDRIER--------------------------------------------------------------------------->
    <?php
    if (isset($_POST['displayCalendar']) && empty($formErrorList)) {
        $month = $_POST['month'];
        $selectedYear = $_POST['year'];
    ?>
        <table class="table table-bordered border-secondary table-responsive mx-auto">
            <thead style="background-color: #808080 ; color: #FFFFFF;">
                <tr>
                    <!-- header: affichage du mois & année sélectionné + noms des jours de la semaine -->
                    <div class="fs-4 text-center mt-4 mb-2"><?= $monthList[$month] . ' ' . $selectedYear ?></div>
                    <th class="col fw-lighter">Lu</th>
                    <th class="col fw-lighter">Ma</th>
                    <th class="col fw-lighter">Me</th>
                    <th class="col fw-lighter">Je</th>
                    <th class="col fw-lighter">Ve</th>
                    <th class="col fw-lighter">Sa</th>
                    <th class="col fw-lighter">Di</th>
                </tr>
            </thead>
            <!-- Corps du tableau -->
            <tbody>
                <?php
                // affichage des jours du mois précédent dans la première semaine du mois demandé
                $firstValidDay = date("N", mktime(0, 0, 0, $month, 1, $selectedYear));
                for ($countDay = 1; $countDay < $firstValidDay; $countDay++) {
                    echo '<td class="table-active"></td>';
                }
                // affichage des jours du mois
                $numberDaysInMonth = date("t", mktime(0, 0, 0, $month, 1, $selectedYear));
                // On complete avec des jours 'vide' hors du mois courant dans la semaine courante
                for ($days = 1; $days <= $numberDaysInMonth; $days++) {
                    // obtenir le numéro du jour, format ISO-8601 c.a.d. lundi=1 .. dimanche=7 
                    $currentDayNumber = date("N", mktime(0, 0, 0, $month, $days, $selectedYear));
                    if ($currentDayNumber == 7) {
                        echo '<td class="defaultDay">' . $days . '</td>';
                        // sinon une nouvelle semaine commence
                        if ($days != $numberDaysInMonth) {
                            echo "<tr>";
                        }
                    } else {
                        echo '<td class="defaultDay validDay">' . $days . '</td>';
                    }
                }
                // completer la semaine courant jusqu'à dimanche avec des jours 'vides'
                while ($currentDayNumber != 7) {
                    $currentDayNumber = date("N", mktime(0, 0, 0, $month, $days, $selectedYear));
                    echo '<td class="table-active"></td>';
                    $days++;
                }
                ?>
            </tbody>
        </table>
    <?php
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>

</html>