<?php

class Dict extends AppModel {
	var $name = "Dict";
	var $useTable = false;
	//var $components = array('lucene');
	
	function getMirror($string = "")
	{
		return $this->lucene->query($string);
	}
	
	function getnext_previous($dict_id, $id, $isNext) {
		if ($isNext) {
			$result = $this->query("select name, meaning, img_link, pronounce_link, id from words where id > $id and dictionary_id=$dict_id order by id limit 0,1");
			//print_r($result);
		} else {
			$result = $this->query("select name, meaning, img_link, pronounce_link, id from words where id < $id and dictionary_id=$dict_id order by id desc limit 0,1");
			//print_r($result);
		}
		
		return $result;
	}
	
	function searchdict($search, $suffix='')
	{
		//echo "vao day";
		require_once("sphinxapi.php");
		$sp = new SphinxClient();
		$sp->SetServer("127.0.0.1", 9312);
		$sp->SetConnectTimeout(5);
		//$sp->SetWeights(array(10, 5, 1));
		$sp->SetMatchMode(SPH_MATCH_EXTENDED2);
        $sp->SetRankingMode(SPH_RANK_PROXIMITY_BM25);
        $sp->SetSortMode(SPH_SORT_RELEVANCE);
		//$dictId = 2;
		//$sp->SetFilter("dictionary_id",array(0=>$dictId));
		$sp->SetFilter("dictionary_id", array($search['dict_id']));
		//$sp->SetFilter("dictionary_id", array(1));
		$sp->SetArrayResult(true);
		//echo strtolower("Advertent");
		//$search['search_term'] = "Advertent";
		$temp = $search['search_term'];
		$temp = str_replace("-", "", $temp);
		$temp = str_replace("_", "", $temp);
		$term = $this->convertToSphinx($temp);
		//$resultList = $sp->Query("@(name_search)giAIo HLAMo", "words");
		$resultList = $sp->Query("@(name_search)".$term, "words");
		//print_r($resultList);
		//echo $search['search_term'];
		$id = '';
		if ($resultList['matches']!=0) {
			for ($i = 0;$i < count($resultList['matches']);$i++) {
				//echo "select name from words where id = ".$resultList['matches'][$i]['id']."";
				$tmpResult = $this->query("select name from words where id = ".$resultList['matches'][$i]['id']."");
				//print_r($tmpResult);
				if ($tmpResult[0]['words']['name'] == $search['search_term']) {
					$id = $resultList['matches'][$i]['id'];
					break;
				}
			}
		}
		//Nếu không tìm thấy, cắt bỏ dần các ký tự cuối theo quy tắc cho tới khi tìm thấy.
		if ($id=='') {			
			$tmp = $search['search_term'];
			if (substr($tmp, strlen($tmp)-1, 1) == "s") {
				$search['search_term'] = substr($tmp, 0, strlen($tmp)-1);
				return $this->searchdict($search);
			//Cắt bỏ một ký tự s
			} else if (substr($tmp, strlen($tmp)-1, 1) == "e") {
				$search['search_term'] = substr($tmp, 0, strlen($tmp)-1);
				return $this->searchdict($search);
			//Cắt bỏ một ký tự i
			} else if (substr($tmp, strlen($tmp)-1, 1) == "i") {
				$search['search_term'] = substr($tmp, 0, strlen($tmp)-1)."y";
				return $this->searchdict($search);
			//Cắt bỏ một ký tự d
			} else if (substr($tmp, strlen($tmp)-1, 1) == "d") {
				$search['search_term'] = substr($tmp, 0, strlen($tmp)-1);
				return $this->searchdict($search);
			//Cắt bỏ một ký tự e
			} else if (substr($tmp, strlen($tmp)-1, 1) == "e") {
				$search['search_term'] = substr($tmp, 0, strlen($tmp)-1);
				return $this->searchdict($search);
			//Cắt bỏ 3 ký tự ing
			} else if (substr($tmp, strlen($tmp)-3, 3) == "ing") {
				$search['search_term'] = substr($tmp, 0, strlen($tmp)-3);
				return $this->searchdict($search);
			//Cắt bỏ 2 ký tự er
			} else if (substr($tmp, strlen($tmp)-2, 2) == "er") {
				$search['search_term'] = substr($tmp, 0, strlen($tmp)-2);
				return $this->searchdict($search);
			//Cắt bỏ 2 ký tự ly
			} else if (substr($tmp, strlen($tmp)-2, 2) == "ly") {
				$search['search_term'] = substr($tmp, 0, strlen($tmp)-2);
				return $this->searchdict($search);
			//Cắt bỏ 2 ký tự st
			} else if (substr($tmp, strlen($tmp)-2, 2) == "st") {
				$search['search_term'] = substr($tmp, 0, strlen($tmp)-2);
				return $this->searchdict($search);
			}
		}
		
		$resultList = $this->query("select meaning, img_link, pronounce_link, id, name from words where id=$id");
		return $resultList;
	}
	
	function getNewsCategory() {
		return $this->query("select name, id from news_categories");
	}
	
	function checkArray($str, $array) {
		echo $str."xuong";
		for ($i = 0;$i < count($array);$i++) {
			echo $array[$i]."<br/>";
			if ($array[$i] == $str) return true;			
		}
		return false;
	}
	
