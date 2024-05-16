<?php
require_once 'google-api/vendor/autoload.php';
$gClient = new Google_Client();
$gClient->setClientId("548524798071-tnvepfata6n354cegqaon1vee05cm2cd.apps.googleusercontent.com");
$gClient->setClientSecret("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");
$gClient->setApplicationName("Login with Google");
$gClient->setRedirectUri("http://localhost/IPT101-Task-Reminder-System/google-login.php");
$gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");

$login_url = $gClient->createAuthUrl();
?>