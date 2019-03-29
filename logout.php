<?php
//oturumu başlat
session_start();
 
//Tüm oturum değişkenlerini 
$_SESSION = array();
 
// Oturumu yok et!
session_destroy();
 
// Login sayfasına yölendirir
header("location: login.php");
exit;
?>