<?php
  $pageTitle = 'Members';
  session_start();
  if($_SESSION['id']){
    include "init.php";
  }else{
    header('Location: index.php');
    exit();
  }
  $page = isset($_GET['page']) ? $_GET['page'] : 'manage';

  if($page == 'manage'){ // MANAGE PAGE:
    
  }elseif($page == 'edit'){ // EDIT PAGE:
    $id = $_SESSION['id'];
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $username = $_POST['username'];
      $full_name = $_POST['full_name'];
      $email = $_POST['email'];
      $password = sha1($_POST['password']);
      $stmt = $db -> prepare("SELECT password FROM users WHERE id = ?");
      $stmt -> execute([$id]);
      $row = $stmt -> fetch();
      if($row['password'] === $password){
        $stmt = $db -> prepare("UPDATE users SET username = ?, full_name = ?, email = ? WHERE id = ?");
        $stmt -> execute([$username, $full_name, $email, $id]);
        $_SESSION['username'] = $username;
        echo "Updated !!";
        header('Location: members.php');
        exit();
      }else{
        echo "Password is Wrong !!";
      }
    }
    $stmt = $db -> prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt -> execute([$id]);
    $row = $stmt -> fetch();
?>
    <h1 class="text-center">Edit member</h1>
    <div class="container">
      <form class="form-horizontal" action="?page=edit" method="POST">
        <!-- START USERNAME -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">Username</label>
          <div class="col-sm-12">
            <input type="text" name="username" class="form-control" autocomplete="off" required value="<?=$row['username']?>">
          </div>
        </div>
        <!-- END USERNAME -->
        <!-- START FULL NAME -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">Full Name</label>
          <div class="col-sm-12">
            <input type="text" name="full_name" class="form-control" autocomplete="off" required value="<?=$row['full_name']?>">
          </div>
        </div>
        <!-- END FULL NAME -->
        <!-- START EMAIL -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">Email</label>
          <div class="col-sm-12">
            <input type="email" name="email" class="form-control" autocomplete="off" required value="<?=$row['email']?>">
          </div>
        </div>
        <!-- END EMAIL -->
        <!-- START PASSWORD -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">Password</label>
          <div class="col-sm-12">
            <input type="password" name="password" class="form-control" autocomplete="off" required value="">
          </div>
        </div>
        <!-- END PASSWORD -->
        <!-- START SUBMIT -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-12">
            <input type="submit" class="btn btn-primary w-100 mt-3" value="Save">
          </div>
        </div>
        <!-- END SUBMIT -->
      </form>
    </div>
<?php
  }elseif($page == 'update'){ // EDIT PAGE:
    // if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //   $id = $_SESSION['id'];
    //   $username = $_POST['username'];
    //   $fullName = $_POST['full_name'];
    //   $email = $_POST['email'];
    //   $password = sha1($_POST['password']);
    //   $stmt = $db -> prepare("SELECT password FROM users WHERE id = ?");
    //   $stmt -> execute([$id]);
    //   $row = $stmt -> fetch();
    //   if($row['password'] === $password){
    //     $stmt = $db -> prepare("UPDATE users SET username = ?, full_name = ?, email = ? WHERE id = ?");
    //     $stmt -> execute([$username, $fullName, $email, $id]);
    //     $_SESSION['username'] = $username;
    //     header('Location: ?page=edit');
    //     exit();
    //   }else{
    //     echo "Password is wrong !!";
    //   }
    // }else{
    //   header('Location: ?page=edit');
    //   exit();
    // }
  }else{
    header('Location: index.php');
    exit();
  }