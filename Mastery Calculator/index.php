<?php

###################################### MODEL ###############################################

session_start();
// require_once "pdo.php";

// input validation
if ( isset($_POST['skillToMaster']) && isset($_POST['pastYrs']) && isset($_POST['weeklyPastHrs']) && isset($_POST['currentHrs']) && isset($_POST['calculate'])) {

   // POST - redirect - GET
   $_SESSION['skillToMaster'] = htmlentities($_POST['skillToMaster']);
   $_SESSION['pastYrs'] = htmlentities($_POST['pastYrs']);
   $_SESSION['weeklyPastHrs'] = htmlentities($_POST['weeklyPastHrs']);
   $_SESSION['currentHrs'] = htmlentities($_POST['currentHrs']);
   $_SESSION['calculate'] = $_POST['calculate'];

   // lets make easier to use variables
   $skillToMaster = $_SESSION['skillToMaster'];
   $pastYrs = $_SESSION['pastYrs'] + 0;
   $weeklyPastHrs = $_SESSION['weeklyPastHrs'] + 0;
   $currentHrs = $_SESSION['currentHrs'] + 0;


   // check if skill is set
   if (!isset($_SESSION['skillToMaster']) || (strlen($_SESSION['skillToMaster']) < 1)) {
     $skillSetChk = false;
   } else {
     $skillSetChk = true;
   }

   // check if data is numeric
   if (is_numeric($_SESSION['pastYrs']) === true) {
        $pastYrsChk = true;
      } else {
        $pastYrsChk = false;
      }
   if (is_numeric($_SESSION['weeklyPastHrs']) === true) {
        $weeklyPastHrChk = true;
      } else {
        $weeklyPastHrChk = false;
      }
   if (is_numeric($_SESSION['currentHrs']) === true) {
        $currentHrsChk = true;
      } else {
        $currentHrsChk = false;
      }

    // here we check if the data is valid
    if (($skillSetChk === false) && ($pastYrsChk === false) && ($weeklyPastHrChk === false) && ($currentHrsChk === false))  {

      $_SESSION['error'] = 'All fields are required';
      header('Location: index.php');
      return;


    } elseif (($skillSetChk === false) && ($pastYrsChk === true) && ($weeklyPastHrChk === true) && ($currentHrsChk === true)) {

      $_SESSION['error'] = 'Skill is required';
      header('Location: index.php');
      return;

    } elseif (($skillSetChk === true) && ($pastYrsChk === false) && ($weeklyPastHrChk === true) && ($currentHrsChk === true)) {

      $_SESSION['error'] = 'Years has to be an integer';
      header('Location: index.php');
      return;

    } elseif (($skillSetChk === true) && ($pastYrsChk === true) && ($weeklyPastHrChk === false) || ($currentHrsChk === false)) {

      $_SESSION['error'] = 'Hours has to be an integer';
      header('Location: index.php');
      return;

    } else {

      // since we know that the data is numeric, we proceed with all the required calculations that then gets sent to be displayed

      // lets determine how many hours the user devolved in the past
      $pastHours = ($weeklyPastHrs * 52.1429) * ($pastYrs);


      // lets calculate the current yearly hours
      $yearlyHours = ($currentHrs * 52.1429);
      $yearlyHoursPercentage = (($yearlyHours * 100) / 10000);

      // path to 10k
      $percToCompletion = (($pastHours * 100) / 10000);
      $percLeft = (100 - $percToCompletion);

      // lets see how long it will take to reach 10k at this paste
      $endGoal = 10000;
      $counter = 0;
      $totalHours = ($yearlyHours + $pastHours);


      // this is for later use when i will implement a chart of the data
      // $xData = array();
      // $yData = array();
      // $_SESSION['xData'] = $xData;
      // $_SESSION['yData'] = $yData;

      // here we check how long till user reaches 10k with input hours
      while ($endGoal > 0) {
        $counter += 1;
        $endGoal -= $totalHours;
        // this will be activated when i will implement charts
        // array_push($xData, $counter);
        // array_push($yData, $endGoal);
      }

      // lets make session variables from the new calculated data
      $_SESSION['counter'] = $counter;
      // total hours accumulated
      $_SESSION['pastHours'] = $pastHours;
      // yearly hours plus past hours
      $_SESSION['yearlyHours'] = $yearlyHours;
      // percentage to completion (10k - current perc)
      $_SESSION['percLeft'] = $percLeft;
      // yearly percentage out of 10k
      $_SESSION['yearHrPerc'] = $yearlyHoursPercentage;
      // current percentage
      $_SESSION['currentPerc'] = $percToCompletion;


      // $skillToMaster = $_SESSION['skillToMaster'];
      // $pastYrs = $_SESSION['pastYrs'];
      // $weeklyPastHrs = $_SESSION['weeklyPastHrs'];
      // $currentHrs = $_SESSION['currentHrs'];
      header('Location: index.php?proceed="active"');
      return;
    }

}



