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
                    'analise-conteudo-rest',
                    'analise-custo-rest',
                    'analise-consolidacao-rest',
                    'produtos-secundarios-rest',
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
