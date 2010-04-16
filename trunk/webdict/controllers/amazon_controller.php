<?php

class AmazonController extends AppController
{
    function index()
    {
        $this->set('results', $this->Amazon->search('php pattern'));
    }
}

?>