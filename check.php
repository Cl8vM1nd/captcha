<?php
session_start();
require('class/cCaptcha.php');

$code = strip_tags(trim($_POST['code']));

if(empty($code)) die('error');

if(cCaptcha::checkCaptcha($code))
    echo 'true';
else
    echo 'false';
?>