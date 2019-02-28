<?php


class Diligencia_DiligenciaRestController extends MinC_Controller_Rest_Abstract
{

    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
//        $profiles = [
//            Autenticacao_Model_Grupos::PARECERISTA,
//            Autenticacao_Model_Grupos::COORDENADOR_DE_PARECER,
//        ];
//
//        $permissionsPerMethod = [
//            '*' => $profiles,
//        ];
//
//        $this->setProtectedMethodsProfilesPermission($permissionsPerMethod);
        $this->setValidateUserIsLogged();

        parent::__construct($request, $response, $invokeArgs);
    }

    public function indexAction()
    {
        try {

            $idPronac = $this->getRequest()->getParam('idPronac');
            $idDiligencia = $this->getRequest()->getParam('idDiligencia');
            $situacao = $this->getRequest()->getParam('situacao');
            $idTipoDiligencia = $this->getRequest()->getParam('tpDiligencia');
            $idProduto = $this->getRequest()->getParam('idProduto');

            if (empty($idPronac)) {
                throw new Exception("Identificador do projeto &eacute; obrigat&oacute;rio");
            }

            $whereDiligencia = ['pro.IdPRONAC = ?' => $idPronac];

            if (!empty($idProduto)) {
                $whereDiligencia = [
                    'pro.IdPRONAC = ?' => $idPronac,
                    'dil.idProduto = ?' => $idProduto,
                    'dil.stEnviado = ?' => 'S'
                ];
            }

            if ($idDiligencia) {
                $whereDiligencia['dil.idDiligencia = ?'] = $idDiligencia;
            }

            if ($idTipoDiligencia) {
                $whereDiligencia['dil.idTipoDiligencia = ?'] = $idTipoDiligencia;
            }

            if ($situacao) {
                $whereDiligencia['pro.Situacao = ?'] = $situacao;
            }

            $tbDiligenciaDbTable = new Diligencia_Model_DbTable_TbDiligencia();
            $diligencias = $tbDiligenciaDbTable->listarDiligencias($whereDiligencia)->toArray();

            $this->customRenderJsonResponse([
                'items' => TratarArray::utf8EncodeArray($diligencias),
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
            $idPronac = $this->getRequest()->getParam('idPronac');
            $idDiligencia = $this->getRequest()->getParam('idDiligencia');

            if (empty($idPronac) || empty($idDiligencia)) {
                throw new Exception("Identificadores obrigat&oacute;rios n&atilde;o informados");
            }

            $tbDiligenciaDbTable = new Diligencia_Model_DbTable_TbDiligencia();
            $DocumentosExigidosDao = new DocumentosExigidos();

            $whereDiligencia = [
                'pro.IdPRONAC = ?' => $idPronac,
                'dil.idDiligencia = ?' => $idDiligencia,
            ];

            $data = [];
            $diligencia = $tbDiligenciaDbTable->listarDiligencias($whereDiligencia)->current()->toArray();
            $data['diligencia']  = $diligencia;
            if ($diligencia['idCodigoDocumentosExigidos']) {
                $data['diligencia']['documentosExigidos'] = $DocumentosExigidosDao->listarDocumentosExigido(
                    $diligencia['idCodigoDocumentosExigidos']
                )->toArray();
            }

            $tbDiligenciaXArquivo = new Diligencia_Model_DbTable_TbDiligenciaXArquivo();
            $anexos = $tbDiligenciaXArquivo->obterAnexosDiligencia(['idDiligencia = ?' => $idDiligencia])->toArray();

            if (!empty($anexos)) {
                $data['diligencia']['anexos'] = $anexos;
            }

            $this->customRenderJsonResponse(TratarArray::utf8EncodeArray($data), 200);

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

            $this->customRenderJsonResponse(
                [
                    'data' => [],
                    'message' => html_entity_decode('')
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
