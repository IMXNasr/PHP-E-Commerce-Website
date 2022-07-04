<?php
    session_start();
    $stmt = $db -> prepare("SELECT username FROM users WHERE id = ?");
    $stmt -> execute([$_SESSION['id']]);
    $rowUser = $stmt -> fetch();
?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="dashboard.php"><?=lang('Admin Dashboard')?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><?=lang('Categories')?></a></li>
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><?=lang('Items')?></a></li>
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="members.php"><?=lang('Members')?></a></li>
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><?=lang('Statistics')?></a></li>
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><?=lang('Logs')?></a></li>
          </ul>
          <ul class="navbar-nav mb-2 mb-lg-0">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?=$rowUser['username'];?>
              </a>
              <ul class="dropdown-menu my-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="members.php?page=edit"><?=lang('Edit profile')?></a></li>
                <li><a class="dropdown-item" href="#"><?=lang('Settings')?></a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="logout.php"><?=lang('Logout')?></a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
