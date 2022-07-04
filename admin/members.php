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
    echo "This is Manage members ";
    echo "<a href='?page=add'>Add member</a>";
  }elseif($page == 'edit'){ // EDIT PAGE:
    $id = $_SESSION['id'];
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      // The data from the user
      $username = $_POST['username'];
      $full_name = $_POST['full_name'];
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
          $stmt = $db -> prepare("UPDATE users SET username = ?, full_name = ?, email = ? WHERE id = ?");
          $stmt -> execute([$username, $full_name, $email, $id]);
          $alert = $stmt -> rowCount() > 0 ? 'success' : 'none';
          $_SESSION['username'] = $username;
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
      <form class="form-horizontal" action="?page=edit" method="POST">
        <!-- START USERNAME -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">Username</label>
          <div class="col-sm-12">
            <input type="text" name="username" class="form-control" autocomplete="off" required value="<?=$rowAll['username']?>">
          </div>
        </div>
        <!-- END USERNAME -->
        <!-- START FULL NAME -->
        <div class="form-group">
          <label for="" class="col-sm-2 control-label">Full Name</label>
          <div class="col-sm-12">
            <input type="text" name="full_name" class="form-control" autocomplete="off" required value="<?=$rowAll['full_name']?>">
          </div>
        </div>
        <!-- END FULL NAME -->
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
    echo "This is add members";
  }else{
    header('Location: index.php');
    exit();
  }
  include $temp . "footer.php";