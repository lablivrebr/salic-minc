<?php

use Application\Modules\MovimentacaoBancaria\service\Captacao as CaptacaoService;

class MovimentacaoBancaria_TransferenciaRecursoController extends MinC_Controller_Rest_Abstract
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
        xd($this->customRenderJsonResponse($tabelaCaptacao->resultadoExtratoDeContaCaptacaoAction(), 200));
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
