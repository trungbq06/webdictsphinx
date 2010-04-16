<?php
App::import('Core', 'I18n');
class DictsController extends AppController {
	var $name = 'Dicts';
	var $components = array('RequestHandler');
	
	function index()
	{
		$this->layout = "";
		try{
			Configure::write('debug', 0);
			ini_set("soap.wsdl_cache_enabled", "0");
			$server = new SoapServer("dicts1.wsdl");
			//$server->setClass("DictsController");
			$server->setClass("Dict");
			//$server->addFunction("searchdict");			
			//$server->service($HTTP_RAW_POST_DATA);
			$server->handle();
			//$functions = $server->getFunctions();
			//print_r($functions);
			//echo "trung";
			//print_r($server->getFunctions());
			//echo "Handled";
			//$this->RequestHandler->respondAs('xml');
		} catch (SoapFault $exception) {
			echo $exception;
	    }
		
		$this->set('user_id', $_SESSION['user_id']);
		$this->set('username', $_SESSION['username']);
	}
	
	function searchword()
	{
		$this->layout = "";
		$search['dict_id'] = 1;
		$search['search_term'] = "authorities";
		$result = $this->Dict->searchdict($search);
		// $tmp = $search['search_term'];
		// if (!empty($result)) {
			// if (substr($tmp, strlen($tmp)-1, 1) == "s") {
				// $search['search_term'] = substr($tmp, 0, strlen($tmp)-1);
				// echo $search['search_term'];
				// $this->searchword();
			// }
		// }
		print_r($result);
	}	
	
	//Test thử server xem có được hay không
	function search()
	{
		$this->layout = "";
		$dicts = $this->Dict->query("select * from dictionaries");
		$this->set("dicts", $dicts);
		//echo "trung";
		ini_set("soap.wsdl_cache_enabled", "0");
		$client = new SoapClient("dicts1.wsdl", array("trace"=>1, "exceptions"=>1));
		//$client = new SoapClient("scramble.wsdl");
		//print_r($client);
		//print_r($client->__getFunctions());
		//echo "trung";
		
		if (isset($_POST['search_term'])) {
			$dict_id = $_POST['dict_id'];
			$search_term = $this->Dict->lower(trim($_POST['search_term']));
		}
		//echo $search_term;
		$this->set("search_term", $_POST['search_term']);
		$this->set("dict_id", $dict_id);
		$search['dict_id'] = $dict_id;
		$search['search_term'] = $search_term;
		$this->set("timedOut", $this->timedOut);
		try {
			//$this->set("result", $client->searchdict("trung"));
			//echo $this->host;
			$result = $client->searchdict($search);
			$this->set("result", $result);
			// $tmp = str_replace('*','{', "/ə'kaunt/ * danh từ");
			// $tmp1 = split('{', $tmp);print_r($tmp1);
			//print_r($result);
			//$result = $client->getMirror("hello");
			//print_r($result);
			//echo "Request :<br>". htmlspecialchars($client->__getLastRequest()). "<br>";
			//echo "Response :<br>". htmlspecialchars($client->__getLastResponse());
	    } catch (SoapFault $exception) {
			echo $exception;
	    }
		
		$checkWord = $this->Dict->query("select id from user_words where user_id=".$_SESSION['user_id']." and word=".$result[0]['words']['id']."");
		$this->set("checkWord", $checkWord);
		
		$user_id = -1;
		if (isset($_SESSION['user_id'])) $user_id = $_SESSION['user_id'];
		$this->set('user_id', $user_id);
		$this->set('username', $_SESSION['username']);
		// $this->RequestHandler->respondAs('xml');
	}
	
	function getNextWord($dict_id, $id, $isNext, $isPlaying) {
		$this->layout = "";
		if ($isNext) 
			$result = $this->Dict->getnext_previous($dict_id, $id, true);
		else $result = $this->Dict->getnext_previous($dict_id, $id, false);
		
		$dicts = $this->Dict->query("select * from dictionaries");
		$this->set("dicts", $dicts);
		$checkWord = $this->Dict->query("select id from user_words where user_id=".$_SESSION['user_id']." and word=".$result[0]['words']['id']."");
		$this->set("checkWord", $checkWord);
		$this->set("dict_id", $dict_id);
		$this->set("result", $result);
		$this->set("search_term", $result[0]['words']['name']);
		$this->set("isPlaying", $isPlaying);
		$this->set("timedOut", $this->timedOut);
	}
	
	function read($category_id = '', $news_id, $page = 1) {
		$this->layout = "";		
		$categories = $this->Dict->getNewsCategory();
		$this->set("categories", $categories);
		$this->set("category_id", $category_id);
		$this->set("news_id", $news_id);
		//print_r($categories);
		$start = ($page-1)*$this->newsPerPage;
		if ($news_id!='-1') {
			$content = $this->Dict->query("select title, created, news_domain_id, id, content from news_content where id=$news_id");
			//print_r($content);
			//$os = array("Mac", "NT", "Irix", "Linux");
			//print_r($os);
			// if (!in_array("Irix1", $os)) {
				// echo "Got Irix";
			// }
			$collection = $this->Dict->collectWord($content);
			//print_r($collection);
			$this->set("collection", $collection);
			$content[0]['news_content']['content'] = $collection['content'];
			$this->set("content", $content);
		} else if ($category_id!='') {
			$resultList = $this->Dict->query("select title, description, created, news_domain_id, id, category_id from news_content where category_id=$category_id order by created desc limit $start, ".$this->newsPerPage."");
			$total = $this->Dict->query("select count(*) as total from news_content where category_id=$category_id");
			
			if (!empty($resultList)) {
				for ($i = 0;$i < count($resultList);$i++) {
					$tmpDomain = $this->Dict->query("select name from news_domain where id=".$resultList[$i]['news_content']['news_domain_id']."");
					$resultList[$i]['news_content']['domain_name'] = $tmpDomain[0]['news_domain']['name'];
				}
			}
		}
		$totalPage = ceil($total[0][0]['total']/$this->newsPerPage);
		$this->set("page", $page);
		$this->set("totalPage", $totalPage);
		$this->set("resultList", $resultList);
		
		$this->set('user_id', $_SESSION['user_id']);
		$this->set('username', $_SESSION['username']);
	}
	
	function searchhighlight($search_term) {
		$this->layout = "";
		$search['dict_id'] = 1;
		$search['search_term'] = $search_term;
		$client = new SoapClient("dicts1.wsdl", array("trace"=>1, "exceptions"=>1));
		$result = $client->searchdict($search);
		$this->set("result", $result);
		$this->set("dict_id", 1);
		$user_id = -1;
		if (isset($_SESSION['user_id'])) $user_id = $_SESSION['user_id'];
		$this->set('user_id', $user_id);
	}
	
	// function getPreviousWord($dict_id, $id) {
		// $this->layout = "";
	// }
	
}

?>