<?php 
class SearchsController extends AppController {
    var $name = 'Searchs';
    //var $components = array('lucene');
    var $helpers = array('html');
	var $uses = array("Lucene");

    function documents() {
		$this->layout = "";
		// if (@preg_match('/\pL/u', 'a') == 1) {
			// echo "PCRE unicode support is turned on.\n";
		// } else {
			// echo "PCRE unicode support is turned off.\n";
		// }
		//echo ini_get('max_execution_time');
		print_r($this->Lucene->query());
		$documents = $this->Lucene->search("a phiến");
		//echo $documents[0]['document_title'];
		$this->set('results', $documents);
        // if(!empty($this->data)) {
            // $documents = $this->lucene->query($this->data['Search']['terms']);
            // $this->set('results', $documents);
        // }
    }
}
?>