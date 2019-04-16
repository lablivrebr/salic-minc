<?php

class DadosBancarios_Bootstrap extends Zend_Application_Module_Bootstrap
{
    public function _initREST()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $restRoute = new Zend_Rest_Route(
            $frontController,
            [],
            [
                "dados-bancarios" => [
                    'transferencia-recurso',
                    'captacao-rest',
                    'conciliacao-bancaria',
                    'contas-bancarias',
                    'devolucoes-rest',
                    'extratos-bancarios-consolidado-rest',
                    'extratos-bancarios-rest',
                    'incosistencia-bancaria-rest',
                    'liberacao-rest',
                    'saldo-contas-rest',
                ]
            ]
        );
        $frontController->getRouter()->addRoute('rest-dados-bancarios', $restRoute);
    }
}