####################################### VIEW ###############################################
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

       <h1 id="mainTitle" >WELCOME TO THE SKILL MASTERY CALCULATOR</h1>

     <section id="indexContainer" class="message-left">
     <i id="brikko" class="nes-bcrikko"></i>
     <div id="balloon" class="nes-balloon from-left is-dark">

       <?php

       if (isset($_SESSION["error"])) {
         echo('<p style = "color:red">').htmlentities($_SESSION["error"])."</p>\n";
         unset($_SESSION["error"]);
       }

       ?>

       <?php

        if ((! isset($_GET['proceed'])) ) {

          echo('<form method="post">');
          echo('<fieldset>');

          echo('<label>Skill to Master: <br> <input type="text" name="skillToMaster"> </label>');
          echo('<label>Roughtly how many years have you already spent on the skill: <br> <input type="text" name="pastYrs"> </label>');
          echo('<label>How many hours were you devoting in the past (per week): <br> <input type="text" name="weeklyPastHrs"> </label>');
          echo('<label>How many hours do you currently spend on the skill: <br> <input type="text" name="currentHrs"> </label>');

          echo('<button class="nes-btn is-success" type="submit" name="calculate" value="proceed">Calculate</button>');

          echo('</fieldset>');
          echo('</form>');



        } else {

          $skillToMaster = $_SESSION['skillToMaster'];

          $pastYrs = $_SESSION['pastYrs'];
          $weeklyPastHrs = $_SESSION['weeklyPastHrs'];
          $currentHrs = $_SESSION['currentHrs'];
          $pstHR = floor($_SESSION['pastHours']);
          $yrlHR = floor($_SESSION['yearlyHours']);

          $percLEFT = floor( $_SESSION['percLeft']);
          $yearHrPERC = floor($_SESSION['yearHrPerc']);
          $currentPERC = floor($_SESSION['currentPerc']);
          $counter = $_SESSION['counter'];

          $percProgress = $currentPERC + $yearHrPERC;

          error_log("- current Perc ".$currentPERC.PHP_EOL, 3, "Errors/errorLog.log");
          error_log("- year hr perc ".$yearHrPERC.PHP_EOL, 3, "Errors/errorLog.log");
          error_log("- perc progress ".$currentHrs.PHP_EOL, 3, "Errors/errorLog.log");
          error_log("- current hours ".$percProgress.PHP_EOL, 3, "Errors/errorLog.log");
          error_log("- counter ".$counter.PHP_EOL, 3, "Errors/errorLog.log");
          error_log("- yearly hr into percentage ".$yearHrPERC.PHP_EOL, 3, "Errors/errorLog.log");

          // rememeber to color code the displayed inputs

          echo('<p> You are working towards reaching 10.000 hours in: <p style="color: #209cee">'.$skillToMaster.'</p></p>');
          echo('<p> Your total hours accumulated so far: <p style="color: #209cee">'.$pstHR.' hours</p></p>');
          echo('<p> Your yearly input total towards Mastery (at <span style="color: #209cee">'.$currentHrs.'</span> hours a week) is:</p"><p style="color: #209cee">'.$yrlHR.' hours a year</p>');
          echo("<p></p>");


          echo('');

          // echo('<a id="exitBtn" class="nes-btn is-error" href="exit.php">Exit</a>');

          echo('</div>');
          echo('</section>');

          // here we show the calculated data, we make an if statement, if percentage is less than 100% we display data, else, congraturation message.

          if ($currentPERC < 100) {

            echo('<section>');
            echo('<div id="dataContI">');
            echo('<p>- Current track to Mastery -</p>');
            echo('<p>Current percentage to Mastery: </p>'.'<span style="color: #209cee">'.$currentPERC.' %</span>');
            echo('<p>Percentage to Mastery left: </p>'.'<span style="color: #209cee">'.(100 - $currentPERC).' %</span>');
            echo('</div');

            echo('<div id="dataContII">');
            echo('<p>- After 1 more year - </p>');
            echo('<p>Current percentage to Mastery:</p>'.'<span style="color: #209cee">'.$percProgress.' %</span>');
            echo('<p>Percentage to Mastery left: </p>'.'<span style="color: #209cee">'.(100 - $percProgress).' %</span>');
            echo('</div');
            echo('</section>');

            echo('<div id="finalDataCont">');

            // to fix
            // to fix

            if (($counter > 1) && ($currentHrs > 1)) {
              echo('<p>At the current rate you will reach 10.000 hours in '.'<span style="color: #209cee">'.$counter.'</span>'.' years</p>');

            } elseif (($currentHrs < 1) && ($counter > 1)) {

              echo('<p>At the current rate you will reach 10.000 hours in '.'<span style="color: #209cee">'.$counter.'</span>'.' year</p>');


            } elseif (($currentHrs < 1) && isset($counter)) {

              echo('<p>With '.'<span style="color: #209cee">'.$currentHrs.'</span>'.' yearly hours, i dont think you will reach 10k anytime soon.</p>');

            }

            // to fix
            // to fix

            echo('<p>KEEP AT IT!!</p>');
            echo('</div');


            echo('<div id="exitButton">');
            echo('<a id="exitBtn" class="nes-btn is-error" href="exit.php">Exit</a>');
            echo('</div');




          } else {

            echo('<p class="congratzMex" style="color: #209cee">You Already reached 10.000 hours! <br> - CONGRATULATIONS! - </p>');
            echo('<a id="exitBtn" class="nes-btn is-error" href="exit.php">Exit</a>');
          }



        }


       ?>

     </div>
   </section>


     <p id="bottomNote">Reaching 10.000 hours in any skill is an amazing accomplishment, how far are you?</p>

     </section>


     <!-- <p>- Current track to Mastery -</p>
     <p>Current percentage to Mastery:</p>
     <p>Percentage to Mastery left: </p>


     <p>- After 1 more year - </p>
     <p>Current percentage to Mastery:</p>
     <p>Percentage to Mastery left: </p>

     <p>At the current rate you will reach 10.000 hours in   years</p>
     <p>KEEP AT IT</p> -->


   </body>
 </html>
