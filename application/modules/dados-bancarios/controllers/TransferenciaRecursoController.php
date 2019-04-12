<?php

use Application\Modules\DadosBancarios\service\Captacao as CaptacaoService;

class DadosBancarios_TransferenciaRecursoController extends MinC_Controller_Rest_Abstract
{
    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
//        $permissionsPerMethod  = ['*'];
//
//        $this->setValidateUserIsLogged();
//        $this->setProtectedMethodsProfilesPermission($permissionsPerMethod);

        parent::__construct($request, $response, $invokeArgs);
    }

    public function indexAction()
    {
        $tabelaCaptacao = new CaptacaoService();
        header('Content-Type: application/json');
        var_dump($this->renderJsonResponse($tabelaCaptacao->resultadoExtratoDeContaCaptacaoAction()));
        die;
    }

    public function getAction()
    {
        $this->renderJsonResponse(\TratarArray::utf8EncodeArray('ola'), 200);
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
