<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />				
		<title>Web Dictionary</title>		
		<link rel="stylesheet" href="/css/style.css" type="text/css" />
		<script type="text/javascript" language="javascript" src="/jquery.js"></script>
		<script type="text/javascript" src="/js/ajax-dynamic-content.js"></script>
		<script type="text/javascript" src="/js/ajax.js"></script>
		<script type="text/javascript" src="/js/ajax-tooltip.js"></script>
		<link rel="stylesheet" href="/css/ajax-tooltip.css" media="screen" type="text/css" />
		<!--<link rel="stylesheet" href="/css/ajax-tooltip-demo.css" media="screen" type="text/css" />-->
</head>
<body>
<center>
<div id="container">
	<?php
		echo $this->element("header");
	?>
	<?php
		echo $this->element("menu_docbao");
	?>
	<div style="float: left;">
	<div class="news_cat">
	<?php
	foreach ($categories as $category) {
		echo "<div class='news_cat_item'><a href='/dicts/read/".$category['news_categories']['id']."/-1/1' style='";
		if ($category_id==$category['news_categories']['id']) echo " color: red;font-weight: bold;";
		echo "'>".$category['news_categories']['name']."</a></div>";
	}
	?>
	</div>
	
	<div class="news_content">
	
	<?php
	if (!empty($content)) {
		echo "<div class='news_title'>".$content[0]['news_content']['title']."</div>";
		echo "<div class='news_content_detail'>".$content[0]['news_content']['content']."</div>";
		// echo "<div class='meaning-bottom'>";
		// for ($i = 0;$i < count($collection);$i++) {
			// echo "<span style='font-weight: bold;'>".$collection[$i]['name'].":</span> ".$collection[$i]['meaning']."<br/>";
		// }
		// echo "</div>";
	} else {
		foreach ($resultList as $result) {
			echo "<div class='news_title'><a href='/dicts/read/".$result['news_content']['category_id']."/".$result['news_content']['id']."'>".$result['news_content']['title']."</a></div>";
			echo "<div class='news_created'>";
			
			$end = time();
			$start = strtotime($result['news_content']['created']);
			$timediff = $end - $start;
			$days=intval($timediff/86400);
			$remain=$timediff%86400;
			$hours=intval($remain/3600) - 1;
			$remain=$remain%3600;
			$mins=intval($remain/60);
			$secs=$remain%60;

			if ($days < 5)
			{
			 $timediff = '';
			 if ($days > 0)
				$timediff .= $days .' ngày ';
			 if ($hours > 0)
				$timediff .= $hours.' giờ ';
			 $timediff .= $mins.' phút';

			 echo "Cập nhật cách đây ".$timediff;
			} else echo $result['news_content']['created'];
			
			echo "<div class='news_source'>Nguồn: ".$result['news_content']['domain_name']."</div></div>";
			echo "<div class='news_description'>".$result['news_content']['description']."</div><br/>";
			// echo "<div class='news_title'>".$result['news_content']['title']."</div>";
			// echo "<div class='news_title'>".$result['news_content']['title']."</div>";
			// echo "<div class='news_title'>".$result['news_content']['title']."</div>";
			// echo "<div class='news_title'>".$result['news_content']['title']."</div>";
		}
	}
	
	if ($totalPage != 0) {
		$beginPage = $page - 3;
		$endPage = $page + 4;
		if ($beginPage < 1) $beginPage = 1;
		if ($endPage > $totalPage) $endPage = $totalPage;
		echo "<div class='paginate'>Trang: ";
	    
	    // if ($beginPage > 1)
	        // echo '<a href="/chuyenla/view/-1/'.($beginPage-1).'"><<</a> ';
	    // for ($i = 0; $i < 5; ++$i) {
	        // $nPage = $beginPage + $i;
	        // if ($nPage > $totalPage)
	            // break;
	        // else
	            // if ($nPage != $page)
	                // echo '<a href="/chuyenla/view/-1/'.$nPage.'">'.$nPage.'</a> ';
	            // else
	                // echo $page.' ';
	    // }
	    // if ($nPage < $totalPage)
	        // echo '<a href="/chuyenla/view/-1/'.($nPage+1).'">>></a> ';
		// echo "</div>";
		if ($beginPage > 1) 
			echo "<a href='/dicts/read/".$category_id."/-1/1'><< </a>";
		for ($i = $beginPage;$i <= $endPage;$i++) {
			if ($i != $page)
				echo "<a href='/dicts/read/".$category_id."/-1/".$i."'>".$i." </a>";
			else echo "<span style='color: red;font-weight: bold;'>".$page."</span> ";
		}
		if ($i < $totalPage)
			echo "<a href='/dicts/read/".$category_id."/-1/".$totalPage."'>>></a>";
	}
	
	?>
		
	</div>
	</div>
</div>
</center>

<script type='text/javascript'>

function search_highlight(search_term) {
	$.get('/dicts/searchhighlight/' + search_term,
		function(data) {
		  $('#addword').html(data);
		  //if (isPlaying)
			//alertTimerId = setTimeout("getWord(" + <?php echo $result[0]['words']['id']; ?> + ")", 3200);
		}	
    )
}

</script>

</body>
</html>