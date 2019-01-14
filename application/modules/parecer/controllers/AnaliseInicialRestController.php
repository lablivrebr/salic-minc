<?php

use Application\Modules\Parecer\Service\AnaliseInicial as AnaliseInicialService;

class Parecer_AnaliseInicialRestController extends MinC_Controller_Rest_Abstract
{

    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        $this->setValidateUserIsLogged();

        parent::__construct($request, $response, $invokeArgs);
    }

    public function indexAction()
    {
        try {
            $tramitacaoService = new AnaliseInicialService($this->getRequest(), $this->getResponse());
            $resposta = $tramitacaoService->listar();

            $this->customRenderJsonResponse([
                'quantidadeAssinaturas' =>  $resposta['quantidadeAssinaturas'],
                'items' => $resposta['data'],
            ], 200);

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
            $tramitacaoService = new AnaliseInicialService($this->getRequest(), $this->getResponse());
            $resposta = $tramitacaoService->buscarTramitacaoProjetoProjeto();

            $this->RenderJsonResponse($resposta, 200);

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
        $this->renderJsonResponse([], 201);
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
