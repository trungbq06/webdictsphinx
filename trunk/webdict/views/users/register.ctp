<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />				
		<title>Web Dictionary</title>		
		<link rel="stylesheet" href="/css/style.css" type="text/css" />
		<script type="text/javascript" language="javascript" src="/jquery.js"></script>
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
	<div id="word_content">
	<?php
		echo $this->element("search_box");
	?>
	
	<div class='box_register' style='height: auto;'>
		<form action="/users/register" method="post">
		<table border=0>
			<tr><td>Gmail: </td><td><input type="text" name="username" style="width: 250px;"/></td></tr>
			<tr><td>Password: </td><td><input type="password" name="password" style="width: 250px;"/></td></tr>
			<tr><td>Giới tính: </td><td>Nam<input type="radio" name="gender" value="1" />Nữ<input type="radio" name="gender" value="0" /></td></tr>
			<tr><td colspan=2><input type="submit" value="Đăng ký" name="submit" /></td></tr>
			<tr><td colspan=2><font color='red'><?php echo $mess; ?></font></td></tr>
		</table>
		</form>
	</div>
	
	</div>
</div>
</center>
</body>
</html>