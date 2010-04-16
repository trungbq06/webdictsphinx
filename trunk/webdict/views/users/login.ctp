<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />				
		<title>Web Dictionary</title>		
		<link rel="stylesheet" href="/css/style.css" type="text/css" />
		<script type="text/javascript" language="javascript" src="/Resource/00/ArrMenu.js"></script>
</head>
<body>
<center>
<div id="container">
	<?php
		echo $this->element("header");
	?>
	<?php
		echo $this->element("menu_home");
	?>
	
	<?php
		echo $this->element("search_box");
	?>
	
	<div class="login">
	<form action="/users/login" method="post">
		<div class="input"><span class="span_input">Gmail: </span><input class="long_text" type="text" name="username" /></div>
		<div class="input"><span class="span_input">Password: </span><input class="long_text" type="password" name="password" /></div>
		<div class="input"><input type="submit" name="submit" value="Đăng nhập" /></div>
	</form>
	</div>
	
</div>
</center>
</body>
</html>