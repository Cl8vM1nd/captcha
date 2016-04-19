$(document).ready(function() {
	$('#update').click(function(e) {
		$('#capcha-image').attr('src', 'captcha.php?rid=' + Math.random());
	});

	$('#reload').click(function(e) {
		$('#capcha-image').attr('src', 'random.php');
	});

	$('#captchaF').on('submit', function(e) {
		e.preventDefault();
		 $.ajax({
		   type: "POST",
		   url: "check.php",
		   data: "code=" + $('#code').val(),
		   success: function(data){
		   if(data == 'true')
		     	alert('Капча введена верно!Спасибо.')
		     else
		     	alert('Капча введена не верно. Попробуйте снова!');
		   }
		 });
	});
});