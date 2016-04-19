<?php
session_start();
error_reporting(E_ALL);

require('class/cCaptcha.php');
$captcha = cCaptcha::generate();

$_SESSION['captcha'] = strtolower($captcha);

cCaptcha::generateImage($captcha);

?>