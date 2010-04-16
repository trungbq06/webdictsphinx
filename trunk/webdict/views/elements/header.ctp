<div id="header">
<?php
	if (!empty($_SESSION['username'])) 
		echo '<div class="hello">Xin chào <a href=""><span class="user_text">'.$_SESSION['username'].'</span></a></div>
		<div class="user_text"><a href="/users/listwords/'.$user_id.'">Từ vựng của tôi</a> | <a href="/users/logout">Thoát</a></div>';
	else echo '<div class="user_text"><a href="/users/login">Đăng nhập với Gmail</a></div>';
?>
</div>