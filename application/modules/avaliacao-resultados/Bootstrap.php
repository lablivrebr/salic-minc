<?php
class AvaliacaoResultados_Bootstrap extends Zend_Application_Module_Bootstrap
{
    public function _initREST()
    {
        $frontController = Zend_Controller_Front::getInstance();

        $restRoute = new Zend_Rest_Route(
            $frontController,
            [],
            [
                'avaliacao-resultados' => [
                    'index',
                    'emissao-parecer-rest',
                    'encaminhamento-prestacao-contas',
                    'fluxo',
                    'estado',
                    'tipo-avaliacao-rest',
                ]
            ]
        );

        $frontController->getRouter()->addRoute('rest-avaliacao-resultados', $restRoute);
    }
}
