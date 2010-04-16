<?php 
    App::import('core', 'AppHelper');

    /**
    * Soap component for handling soap requests in Cake
    *
    * @author      Marcel Raaijmakers (Marcelius)
    * @copyright   Copyright 2009, Marcel Raaijmakers
    * @license     http://www.opensource.org/licenses/mit-license.php The MIT License
    */
    class SoapComponent extends Component{

        var $name = 'Soap';

        var $components = array('RequestHandler');

        var $controller;

        var $__settings = array(
            'wsdl' => false,
            'wsdlAction' => 'wsdl',
            'prefix' => 'soap',
            'action' => array('service'),
        );

        public function initialize($controller, $settings = array()){
            if (Configure::read('debug') != 0){
                ini_set('soap.wsdl_cache_enabled', false);
            }

            $this->controller = $controller;

            if (isset($settings['wsdl']) && !empty($settings['wsdl'])){
                $this->__settings['wsdl'] = $settings['wsdl'];
            }

            if (isset($settings['prefix'])){
                $this->__settings['prefix'] = $settings['prefix'];
            }

            if (isset($settings['action'])){
                $this->__settings['action'] = is_array($settings['action']) ? $settings['action'] : array($settings['action']);
            }

            parent::initialize($controller);
        }


        public function startup(){
            if (isset($this->controller->params['soap'])){
                if ($this->__settings['wsdl'] != false){
                    //render the wsdl file
                    if ($this->action() == $this->__settings['wsdlAction']){
                        Configure::write('debug', 0);
                        $this->RequestHandler->respondAs('xml');

                        $this->controller->ext = '.wsdl';
                        $this->controller->render(null, false, DS . 'elements' . DS . $this->__settings['wsdl']); //only works with short open tags set to false!
                    } elseif(in_array($this->action(), $this->__settings['action'])) {

                        //handle request
                        $soapServer = new SoapServer($this->wsdlUrl());
                        $soapServer->setObject($this->controller);
                        $soapServer->handle();

                        //stop script execution
                        $this->_stop();
                        return false;

                    }
                }
            }
        }

        /**
         * Return the current action
         *
         * @return string
         */
        public function action(){
            return (!empty($this->__settings['prefix'])) ? str_replace( $this->__settings['prefix'] . '_', '',  $this->controller->action) : $this->controller->action;
        }

        /**
         * Return the url to the wsdl file
         *
         * @return string
         */
        public function wsdlUrl(){
            return AppHelper::url(array('controller'=>Inflector::underscore($this->controller->name), 'action'=>$this->__settings['wsdlAction'], $this->__settings['prefix'] => true), true);
        }

    }
?> 