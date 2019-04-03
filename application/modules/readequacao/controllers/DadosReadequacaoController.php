<?php

use Application\Modules\Readequacao\Service\Readequacao\Readequacao as ReadequacaoService;
use Application\Modules\Documento\Service\Documento\Documento as DocumentoService;

class Readequacao_DadosReadequacaoController extends MinC_Controller_Rest_Abstract
{

    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        $profiles = [
            Autenticacao_Model_Grupos::PROPONENTE,
            Autenticacao_Model_Grupos::TECNICO_ACOMPANHAMENTO,
            Autenticacao_Model_Grupos::COORDENADOR_ACOMPANHAMENTO,
            Autenticacao_Model_Grupos::COORDENADOR_GERAL_ACOMPANHAMENTO,
        ];

        $permissionsPerMethod  = [
            'post' => [
                Autenticacao_Model_Grupos::PROPONENTE
            ]
        ];
        $this->setProtectedMethodsProfilesPermission($permissionsPerMethod);

        $subRoutes = [
            'readequacao/dados-readequacao/{idReadequacao}/documento/{idDocumento}'
        ];

        $this->registrarSubRoutes($subRoutes);

        parent::__construct($request, $response, $invokeArgs);
    }

    public function getAction() {
        $data = [];
        $code = 200;

        $idReadequacao = $this->getRequest()->getParam('id');

        $readequacaoService = new ReadequacaoService($this->getRequest(), $this->getResponse());
        $data = $readequacaoService->buscar($idReadequacao);

        $this->renderJsonResponse(\TratarArray::utf8EncodeArray($data), $code);
    }

    public function indexAction(){
        $data = [];
        $code = 200;

        $idReadequacao = $this->getRequest()->getParam('idReadequacao');
        $idPronac = $this->getRequest()->getParam('idPronac');
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        $idTipoReadequacao = $this->getRequest()->getParam('idTipoReadequacao');
        $stEstagioAtual = $this->getRequest()->getParam('stEstagioAtual');

        $readequacaoService = new ReadequacaoService($this->getRequest(), $this->getResponse());
        $data = $readequacaoService->buscarReadequacoes($idPronac, $idTipoReadequacao, $stEstagioAtual);

        $this->renderJsonResponse(\TratarArray::utf8EncodeArray($data), $code);
    }

    public function headAction(){}

    public function postAction(){
        $readequacaoService = new ReadequacaoService($this->getRequest(), $this->getResponse());

        $resposta = $readequacaoService->salvar();

        $this->renderJsonResponse($resposta, 200);
    }

    public function putAction(){}

    public function deleteAction(){
        $readequacaoService = new ReadequacaoService($this->getRequest(), $this->getResponse());
        
        $resposta = $readequacaoService->remover();
        
        $this->renderJsonResponse($resposta, 204);
    }
}
