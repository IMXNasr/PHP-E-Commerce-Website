<?php
    $pageTitle = 'Login';
    include "init.php";
    session_start();
    if($_SESSION['id']){
      header('Location: dashboard.php');
      exit();
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $username = $_POST['username'];
      $password = $_POST['password'];
      $hashedPassword = sha1($password);
      // get the id of the user if exists
      $stmt = $db -> prepare("SELECT id, username FROM users WHERE username = ? AND password = ? AND admin = 1 LIMIT 1");
      $stmt->execute([$username, $hashedPassword]);
      $row = $stmt -> fetch();
      $count = $stmt -> rowCount();
      // if count > 0 this mean that database have this user
      if($count > 0){
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        header('Location: dashboard.php');
        exit();
      }else{
        echo "We don't have your account";
      }
    }
?>
    <form class="login" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
      <h4 class="text-center">Login</h4>
      <input class="form-control" type="text" name="username" placeholder="Username" autocomplete="off">
      <input class="form-control" type="password" name="password" placeholder="Password" autocomplete="off">
      <input class="btn btn-primary w-100" type="submit" value="Login">
    </form>
<?php include $temp . "footer.php";?>