<?php


session_start();
session_destroy();
unset($_SESSION['calculate']);
header('Location: index.php');


 ?>
