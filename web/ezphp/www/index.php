<?php
	session_start();
	if ($_SESSION['islogin'] !== "1") {
		header("location:login.php");
	}

	$username=$_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Mine-Sweep</title>
	<link href="Css/bootstrap.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="Js/jquery.js"></script>
	<style type="text/css">
		.reply_btn{
			border:none;
			background:none;
			color:#337ab7;
		}
		.reply_btn:hover{
			border:none;
			cursor: pointer;
			text-decoration: underline;
			color: #ba1a09;
		}
	</style>
</head>
<body>
	<div align="right" style="padding:10px 50px;background:#00BFFF;">
		<h6 style="height:60px;line-height:60px;color:#fff;">
		<a href="scoreboard.php" style="color:#fff;">排行榜</a>&nbsp;&nbsp;
		<a href="info.php" style="color:#fff;"><?php echo $username; ?></a>&nbsp;<span>&nbsp;&nbsp;&nbsp;<a href="action.php?a=quit" style="color:#fff;">退出登录</a></span></h6>
	</div>
	<div class="container">
		<h3 align="center">Mine-Sweep</h3>
	</div>
	<br />
	<?php
	include("game.php");
	?>
</body>
</html>
