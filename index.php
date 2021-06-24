<?php
define( 'dateFormatEng','Y/n/j');  
define( 'dateFormatFr','j/n/Y');
//$dateFormatEng='Y/n/j';
//$dateFormatFr='j/n/Y';
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
$yearList=[];
?>
<!DOCTYPE html>
<html>

<head>
  <title>Calendrier PHP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="row justify-content-center">
    <div class="col-auto">
      <form method="get" action="index.php">
        <select name="month">
        <option selected value="" disabled selected>Veuillez choisir un mois</option>
              <?php
          foreach ($monthList as $monthNumber => $monthName) {
          ?>
            <option value="<?= $monthNumber; ?>" <?php
                                          if (isset($_GET['month']) && $_GET['month'] == $monthNumber) {
                                            echo 'selected';
                                          }
                                          ?>><?= $monthName; ?>
            </option>
          <?php
          }
          ?>
        </select>
        <select name="year">
        <option selected value="" disabled selected>Veuillez choisir une année</option>
          <?php
          for ($year = 1970; $year <= 2030; $year++) {
            $yearList[]= $year;
          ?>
            <option value="<?= $year; ?>" <?php
                    if (isset($_GET['year']) && $_GET['year'] == $year) {
                      echo 'selected';
                    }
                    ?>>
                    <?= $year; ?>
            </option>
          <?php
          }
          ?>
        </select>
        <input type="submit" name="displayCalendar" value="Afficher" class="btn btn-danger">
      </form>
      <?php
      if (isset($_GET['displayCalendar'])) {
        /*
        * recupération et vérification des paramètres reçus
        */
        $errorList=[];
        if (empty($_GET['month']))
        {
          $errorList['month']= 'absent';
        }
        else
        {
          $month = htmlspecialchars($_GET['month']);
          if (!array_key_exists($month, $monthList))
          {
            $errorList['month']= $month.' non prévu';
          }
        }
        if (empty($_GET['year']))
        {
          $errorList['year']= 'absente';
        }
        else
        {
          $year = htmlspecialchars($_GET['year']);
          if (!in_array($year,$yearList))
          {
            $errorList['year']= $year.' non prévue';
          }
        }
      if (!empty($errorList))
      {
        var_dump($errorList);
        exit;
      }
      // calcul de jours fériés dans l'année donnée
      $publicHolliday=[];
      array_push( $publicHolliday, '1/1/'.$year, '1/5/'.$year, '8/5/'.$year, '14/7/'.$year, '15/8/'.$year, '1/11/'.$year, '11/11/'.$year, '25/12/'.$year);
      $dimancheDePaques= date( dateFormatEng, easter_date($year));
      $lundiDePaques= date( dateFormatFr, strtotime($dimancheDePaques." +1 day"));
      $jeudiDeAscension= date( dateFormatFr, strtotime($dimancheDePaques." +39 days"));
      $lundiDePentecote= date( dateFormatFr, strtotime($dimancheDePaques." +50 days"));
      array_push( $publicHolliday, $dimancheDePaques, $lundiDePaques, $jeudiDeAscension, $lundiDePentecote);
      sort($publicHolliday);
      ?>
        <!-- Affichage du calendrier
            table-striped: alternance automatique de la couleur de fond 'gris clair/blanc'
            bordered & table-bordered: ajout de bordures aux cellules du tableau
          -->
            <table class="table table-responsive table-bordered">
              <thead>
                <!-- header: noms des jours de la semaine -->
                <div><strong><?= $monthList[$month] . ' ' . $year ?></strong></div>
                <div>
                  <th class="dayName">Lundi</th>
                </div>
                <div>
                  <th class="dayName">Mardi</th>
                </div>
                <div>
                  <th class="dayName">Mercredi</th>
                </div>
                <div>
                  <th class="dayName">Jeudi</th>
                </div>
                <div>
                  <th class="dayName">Vendredi</th>
                </div>
                <div>
                  <th class="dayName">Samedi</th>
                </div>
                <div>
                  <th class="dayName">Dimanche</th>
                </div>
              </thead>
              <tbody>
                <?php
                // affichage des jours du mois précédent dans la première semaine du mois demandé
                $firstValidDay = date("N", mktime(0, 0, 0, $month, 1, $year));
                for ($countDay = 1; $countDay < $firstValidDay; $countDay++) {
                  echo '<td class="table-active"></td>';
                }
                // affichage des jours du mois
                $numberDaysInMonth = date("t", mktime(0, 0, 0, $month, 1, $year));
                // On complete avec des jours 'vide' hors du mois courant dans la semaine courante
                for ($days = 1; $days <= $numberDaysInMonth; $days++) {
                  $ddMmYyyy= sprintf('%d/%d/%d', $days,$month,$year);
                  // obtenir le numéro du jour, format ISO-8601 c.a.d. lundi=1 .. dimanche=7 
                  $currentDayNumber = date("N", mktime(0, 0, 0, $month, $days, $year));
                  if (($currentDayNumber == 7) || (in_array($ddMmYyyy, $publicHolliday))){
                    // mettre les dimanches et les jours fériés en rouge
                    echo '<td class="defaultDay sunDay">' . $days . '</td>';
                    // sinon une nouvelle semaine commence
                    if (($days != $numberDaysInMonth) && ($currentDayNumber == 7)) {
                      echo "<tr>";
                    }
                  } else {
                    // sinon mettre le jour de la semaine en vert
                    echo '<td class="defaultDay validDay">' . $days . '</td>';
                  }
                }
                // completer la semaine courant jusqu'à dimanche avec des jours 'vides'
                while ($currentDayNumber != 7) {
                  $currentDayNumber = date("N", mktime(0, 0, 0, $month, $days, $year));
                  echo '<td class="table-active"></td>';
                  $days++;
                }
                ?>
              </tbody>
            </table>
      </div>
    </div>
  <?php
  }
  ?>
</body>

</html>