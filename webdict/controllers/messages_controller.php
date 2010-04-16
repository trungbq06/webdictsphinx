<?php 
    class MessagesController extends AppController{
        public $uses = null; //for demostration purposes we do not need a model
        
        public $components = array(
            'Soap' => array(
                'wsdl' => 'myWSDLFile', //the file name in the view folder
                'action' => 'service', //soap service method / handler
            )
        );

        public function soap_wsdl(){
            //will be handled by SoapComponent
        }

        public function soap_service(){
            //will be handled by SoapComponent
        }
        
        /**
         * A soap call 'soap_foo' is handled here
         *
         * @param Object $in The input parameter 'foo'
         * @return Object
         */
        public function soap_foo($in){
            $obj = new stdClass();
            $obj->out = 'foo response';
            return $obj;
        }
		
		public function soap_test()
		{
			ini_set('soap.wsdl_cache_enabled', 0); //enable when in production mode, this does save a lot of time

			$soapClient = new SoapClient('http://my-kickass-project.com/soap/messages/wsdl');

			$param = new StdClass();
			$param->in = 'param';

			$foo = $soapClient->soap_foo($param);
			var_dump($foo); //an object of StdClass with an 'out' field and the value 'foo response' 
		}
    }
?> 