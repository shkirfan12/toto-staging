<?php

class Login_Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
    /* public function _initHSSess(){
      $frontController  = Zend_Controller_Front::getInstance();
      $frontController->setBaseUrl('');
      }

      protected function _initAutoloader(){
      $autoloader = new Zend_Application_Module_Autoloader(array(
      'namespace' => '',
      'basePath'  => APPLICATION_PATH . '/modules',
      ));
      return $autoloader;
      }

      protected function _initAutoloadModules() {
      $autoloader = new Zend_Application_Module_Autoloader(
      array(
      'namespace' => '',
      'basePath' => APPLICATION_PATH . '/modules/default'
      ),
      array(
      'namespace' => 'Admin',
      'basePath' => APPLICATION_PATH . '/modules/test'
      )
      );
      return $autoloader;
      } 
     */

    protected function _initDoctype() {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }

    /* function to register a constant for config */

    protected function _initConfig() {

        $config = new Zend_Config($this->getOptions(), true);

        Zend_Registry::set('config', $config);

        return $config;
    }

    /* function to set base url of the project */

    protected function _initSetBaseUrl() {

        $controller = Zend_Controller_Front::getInstance();

        $controller->setControllerDirectory('./application/controllers')
                ->setBaseUrl('/');

        return $controller;
    }

}

