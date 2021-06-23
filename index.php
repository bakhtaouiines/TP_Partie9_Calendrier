<!DOCTYPE html>
<html>
  <head>
    <title>Calendrier PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  <style>
    .defaultDay
    {
      font-family:arial;
      font-weight: lighter;
      text-align:center;
      vertical-align:middle;
    }
    .validDay
    {
      color:grey;
      background-color:white;
    }
    .emptyDay
    {
      color: grey;
      background-color:grey;
    }
    .sunDay
    {
      color:black;
      background-color:white;
    }
  </style>
  </head>
  <body>
    <center>
    <form method="post" action="index.php">
      <select name="month" >
        <?php
          $yearArray = array( 1 => 'janvier',
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
          foreach ($yearArray as $key => $value)
          {
        ?>
          <option value='<?= $key; ?>' 
            <?php 
            // serveur: mois donné & correspond à celui qui va être affiché
            if (isset($_POST['month']) && $_POST['month'] == $key)
            {
              echo 'selected';
            } 
            // client & serveur: commun
            ?>
            ><?= $value ;?>
          </option>
            <?php
              }
            ?>
          </select>
          <select name="year">
            <?php
            for ($countYear = 1970; $countYear <= 2032; $countYear++)
            {
            // partie serveur:
            ?>
            <option 
              <?php 
              // serveur: année donnée & correspond à celle qui va être affiché
              if (isset($_POST['year']) && $_POST['year'] == $countYear)
              {
                echo 'selected';
              }
            // client & serveur: commun
            ?>
              >
              <?= $countYear ;?>
            </option>
            <?php
            }
            ?>
          </select>
        <input type="submit" name="submit" value="Afficher" class="btn btn-primary">
    </form>
    <?php
      if (isset($_POST['submit']))
      {
        /*
        * partie serveur: recupération des paramètres
        */
        $month = $_POST['month'];
        $year = $_POST['year'];
    ?>
      <!-- Affichage du calendrier
        table-striped: alternance automatique de la couleur de fond 'gris clair/blanc'
        bordered & table-bordered: ajout de bordures aux cellules du tableau
      -->
      <table class="table bordered table-bordered">
        <thead>
          <!-- header: noms des jours de la semaine -->
          <div><strong><?= $yearArray[$month].' '.$year ?></strong></div>
          <div><th>Lundi</th></div>
          <div><th>Mardi</th></div>
          <div><th>Mercredi</th></div>
          <div><th>Jeudi</th></div>
          <div><th>Vendredi</th></div>
          <div><th>Samedi</th></div>
          <div><th>Dimanche</th></div>
        </thead>
        <tbody>
          <?php
            // affichage des jours du mois précédent dans la première semaine
            $firstValidDay = date( "N", mktime (0,0,0,$month,1,$year));
            for ($countDay = 1; $countDay < $firstValidDay; $countDay++) 
            {
              echo "<td class=\"emptyDay table-active\"> </td>";
            }
            // affichage des jours du mois
            $numberDaysInMonth = date( "t", mktime(0,0,0,$month,1,$year));
            for ($days = 1; $days <= $numberDaysInMonth+6; $days++) 
            {
              if ($days > $numberDaysInMonth) 
              {
                // On complete avec des jours 'vide' hors du mois courant
                echo "<td class=\"table-active\"></td>";
              }
              else
              {
                if (date( "N", mktime (0,0,0,$month,$days,$year)) == 7)
                { 
                  // mettre les dimanches en rouge
                  {
                    echo "<td class=\"defaultDay sunDay\">$days</td>";
                    // attention si on est dimanche & dernier jour du mois
                    if ($days == $numberDaysInMonth) break;
                    // sinon une nouvelle semaine commence
                    echo "<tr>"; 
                  }
                }
                else
                {
                  // sinon mettre le jour de la semaine en vert
                  echo "<td class=\"defaultDay validDay\">$days</td>";
                }
              }
            }
          }
          ?>
          </tbody>
        </table>
    </center>
  </body>
</html>
