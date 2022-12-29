<?
	session_start();
	session_destroy();
	echo "<script>top.location.replace('index.html')</script>";
?>
