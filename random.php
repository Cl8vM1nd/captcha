<?php
session_start();
require('class/cCaptcha.php');

cCaptcha::generateImage($_SESSION['captcha']);

?>