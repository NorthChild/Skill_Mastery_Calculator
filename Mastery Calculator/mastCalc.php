<?php

session_start();
require_once "pdo.php";

if (! isset($_SESSION['skillSetChk'])) {
  echo('not set');
}

 ?>



  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <title>WELCOME TO THE SKILL MASTERY CALCULATOR</title>
      <link rel="stylesheet" href="CSS/masCalc.css">
      <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
      <link href="https://unpkg.com/nes.css/css/nes.css" rel="stylesheet" />
    </head>
    <body>

      <section class="nes-container is-dark">

        <h1>-- WELCOME TO THE SKILL MASTERY CALCULATOR -- </h1>

      <section id="indexContainer" class="message-left">
      <i class="nes-bcrikko"></i>
      <div class="nes-balloon from-left is-dark">

        <?php

        if (isset($_SESSION["error"])) {
          echo('<p style = "color:red">').htmlentities($_SESSION["error"])."</p>\n";
          unset($_SESSION["error"]);
        }
        ?>


      </div>
      </section>
      </section>


    </body>
  </html>
