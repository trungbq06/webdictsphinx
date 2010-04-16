<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />				
		<title>Web Dictionary</title>		
		<link rel="stylesheet" href="/css/style.css" type="text/css" />
		<script type="text/javascript" language="javascript" src="/jquery.js"></script>
</head>
<body>
	<?php
	//print_r($result);
	if (!empty($result)) {
		$tmp = $result[0]['words'];
		$meaning = $tmp['meaning'];
		//echo $tmp['id'];
		//echo $meaning;
		
		if ($dict_id==1) 
			$meaning_arr = split('{', str_replace('*', '{', $meaning));
		$img_link = $tmp['img_link'];
		//echo $result[0]['words']['id'];
		$pronouce_link = $tmp['pronounce_link'];
		
		//echo $pronouce_link;
		echo "<div class='' style='height: auto;'>";
		if ($meaning_arr[0]!='' && $dict_id!=2) {
			echo "<div class='highlight_title'>[".$meaning_arr[0]."]";
			if ($pronouce_link != '') 
				echo '<span style="margin-top: 14px;text-align: center;"><object height="10" width="10" align="absmiddle" style="margin: 2px 0pt 0pt 10px; padding: 0pt; position: absolute;" id="player" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"><param value="sameDomain" name="allowScriptAccess">
							<param value="/player.swf" name="movie">
							<param value="high" name="quality">
							<param value="#eeeeee" name="bgcolor">
							<param value="file='.$pronouce_link.'&amp;autolaunch=true" name="FlashVars">
							<embed height="18" width="18" align="middle" viewastext="true" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowscriptaccess="sameDomain" name="player" bgcolor="#eeeeee" quality="high" flashvars="file='.$pronouce_link.'&amp;autolaunch=true" src="/player.swf"></object></span>';
			echo "</div>";
		}
		echo "<div class='highlight_title'>".$tmp['name']."</div>";
		echo "</div>";
		
		//echo "<div style='padding-bottom: 10px;float: left;padding-left: 25px;padding-top: 10px;font-size: 14px;'><a href=''>Phát âm từ này</a></div>";
		if (empty($checkWord)) {
			echo "<div style='padding-top: 10px;font-size: 11px;text-align: left;' id='addwordhighlight'><span>";
			echo "<a href='javascript:addwordhighlight_1(".$tmp['id'].");'><img src='/images/add.jpg' border=0 width='20' height='20' style='vertical-align: middle;'/>Thêm vào từ vựng của tôi</a>";
			echo "</span></div>";
		}
		//echo "<div style='text-align: left;'><span><a href='javascript:addwordhighlight(".$tmp['id'].");'><img src='/images/add.jpg' border=0 width='20' height='20' style='vertical-align: middle;'/>Thêm vào từ vựng của tôi</a></span></div>";
		//echo "<br/><br/>";
		//$tmp_arr = split('-', $meaning_arr[1]);
		//print_r($tmp_arr);
		echo "<div class='highlight_meaning' style='height: auto;margin-top: 2px;'>";
		//echo "<div class='highlight_meaning' style='height: auto;'>";
		$start = 1;
		if ($dict_id!=1) $start = 0;
		if (!empty($meaning_arr)) {
			for ($i=$start;$i < count($meaning_arr);$i++) {
				$tmp_arr = split('-', $meaning_arr[$i]);
				
				//echo "<div style='text-align: left;'><b>".$tmp_arr[0]."</b></div>";
				echo "<b>".$tmp_arr[0]."</b>";
				//echo "trung";
				//Còn lỗi từ Ni-a-ga-ra. Bị cắt thành nhiều dòng.
				for ($j = 1;$j < count($tmp_arr);$j++) {
					echo "<div style='padding-top:5px;padding-bottom: 5px;'> - ";
					if (strlen($tmp_arr[$j]) < 5)
						echo $tmp_arr[$j];
					else {
						echo $tmp_arr[$j];
						echo "</div>";
					}
				}
				//echo "<br/>";
				//echo $meaning_arr[$i]."<br/><br/>";
			}
		} else {
			echo "<div style='padding-top:5px;'> - ";
			echo $meaning;
			echo "</div>";
		}
		
		 echo "</div>";
		// $img_arr = split(",", $img_link);
		// $img_link = '';
		// for ($i = 0;$i < 6;$i++) {
			// $img_link .= $img_arr[$i].",";
		// }
		// $img_link = substr($img_link, 0, strlen($img_link)-1);
		// $img_link = str_replace(",http://", "'/><img class='picture_box' src='http://", $img_link);
		// echo "<div style='clear:both;'/>";
		// if ($tmp['img_link']!='') 
			// echo "<div class='box' style='text-align:center; height: auto; margin-top: 15px;'><img class='picture_box' src='".$img_link."'/></div>";
	}
	
	?>

<script type='text/javascript'>
var user_id = '';
user_id = <?php echo $user_id; ?>;
function addwordhighlight_1(word_id) {
	if (user_id!='-1') {
		addwordhighlight(word_id);		
	}
	else alert('Vui lòng đăng nhập!');
}

function addwordhighlight(word_id) {
	//alert("going here...");
    $.get('/users/addword/' + word_id,
		function(data) {
		  $('#addwordhighlight').html(data);
		  //if (isPlaying)
			//alertTimerId = setTimeout("getWord(" + <?php echo $result[0]['words']['id']; ?> + ")", 3200);
		}	
    )
}

</script>

</body>
</html>