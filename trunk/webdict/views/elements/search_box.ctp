<div class="box" id="box_search">
	<div class="searchBox">
	<form action="/dicts/search" method="post" name="dict_form">
		<select name="dict_id" class="input_text" style="margin-top: 2px;">
			<?php
			
			for ($i = 0;$i < count($dicts);$i++) {
				echo '<option value="'.$dicts[$i]['dictionaries']['id'].'"';
				if ($dict_id == $dicts[$i]['dictionaries']['id']) echo " selected";
				echo '>'.$dicts[$i]['dictionaries']['name'].'</option>';
			}
			
			?>
		</select>
		<input type="text" name="search_term" class="input_text" value="<?php echo $search_term; ?>" style="margin-left: 5px;" /><img src="/images/submit.jpg" class="input_submit" border=0 onclick="dict_form.submit()"/>
	</form>
	</div>
	<div class="action_img">
		<div class="play"><a href="javascript:getNextWord(<?php echo $dict_id;?>, <?php echo $result[0]['words']['id']; ?>, 0, 0);"><img src="/images/previous.jpg" border="0"/></a></div>
		<a href="javascript:startPlaying(<?php echo $dict_id;?>, <?php echo $result[0]['words']['id']; ?>, 1);"><div id="play_button"><img src="/images/<?php if ($isPlaying) echo "pause.jpg";
		else echo "play.jpg";
		?>" border="0"/></div></a>
		<div class="play"><a href="javascript:getNextWord(<?php echo $dict_id;?>, <?php echo $result[0]['words']['id']; ?>, 1, 0);"><img src="/images/next.jpg" border="0"/></a></div>
	</div>
</div>