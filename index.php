<?
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>	

<img src='captcha.php' id='capcha-image'>
</br>
<button id="update">Обновить капчу</button></br>
<button id="reload">Не вижу</button></br>
</br>
<span>Введите капчу:</span>

<form id="captchaF">
<input type="text" id="code" />
<input type="submit" value="Продолжить" />
</form>

<script type="text/javascript" src='js/jquery-1.11.2.min.js'></script>
<script type="text/javascript" src='js/ajax.js'></script>
</body>
</html>