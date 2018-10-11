<?php
use Application\Modules\AvaliacaoResultados\Service\ParecerTecnico\AvaliacaoComprovante as AvaliacaoComprovantesService;


class AvaliacaoResultados_AvaliacaoComprovanteController extends MinC_Controller_Rest_Abstract
{
    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        $profiles = [
            Autenticacao_Model_Grupos::TECNICO_PRESTACAO_DE_CONTAS,
            Autenticacao_Model_Grupos::COORDENADOR_PRESTACAO_DE_CONTAS,
            Autenticacao_Model_Grupos::COORDENADOR_GERAL_PRESTACAO_DE_CONTAS,
        ];

        $permissionsPerMethod  = [
//            'index' => $profiles,
//            'post' => $profiles
        ];
        $this->setProtectedMethodsProfilesPermission($permissionsPerMethod);


        parent::__construct($request, $response, $invokeArgs);
    }

    public function getAction()
    {

        if (
            !$this->getRequest()->getParam('idPronac') ||
            !isset($this->getRequest()->idPronac))
        {
            $this->customRenderJsonResponse([], 404);
            return;
        }

        $comprovantesService = new AvaliacaoComprovantesService($this->getRequest(), $this->getResponse());
        $comprovantes = $comprovantesService->buscarComprovantes();
        $dadosItem = $comprovantesService->buscarDadosAvaliacaoComprovante();

        $this->renderJsonResponse([
            'dadosItem' => $dadosItem,
            'comprovantes' => $comprovantes
        ], 200);
    }

    public function indexAction(){}

    public function headAction(){}

    public function postAction(){}

    public function putAction(){}

    public function deleteAction(){}

}
