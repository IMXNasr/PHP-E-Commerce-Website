<?php
    $pageTitle = 'Dashboard';
    session_start();
    if($_SESSION['id']){
      include "init.php";
    }else{
      header('Location: index.php');
      exit();
    }
?>
<?php include $temp . "footer.php";?>