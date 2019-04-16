<?php

use Application\Modules\DadosBancarios\Service\Captacao as CaptacaoService;

class DadosBancarios_TransferenciaRecursoController extends MinC_Controller_Rest_Abstract
{
    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        $permissionsPerMethod  = ['*'];

        $this->setValidateUserIsLogged();
        $this->setProtectedMethodsProfilesPermission($permissionsPerMethod);

        parent::__construct($request, $response, $invokeArgs);
    }

    public function indexAction()
    {
        $tabelaCaptacao = new CaptacaoService($this->_request, $this->_response);
        $this->renderJsonResponse([$tabelaCaptacao->resultadoExtratoDeContaCaptacaoAction()], 200);
    }

    public function getAction()
    {
        $tabelaCaptacao = new CaptacaoService($this->_request, $this->_response);
        $this->renderJsonResponse([$tabelaCaptacao->resultadoExtratoDeContaCaptacaoAction()], 200);
    }

    public function headAction(){}

    public function postAction()
    {
        $this->putAction();
    }

    public function putAction()
    {
        $avaliacaoFinanceiraService = new AvaliacaoFinanceiraService($this->getRequest(), $this->getResponse());
        $response = $avaliacaoFinanceiraService->salvar();
        $this->customRenderJsonResponse($response, 200);
    }

    public function deleteAction(){}
}
