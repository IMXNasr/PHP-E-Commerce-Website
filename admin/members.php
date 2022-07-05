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
    $stmt = $db -> prepare("SELECT * FROM users");
    $stmt -> execute();
    $rows = $stmt -> fetchAll();
?>
    <h1 class="text-center">Manage members</h1>
    <div class="container">
      <div class="table-responsive">
        <table class="table table-bordered main-table">
          <tr>
            <th>Id</th>
            <th>Username</th>
            <th>Name</th>
            <th>Email</th>
            <th>Control</th>
          </tr>
          <?php foreach($rows as $row){ ?>
          <tr>
            <td><?=$row['id']?></td>
            <td><?=$row['username']?></td>
            <td><?=$row['name']?></td>
            <td><?=$row['email']?></td>
            <td>
              <a href="?page=edit&id=<?=$row['id']?>" class="btn btn-warning">Edit</a>
              <a href="?page=delete&id=<?=$row['id']?>" class="btn btn-danger">Delete</a>
            </td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <a href="?page=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add new member</a>
    </div>
<?php }elseif($page == 'edit'){ // EDIT PAGE:
    $id = $_GET['id'];
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      // The data from the user
      $username = $_POST['username'];
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = sha1($_POST['password']);
      // Get the password of the current user
      $stmt = $db -> prepare("SELECT password FROM users WHERE id = ?");
      $stmt -> execute([$id]);
      $row = $stmt -> fetch();
      // Get the users with the same $username
      $stmtUsername = $db -> prepare("SELECT username FROM users WHERE NOT id = ? AND username = ?");
      $stmtUsername -> execute([$id, $username]);
      $countUsername = $stmtUsername -> rowCount();
      // Get the users with the same $email
      $stmtEmail = $db -> prepare("SELECT email FROM users WHERE NOT id = ? AND email = ?");
      $stmtEmail -> execute([$id, $email]);
      $countEmail = $stmtEmail -> rowCount();
      // alert type
      $alert = '';
      // Check if the password is correct
      if($row['password'] === $password){
        // Check if there are username
        if($countUsername <= 0 && $countEmail <= 0){
          $stmt = $db -> prepare("UPDATE users SET username = ?, name = ?, email = ? WHERE id = ?");
          $stmt -> execute([$username, $name, $email, $id]);
          $alert = $stmt -> rowCount() > 0 ? 'success' : 'none';
          if($id === $_SESSION['id']){
            $_SESSION['username'] = $username;
          }
        }elseif($countUsername > 0){
          $alert = 'username';
        }elseif($countEmail > 0){
          $alert = 'email';
        }
      }else{
        $alert = 'password';
      }
    }
    $stmt = $db -> prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $stmt -> execute([$id]);
    $rowAll = $stmt -> fetch();
    include $temp . "navbar.php";
?>
    <h1 class="text-center">Edit member</h1>
    <div class="container">
    <?php if($alert === 'success') { ?>
      <div class="alert alert-success" role="alert">
        Updated successfully !!
      </div>
    <?php } ?>
    <?php if($alert === 'none') { ?>
      <div class="alert alert-secondary" role="alert">
        Nothing changed
      </div>
    <?php } ?>
    <?php if($alert === 'username') { ?>
      <div class="alert alert-danger" role="alert">
        Username is already exists !!
      </div>
      <?php } ?>
      <?php if($alert === 'email') { ?>
        <div class="alert alert-danger" role="alert">
        Email is already exists !!
      </div>
    <?php } ?>
      <?php if($alert === 'password') { ?>
        <div class="alert alert-danger" role="alert">
        Password is wrong !!
      </div>
    <?php } ?>
      <form class="form-horizontal" action="?page=edit&id=<?=$_GET['id']?>" method="POST">
        <!-- START USERNAME -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">Username</label>
          <div class="col-sm-12">
            <input type="text" name="username" class="form-control" autocomplete="off" required value="<?=$rowAll['username']?>">
          </div>
        </div>
        <!-- END USERNAME -->
        <!-- START NAME -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">Name</label>
          <div class="col-sm-12">
            <input type="text" name="name" class="form-control" autocomplete="off" required value="<?=$rowAll['name']?>">
          </div>
        </div>
        <!-- END NAME -->
        <!-- START EMAIL -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">Email</label>
          <div class="col-sm-12">
            <input type="email" name="email" class="form-control" autocomplete="off" required value="<?=$rowAll['email']?>">
          </div>
        </div>
        <!-- END EMAIL -->
        <!-- START PASSWORD -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">Confirm Password</label>
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
  }elseif($page == 'add'){ // ADD PAGE:
    $id = $_SESSION['id'];
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      // The data from the user
      $username = $_POST['username'];
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = sha1($_POST['password']);
      // Get the password of the current user
      $stmtGetUsername = $db -> prepare("SELECT username FROM users WHERE username = ? LIMIT 1");
      $stmtGetUsername -> execute([$username]);
      $stmtGetEmail = $db -> prepare("SELECT email FROM users WHERE email = ? LIMIT 1");
      $stmtGetEmail -> execute([$email]);
      $alert = '';
      // Insert new user if there is no user with the same username and email
      if($stmtGetUsername -> rowCount() <= 0 && $stmtGetEmail -> rowCount() <= 0){
        $stmt = $db -> prepare("INSERT INTO users (username, name, email, password) VALUES (?, ?, ?, ?)");
        $stmt -> execute([$username, $name, $email, $password]);
        if($stmt -> rowCount() > 0){
          $alert = 'success';
          $username = '';
          $name = '';
          $email = '';
          $password = '';
        }
      }elseif($stmtGetUsername -> rowCount() > 0){
        $alert = 'username';
      }elseif($stmtGetEmail -> rowCount() > 0){
        $alert = 'email';
      }
    }
