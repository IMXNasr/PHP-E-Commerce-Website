<?php
    $pageTitle = 'Dashboard';
    session_start();
    if($_SESSION['id']){
      include "init.php";
?>
    <div class="container text-center home-stats">
      <h1>Dashboard</h1>
      <div class="row">
        <div class="col-md-3">
          <div class="stat">Total Members <span><?=countItems("id", "users")?></span></div>
        </div>
        <div class="col-md-3">
          <div class="stat">Pending Members <span>25</span></div>
        </div>
        <div class="col-md-3">
          <div class="stat">Total Items <span>63</span></div>
        </div>
        <div class="col-md-3">
          <div class="stat">Total Comments <span>1003</span></div>
        </div>
      </div>
    </div>
    <div class="container latest">
      <div class="row">
        <div class="col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <i class="fa fa-users"></i> Latest Users
            </div>
            <div class="panel-body">test</div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <i class="fa fa-tag"></i> Latest Items
            </div>
            <div class="panel-body">test</div>
          </div>
        </div>
      </div>
    </div>
<?php  }else{
      header('Location: index.php');
      exit();
    }
?>
<?php include $temp . "footer.php";?>