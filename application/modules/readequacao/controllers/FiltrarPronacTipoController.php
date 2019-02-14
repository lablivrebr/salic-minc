<?php

use Application\Modules\Readequacao\Service\Readequacao\Readequacao as ReadequacaoService;

class Readequacao_FiltrarPronacTipoController extends MinC_Controller_Rest_Abstract
{

    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        $profiles = [
            // Autenticacao_Model_Grupos::PROPONENTE,
            // Autenticacao_Model_Grupos::TECNICO_ACOMPANHAMENTO,
            // Autenticacao_Model_Grupos::COORDENADOR_ACOMPANHAMENTO,
            // Autenticacao_Model_Grupos::COORDENADOR_GERAL_ACOMPANHAMENTO,
        ];

        $permissionsPerMethod  = [
        ];
        $this->setProtectedMethodsProfilesPermission($permissionsPerMethod);
        
        parent::__construct($request, $response, $invokeArgs);
    }

    public function getAction() {
        $idPronac = $this->getRequest()->getParam('idPronac');
        $idTipoReadequacao = $this->getRequest()->getParam('idTipoReadequacao');
        
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }
        
        $data = [];
        $code = 200;
        
        $readequacaoService = new ReadequacaoService($this->getRequest(), $this->getResponse());
        $data = $readequacaoService->buscarReadequacoesPorPronacTipo($idPronac, $idTipoReadequacao);
        
        $this->renderJsonResponse($data, $code);
    }

    public function indexAction(){}

    public function headAction(){}

    public function postAction(){}

    public function putAction(){}

    public function deleteAction(){}

}