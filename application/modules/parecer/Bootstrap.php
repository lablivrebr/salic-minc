<?php
class Parecer_Bootstrap extends Zend_Application_Module_Bootstrap
{
    public function _initPath()
    {
        require_once APPLICATION_PATH . '/modules/parecer/controllers/GenericController.php';
    }

    public function _initREST()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $restRoute = new Zend_Rest_Route(
            $frontController,
            [],
            [
                "parecer" => [
                    'analise-inicial-rest',
                    'analise-inicial-conteudo-rest',
                    'analise-inicial-custo-rest',
                    'analise-inicial-consolidacao-rest',
                    'analise-inicial-outros-produtos-rest',
                    'analise-inicial-finalizacao-rest',
                    'produto-rest',
                    'planilha-produto-rest',
                ]
            ]
        );

        $nomeConjuntoDeRotas = 'restParecer';
        $frontController->getRouter()->addRoute(
            $nomeConjuntoDeRotas,
            $restRoute
        );
    }
}
