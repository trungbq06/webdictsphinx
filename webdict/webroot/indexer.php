<?php

// Add your vendor directory to the includepath. ZF needs this.
//ini_set('include_path', ini_get('include_path') . ':' . dirname(__FILE__) . '/vendors');

// Require the Lucene Class
require_once('Zend/Search/Lucene.php');

// Establish your connection to the database
mysql_connect('localhost', 'root', '');
mysql_select_db('webdict');

// Create a new index. This folder has to be readable by the httpd user
// I will use the cache directory to store the index data
$indexPath = dirname(__FILE__) . '/tmp/cache/indexer';
//echo $indexPath;
//setlocale(LC_CTYPE, 'de_DE.iso-8859-1');
$index = new Zend_Search_Lucene($indexPath, true);
Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8());
// Lets get some records to add to the index
$documents_rs = mysql_query('SELECT * FROM words');
//Tạo một indexer
while($document = mysql_fetch_object($documents_rs)) {
    // Create a new searchable document instance
    $doc = new Zend_Search_Lucene_Document();
	//echo $document->name;
    // Add some information
	$doc->addField(Zend_Search_Lucene_Field::Text('title', $document->name, 'utf-8'));
    $doc->addField(Zend_Search_Lucene_Field::UnIndexed('document_id', $document->id));
    $doc->addField(Zend_Search_Lucene_Field::UnIndexed('document_created', $document->created));
    $doc->addField(Zend_Search_Lucene_Field::UnIndexed('document_updated', $document->updated));
    //$doc->addField(Zend_Search_Lucene_Field::Text('document_title', utf8_encode($document->name)));
    //$doc->addField(Zend_Search_Lucene_Field::Text('document_description', $document->meaning));
    
    // Add the document to the index
    $index->addDocument($doc);
}

// Commit the index
$index->commit();
?> 