<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <title>Calendrier PHP</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link href="style/style.css" rel="stylesheet">
</head>

<body>
    <div class="mx-4">
        <h1 class="fw-lighter fs-3">TP - Partie 9 - CALENDRIER PHP</h1>
        <p class=" lead fst-italic">Faire un formulaire avec deux listes déroulantes. La première sert à choisir le mois, et le deuxième permet d'avoir l'année.</p>
    </div>
    <hr>

    <center>
        <form method="post" action="index.php">
            <div class="container">
                <div class="row">
                    <select name="month" class="col form-select">
                        <option selected value="" disabled selected>Veuillez choisir un mois</option>
                        <?php
                        $yearArray = array(
                            1 => 'janvier',
                            2 => 'février',
                            3 => 'mars',
                            4 => 'avril',
                            5 => 'mai',
                            6 => 'juin',
                            7 => 'juillet',
                            8 => 'aout',
                            9 => 'septembre',
                            10 => 'octobre',
                            11 => 'novembre',
                            12 => 'décembre'
                        );
                        foreach ($yearArray as $key => $value) {
                        ?>
                            <option value='<?= $key; ?>' <?php
                                                            // serveur: mois donné & correspond à celui qui va être affiché
                                                            if (isset($_POST['month']) && $_POST['month'] == $key) {
                                                                echo 'selected';
                                                            }
                                                            // client & serveur: commun
                                                            ?>><?= $value; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                    <select name="year" class="col form-select">
                        <option selected value="" disabled selected>Veuillez choisir une année</option>
                        <?php
                        for ($countYear = 1970; $countYear <= 2032; $countYear++) {
                            // partie serveur:
                        ?>
                            <option <?php
                                    // serveur: année donnée & correspond à celle qui va être affiché
                                    if (isset($_POST['year']) && $_POST['year'] == $countYear) {
                                        echo 'selected';
                                    }
                                    // client & serveur: commun
                                    ?>>
                                <?= $countYear; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="submit" name="submit" value="Afficher" class="col-md-1 ms-4 btn btn-secondary btn-sm">
                </div>
            </div>
        </form>
    </center>
    <!-- Affichage du calendrier -->
    <table class="table table-bordered border-secondary table-responsive mx-auto">
        <!-- les jours du mois si l'utilisateur a envoyé une requête -->

        <?php
        if (isset($_POST['submit'])) {
            // partie serveur: recupération des paramètres
            $month = $_POST['month'];
            $year = $_POST['year'];
        ?>
            <thead style="background-color: #808080 ; color: #959595;">

                <tr>
                    <!-- header: noms des jours de la semaine -->
                    <div class="fs-4 text-center mt-4 mb-2"><?= $yearArray[$month] . ' ' . $year ?></div>
                    <th class="col fw-lighter">Lu</th>
                    <th class="col fw-lighter">Ma</th>
                    <th class="col fw-lighter">Me</th>
                    <th class="col fw-lighter">Je</th>
                    <th class="col fw-lighter">Ve</th>
                    <th class="col fw-lighter">Sa</th>
                    <th class="col fw-lighter">Di</th>
                </tr>
            </thead>
            <tbody class="defaultDay">
            <?php
            // affichage des jours du mois précédent dans la première semaine
            $firstValidDay = date("N", mktime(0, 0, 0, $month, 1, $year)); // N = Jour de la semaine au format numérique
            for ($countDay = 1; $countDay < $firstValidDay; $countDay++) {
                echo "<td class=\"table-active \" ></td>";
            }
            // affichage des jours du mois
            $numberDaysInMonth = date("t", mktime(0, 0, 0, $month, 1, $year)); // t = Nombre de jours dans le mois
            //on grise les jours précédents le 1er jour du mois
            for ($days = 1; $days <= $numberDaysInMonth + 6; $days++) {
                if ($days > $numberDaysInMonth) {
                    echo "<td class=\"table-active\"></td>";
                } else {
                    if (date("N", mktime(0, 0, 0, $month, $days, $year)) == 6) { // N = Représentation numérique ISO-8601 du jour de la semaine
                        echo "<td class=\"defaultDay\">$daysvalue</td>";

                        // attention à la dernière semaine du mois... On arrête tout !
                        if ($days == $numberDaysInMonth) break;
                        echo "<td class=\"table-active\" ></td>";
                        // et une nouvelle semaine commence
                        echo '<tr>';
                    } else {
                        if ($days > $numberDaysInMonth) $daysvalue = "";
                        else  $daysvalue = $days;
                        echo "<td class=\"defaultDay validDay \">$daysvalue</td>";
                    }
                }
            }
        }
            ?>
            </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>

</html>