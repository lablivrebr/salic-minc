<?php

class MovimentacaoBancaria_Bootstrap extends Zend_Application_Module_Bootstrap
{
    public function _initREST()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $restRoute = new Zend_Rest_Route(
            $frontController,
            [],
            [
                "movimentacao-bancaria" => [
                    'foo-rest',
                ]
            ]
        );
        $frontController->getRouter()->addRoute('movimentacao-bancaria', $restRoute);
    }
}