?>
    <h1 class="text-center">Add member</h1>
    <div class="container">
    <?php if($alert === 'success') { ?>
      <div class="alert alert-success" role="alert">
        User created successfully !!
      </div>
    <?php } ?>
    <?php if($alert === 'username') { ?>
      <div class="alert alert-danger" role="alert">
        Username is already exists !!
      </div>
      <?php } ?>
      <?php if($alert === 'email') { ?>
        <div class="alert alert-danger" role="alert">
        Email is already exists !!
      </div>
    <?php } ?>
    <?php if($alert === 'password') { ?>
        <div class="alert alert-danger" role="alert">
        Password is wrong !!
      </div>
    <?php } ?>
      <form class="form-horizontal" action="?page=add" method="POST">
        <!-- START USERNAME -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">Username</label>
          <div class="col-sm-12">
            <input type="text" name="username" class="form-control" autocomplete="off" required value="<?=$username?>">
          </div>
        </div>
        <!-- END USERNAME -->
        <!-- START NAME -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">Name</label>
          <div class="col-sm-12">
            <input type="text" name="name" class="form-control" autocomplete="off" required value="<?=$name?>">
          </div>
        </div>
        <!-- END NAME -->
        <!-- START EMAIL -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">Email</label>
          <div class="col-sm-12">
            <input type="email" name="email" class="form-control" autocomplete="off" required value="<?=$email?>">
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
            <input type="submit" class="btn btn-primary w-100 mt-3" value="Add">
          </div>
        </div>
        <!-- END SUBMIT -->
      </form>
    </div>
<?php
  }elseif($page === 'delete'){
    $id = $_GET['id'];
    if($_SERVER['REQUEST_METHOD'] === 'POST' && $id && is_numeric($id)){
      $stmt = $db -> prepare("DELETE FROM users WHERE id = ?");
      $stmt -> execute([$id]);
      // session_start();
      if($stmt -> rowCount() > 0){
        if((int)$id === (int)$_SESSION['id']){
          header('Location: logout.php');
          exit();
        }else{
          header('Location: members.php');
          exit();
        }
      }
    }
    elseif($id && is_numeric($id)){
      $stmt = $db -> prepare("SELECT username FROM users WHERE id = ? LIMIT 1");
      $stmt -> execute([$id]);
      if($stmt -> rowCount() > 0){
        $username = $stmt -> fetch()['username'];
?>
    <h1 class="text-center">Are you sure you wanna delete <strong><?=$username?></strong> ?</h1>
    <form class="delete-form" action="?page=delete&id=<?=$id?>" method="POST">
      <a class="btn btn-warning" href="members.php">Cancel</a>
      <button class="btn btn-danger" type="submit">Delete</button>
    </form>
<?php
      }else{
        header('Location: members.php');
        exit();
      }
    }else{
      header('Location: members.php');
      exit();
    }
  }
  include $temp . "footer.php";