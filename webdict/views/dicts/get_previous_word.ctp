<?php
	echo $this->element("search_box");
?>

<?php

if (!empty($result)) {
	$tmp = $result[0]['words'];
	$meaning = $tmp['meaning'];
	//echo $tmp['id'];
	//echo $meaning;
	
	if ($dict_id==1) 
		$meaning_arr = split('{', str_replace('*', '{', $meaning));
	// if ($dict_id==2) 
		// $meaning_arr = split('{', str_replace('*', '{', $meaning));
	// if ($dict_id==2) 
		// $meaning_arr = split('{', str_replace('*', '{', $meaning));
	// if ($dict_id==2) 
		// $meaning_arr = split('{', str_replace('*', '{', $meaning));
	// if ($dict_id==2) 
		// $meaning_arr = split('{', str_replace('*', '{', $meaning));
	// if ($dict_id==2) 
		// $meaning_arr = split('{', str_replace('*', '{', $meaning));
	// if ($dict_id==2) 
		// $meaning_arr = split('{', str_replace('*', '{', $meaning));
	$img_link = $tmp['img_link'];
	//echo $result[0]['words']['id'];
	$pronouce_link = $tmp['pronounce_link'];
	
	//echo $pronouce_link;
	echo "<br/>";
	echo "<div class='box' style='height: auto;'>";
	if ($meaning_arr[0]!='' && $dict_id!=2)
		echo "<div class='box_center'>[".$meaning_arr[0]."]";
		if ($pronouce_link != '') 
			echo '<span style="margin-top: 14px;text-align: center;"><object height="20" width="20" align="absmiddle" style="margin: 2px 0pt 0pt 10px; padding: 0pt; position: absolute;" id="player" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"><param value="sameDomain" name="allowScriptAccess">
						<param value="/player.swf" name="movie">
						<param value="high" name="quality">
						<param value="#eeeeee" name="bgcolor">
						<param value="file='.$pronouce_link.'&amp;autolaunch=true" name="FlashVars">
						<embed height="30" width="30" align="middle" viewastext="true" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowscriptaccess="sameDomain" name="player" bgcolor="#eeeeee" quality="high" flashvars="file='.$pronouce_link.'&amp;autolaunch=true" src="/player.swf"></object></span>';
		echo "</div>";
	echo "<div class='box_center1'>".$search_term."</div>";
	echo "</div>";
	
	//echo "<div style='padding-bottom: 10px;float: left;padding-left: 25px;padding-top: 10px;font-size: 14px;'><a href=''>Phát âm từ này</a></div>";
	echo "<div style='padding-bottom: 10px;padding-left: 25px; display: inline;padding-top: 10px;font-size: 14px;float: left; '><a href='/users/addword/".$tmp['id']."'><img src='/images/add.jpg' border=0 width='20' height='20' style='vertical-align: middle;'/>Thêm vào từ vựng của tôi</a></div>";
	echo "<br/><br/>";
	//$tmp_arr = split('-', $meaning_arr[1]);
	//print_r($tmp_arr);
	echo "<div class='box' style='height: auto;margin-top: 10px;'>";
	echo "<div class='meaning' style='height: auto;'>";
	$start = 1;
	if ($dict_id!=1) $start = 0;
	if (!empty($meaning_arr)) {
		for ($i=$start;$i < count($meaning_arr);$i++) {
			$tmp_arr = split('-', $meaning_arr[$i]);
			
			echo "<b>".$tmp_arr[0]."</b>";
			//Còn lỗi từ Ni-a-ga-ra. Bị cắt thành nhiều dòng.
			for ($j = 1;$j < count($tmp_arr);$j++) {
				echo "<div style='padding-top:5px;'> - ";
				if (strlen($tmp_arr[$j]) < 5)
					echo $tmp_arr[$j];
				else {
					echo $tmp_arr[$j];
					echo "</div>";
				}
			}
			echo "<br/>";
			//echo $meaning_arr[$i]."<br/><br/>";
		}
	} else {
		echo "<div style='padding-top:5px;'> - ";
		echo $meaning;
		echo "</div>";
	}

	echo "</div></div>";
	$img_link = str_replace(",http://", "'/><img src='http://", $img_link);
	echo "<div style='clear:both;'/>";
	if ($img_link!='') 
		echo "<div class='box' style='text-align:center; height: auto; margin-top: 15px;'><img src='".$img_link."/></div>";
}

echo $isPlaying;

?>

<script type="text/javascript">

alert(document.getElementById("play_button").innerHTML);
if (document.getElementById("play_button").innerHTML == '<img src="/images/pause.jpg" border="0">') {
	setTimeout("getWord()", 32000);
	}
	
</script>