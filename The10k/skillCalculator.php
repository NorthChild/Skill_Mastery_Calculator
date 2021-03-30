<?php  // code coming soon


session_start();

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {

  $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
  $stmt = $pdo -> prepare($sql);
  $stmt -> execute(array(
    ':name' => $_POST['name'],
    ':email' => $_POST['email'],
    ':password' => $_POST['password'],
  ));
  $_SESSION['success'] = 'Record Added';
  header('Location: CRUD.php');
  return;
}

?>
