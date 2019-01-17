<?php

use Application\Modules\Parecer\Service\AnaliseCusto as AnaliseCusto;

class Parecer_AnaliseCustoRestController extends MinC_Controller_Rest_Abstract
{

    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
//        $profiles = [
//            Autenticacao_Model_Grupos::TECNICO_PRESTACAO_DE_CONTAS,
//            Autenticacao_Model_Grupos::COORDENADOR_PRESTACAO_DE_CONTAS,
//            Autenticacao_Model_Grupos::COORDENADOR_GERAL_PRESTACAO_DE_CONTAS,
//        ];
//
//        $permissionsPerMethod  = [
////            'index' => $profiles,
////            'post' => $profiles
//        ];
//        $this->setProtectedMethodsProfilesPermission($permissionsPerMethod);
//
        $this->setValidateUserIsLogged();

        parent::__construct($request, $response, $invokeArgs);
    }

    public function indexAction()
    {
        try {

            $this->customRenderJsonResponse([], 200);

        } catch (Exception $objException) {
            $this->customRenderJsonResponse([
                'error' => [
                    'code' => 404,
                    'message' => $objException->getMessage()
                ]
            ], 404);

        }
    }

    public function getAction()
    {
        try {
            $analiseConteudoService = new AnaliseCusto($this->getRequest(), $this->getResponse());
            $resposta = $analiseConteudoService->obter();

            $this->customRenderJsonResponse($resposta, 200);

        } catch (Exception $objException) {
            $this->customRenderJsonResponse([
                'error' => [
                    'code' => 404,
                    'message' => $objException->getMessage()
                ]
            ], 404);

        }
    }

    public function postAction()
    {
        try {

            $analiseConteudoService = new AnaliseCusto($this->getRequest(), $this->getResponse());
//            $resposta = $analiseConteudoService->salvar();

            $this->customRenderJsonResponse(
                [
                    'data' => $resposta,
                    'message' => html_entity_decode('Avalia&ccedil;&atilde;o realizada com sucesso')
                ], 200);

        } catch (Exception $e) {
            $this->customRenderJsonResponse(
                [
                    'code' => 500,
                    'message' => html_entity_decode($e->getMessage())
                ], 500);

        }
    }

    public function putAction()
    {
        $this->renderJsonResponse([], 200);
    }

    public function deleteAction()
    {
        $this->renderJsonResponse([], 204);
    }

    public function headAction()
    {
        $this->renderJsonResponse([], 200);
    }
}
