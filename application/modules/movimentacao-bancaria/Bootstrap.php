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
                    'transferencia-recurso',
                ]
            ]
        );
        $frontController->getRouter()->addRoute('rest-movimentacao-bancaria', $restRoute);
    }
}
