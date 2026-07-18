<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" type="text/css" href="http://localhost/Easylogics/css/bootstrap.css"/>
	</head>
<body>
<form action="" method="POST">
<div class="col-md-3" style="border:solid"></div>
	<div class="col-md-6 well" style="border:solid">
			<img src="/Easylogics/About/showCaptcha" />
			<br />
			<br />
			<input type="text" name="captcha" />
			<?php
				if(ISSET($_POST['verify'])){
					if(!empty($_POST['captcha'])){
						if($_SESSION['captcha'] == $_POST['captcha']){
							echo "<label class='text-success'>Validated</label>";
						}else{
							echo "<label class='text-danger'>Invalid captcha!</label>";
						}
					}else{
						echo "<label class='text-warning'>Please fill up the required field!</label>";
					}
				}
			?>
			<br /><br />
			<button name="verify" class="btn btn-primary">Verify</button>
			
		</form>
		</div>
</body>
</html>