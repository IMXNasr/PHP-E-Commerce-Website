<?php
    function getTitle(){
      global $pageTitle;
      if(isset($pageTitle)){
        echo $pageTitle;
      }else{
        echo 'Default';
      }
    }
    // function ifUserIsExistRedirectTo($path){
    //   session_start();
    //   if($_SESSION['id']){
    //     header()
    //   }
    // }
    function countItems($item, $table){
      global $db;
      $stmt = $db -> prepare("SELECT COUNT($item) FROM $table");
      $stmt -> execute();
      return $stmt -> fetchColumn(); 
    }