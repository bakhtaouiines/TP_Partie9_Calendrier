<!DOCTYPE html>
<html>

<head>
    <title>Calendrier PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <style>
        .defaultDay {
            color: #808080;
            font-family: arial;
            font-weight: lighter;
            font-size: 0.9vw;
            background-color: "#FFFFFF";
        }

        .validDay {
            background-color: white;
        }
    </style>
</head>

<body>
    <div class="mx-4">
        <h1 class="fw-lighter fs-3">TP - Partie 9 - CALENDRIER PHP</h1>
        <p class="fst-italic">Faire un formulaire avec deux listes déroulantes. La première sert à choisir le mois, et le deuxième permet d'avoir l'année.</p>
    </div>
    <hr>
    <center>
        <form method="post" action="index.php">
            <select name="month">
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
            <select name="year">
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
            <input type="submit" name="submit" value="Afficher" class="btn btn-danger btn-sm">
        </form>
    </center>
    <!-- Affichage du calendrier -->
    <table class="table table-borderless table-bordered table-responsive mx-auto" style="font-size:1.5vw ; width: 200px; ">
        <!-- les jours du mois si l'utilisateur a envoyé une requête -->
        <tr>
            <?php
            if (isset($_POST['submit'])) {
                // partie serveur: recupération des paramètres
                $month = $_POST['month'];
                $year = $_POST['year'];
            ?>
                <thead style="background-color : #808080; color: #D8D8D8;">
                    <!-- header: noms des jours de la semaine -->
                    <div style="color: #959595; font-weight : lighter; " class="fs-4 text-center mt-4 mb-2"><?= $yearArray[$month] . ' ' . $year ?></div>

                    <th scope="col" style="font-weight : lighter">Lundi</th>


                    <th scope="col" style="font-weight : lighter">Mardi</th>


                    <th scope="col" style="font-weight : lighter">Mercredi</th>


                    <th scope="col" style="font-weight : lighter">Jeudi</th>


                    <th scope="col" style="font-weight : lighter">Vendredi</th>


                    <th scope="col" style="font-weight : lighter">Samedi</th>


                    <th scope="col" style="font-weight : lighter">Dimanche</th>

                </thead>
                <tbody class="defaultDay">
                <?php
                // affichage des jours du mois précédent dans la première semaine
                $firstValidDay = date("w", mktime(0, 0, 0, $month, 1, $year)); // w = Jour de la semaine au format numérique
                for ($countDay = 1; $countDay < $firstValidDay; $countDay++) {
                    echo "<td class=\"table-active\" ></td>";
                }
                // affichage des jours du mois
                $numberDaysInMonth = date("t", mktime(0, 0, 0, $month, 1, $year)); // t = Nombre de jours dans le mois
                for ($days = 1; $days <= $numberDaysInMonth + 6; $days++) {
                    $timeStampDay = mktime(0, 0, 0, $month, $days, $year);
                    // mettre les dimanches en évidence
                    if (date("N", mktime(0, 0, 0, $month, $days, $year)) == 7) { // N = Représentation numérique ISO-8601 du jour de la semaine
                        if ($days > $numberDaysInMonth) $daysvalue = "";
                        else  $daysvalue = $days;
                        echo "<td class=\"defaultDay\">$daysvalue</td>";
                        // attention à la dernière semaine du mois... On arrête tout !
                        if ($days >= $numberDaysInMonth) break;
                        // et une nouvelle semaine commence
                        echo "<tr>";
                    } else {
                        // sinon mettre le jour de la semaine en vert
                        if ($days > $numberDaysInMonth) $daysvalue = "";
                        else  $daysvalue = $days;
                        echo "<td class=\"defaultDay validDay\">$daysvalue</td>";
                    }
                }
            }
                ?>
        </tr>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>

</html>