	function findPos($str, $find, $pos) {
		$tmp_pos = strpos($str, $find, $pos);
		if ($tmp_pos!==false) {
			if ((substr($str, $tmp_pos-1, 1) == " " || substr($str, $tmp_pos-1, 1) == ">" || substr($str, $tmp_pos-1, 1) == "'" || substr($str, $tmp_pos-1, 1) == '"') && (substr($str, $tmp_pos+strlen($find), 1) == " " || substr($str, $tmp_pos+strlen($find), 1) == "." || substr($str, $tmp_pos+strlen($find), 1) == ',' || substr($str, $tmp_pos+strlen($find), 1) == '"' || substr($str, $tmp_pos+strlen($find), 1) == "'"))
				return $tmp_pos;
			else return $this->findPos($str, $find, $tmp_pos+1);
		}
		return $tmp_pos;
	}
	
	function collectWord($content) {
		$word_array = $this->getWordOfUser($_SESSION['user_id']);
		$known_word = $this->getKnownWord();
		$temp_content = $content[0]['news_content']['content'];
		//$temp_content = strip_tags($temp_content);
		$temp_content = trim($temp_content);
		$strip_content = strip_tags($temp_content);
		$strip_content = str_replace(".", " ", $strip_content);
		$strip_content = str_replace(",", " ", $strip_content);
		$strip_content = str_replace("  ", " ", $strip_content);
		//echo $this->findPos($strip_content, "flow", 0);
		//$strip_content = str_replace(".", " ", $strip_content);
		$content_array = split(" ", $strip_content);
		$temp_word = array();
		for ($i = 0;$i < count($word_array);$i++) {
			$temp_word[$i] = $word_array[$i]['words']['name'];
		}
		for ($j = 0;$j < count($known_word);$j++) {
			$temp_word[$j+$i] = $known_word[$j]['known_words']['name'];
		}
		
		$word_array = $temp_word;
		$search['dict_id'] = 1;
		//Những từ gốc được tìm thấy
		$collection_words = array();
		$tmp_collection_words = array();
		$j = 0;
		//$word_array = array("last");
		//print_r($word_array);
		// $search['search_term'] = "authorities";
		// $temp_search = $this->searchdict($search);		
		// print_r($temp_search);
		//$content_strip = strip_tags($temp_content);
		//$pos = strpos($temp_content, "businesses");
		//echo $pos;		
		//echo substr($temp_content, $pos+strlen("More"), strlen($temp_content));
		//echo $temp_content;
		$tmp_content = '';
		for ($i = 0;$i < count($content_array);$i++) {
			$content_array[$i] = str_replace("'", "", $content_array[$i]);
			$content_array[$i] = str_replace(".", "", $content_array[$i]);
			$content_array[$i] = str_replace(",", "", $content_array[$i]);
			$content_array[$i] = str_replace('"', "", $content_array[$i]);
			$tmp_content = $this->lower($content_array[$i]);
			//echo $tmp_content." ";
			//echo $content_array[$i]."<br/>";
			if (!in_array($tmp_content, $word_array) && !in_array($tmp_content, $tmp_collection_words)) {
				//echo "vao day roi<br/>";
				$search['search_term'] = $tmp_content;
				//echo $search['search_term'];
				$temp_search = $this->searchdict($search);
				//onmouseover='javascript:searchhighlight(\'".$tmp_search['search_term']."\');'
				//print_r($temp_search);
				//Nếu tìm thấy nghĩa
				if (!empty($temp_search)) {
					//Nếu từ đó không nằm trong những từ đã được học hay những từ đã tìm thấy trước đó thì highlight từ đó
					if (!in_array($temp_search[0]['words']['name'], $word_array) && !in_array($temp_search[0]['words']['name'], $tmp_collection_words)) {
						//echo $tmp_content."<br/>";
						$pos = $this->findPos($temp_content, $content_array[$i], 0);
						$tmp_str = substr($temp_content, 0, $pos);
						 //onmouseout="ajax_hideTooltip()"
						if ($pos!==false)
							$temp_content = $tmp_str.'<a href="#" onmouseover="ajax_showTooltip(window.event,\'/dicts/searchhighlight/'.$temp_search[0]['words']['name'].'\',this);return false"><span class="highlight">'.$content_array[$i].'</span></a>'.substr($temp_content, $pos+strlen($content_array[$i]), strlen($temp_content)-($pos+strlen($content_array[$i])));
							//$temp_content = $tmp_str.'<a href="#" onmouseover="search_highlight(\''.$temp_search[0]['words']['name'].'\')"><span class="highlight">'.$content_array[$i].'</span></a>'.substr($temp_content, $pos+strlen($content_array[$i]), strlen($temp_content)-($pos+strlen($content_array[$i])));
						$tmp_collection_words[$j] = $temp_search[0]['words']['name'];
						$collection_words[$j]['name'] = $temp_search[0]['words']['name'];
						$collection_words[$j]['meaning'] = $temp_search[0]['words']['meaning'];
						$j++;
					}
				}
			}
		}
		//echo $temp_content;
		//print_r($collection_words);
		$collection_words['content'] = $temp_content;
		return $collection_words;
		//print_r($collection_words);
		// if (in_array($tmp, $word_array)) {
			
		// }
	}
	
	function getKnownWord() {
		return $this->query("select name from known_words");		
	}
	
	function getWordOfUser($id) {
		$word_id = $this->query("select word from user_words where user_id=$id");
		//echo "select word from user_words where user_id=$id";
		print_r($word_id);
		$temp = '';
		for ($i = 0;$i < count($word_id);$i++) {
			$temp .= $word_id[$i]['user_words']['word'].",";
		}
		$word_id = substr($temp, 0, strlen($temp)-1);
		$word_array = $this->query("select name from words where id in($word_id)");
		//print_r($word_array);
		return $word_array;
	}

}

?>