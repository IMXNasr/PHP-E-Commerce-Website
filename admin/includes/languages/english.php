<?php
    function lang($phrase){
      static $lang = [
        'Admin Dashboard' => 'Admin Dashboard',
        'Categories' => 'Categories',
        'Items' => 'Items',
        'Members' => 'Members',
        'Statistics' => 'Statistics',
        'Logs' => 'Logs',
        'Edit profile' => 'Edit profile',
        'Settings' => 'Settings',
        'Logout' => 'Logout',
      ];
      return $lang[$phrase];
    };