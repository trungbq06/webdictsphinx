<?php 
// I'm not sure this is a good idea inside Cake, but I had no problems so far
//ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . VENDORS);
//vendor('Zend' . DS . 'Search' . DS . 'Lucene');
require_once('Zend/Search/Lucene.php');

class LuceneComponent extends Object {
    var $controller = true;
	var $model = true;
    var $index = null;
    
    function startup(&$controller) {
    }    

    // Get the index object
    function &getIndex() {
        if(!$this->index) {
            $this->index = new Zend_Search_Lucene("tmp/cache/indexer");
        }
        return $this->index;
    }
    
    // Executes a query to the index and returns the results
    function query($query) {
        $index =& $this->getIndex();
		//echo "toi day";
		Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
		//$query = new Zend_Search_Lucene_Search_Query_Phrase();
		//$query->addTerm(new Zend_Search_Lucene_Index_Term($query, 'title'));
        $results = $index->find($query);
        return $results;
    }
}
?> 