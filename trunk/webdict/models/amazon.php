<?php

class Amazon extends AppModel
{
    var $useTable = false;

    function search($keyword)
    {
        $client = new SoapClient("http://soap.amazon.com/schemas2/AmazonWebServices.wsdl");

        $params = array('keyword'=>$keyword,'page'=> 1,'mode'=> 'books','tag'=>'','type'=> 'lite','devtag'=> 'YOUR_DEV_TAG');

        return $client->KeywordSearchRequest($params);
    }
}

?>