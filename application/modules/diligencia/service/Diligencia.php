<?php

namespace Application\Modules\Diligencia\Service\Diligencia;


class Diligencia implements \MinC\Servico\IServicoRestZend
{
    /**
     * @var \Zend_Controller_Request_Abstract $request
     */
    private $request;

    /**
     * @var \Zend_Controller_Response_Abstract $response
     */
    private $response;

    function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function listar()
    {
        try {
            $idPronac = $this->request->getParam('idPronac');
            $idDiligencia = $this->request->getParam('idDiligencia');
            $situacao = $this->request->getParam('situacao');
            $idTipoDiligencia = $this->request->getParam('tpDiligencia');
            $idProduto = $this->request->getParam('idProduto');

            if (empty($idPronac)) {
                throw new \Exception("Identificador do projeto &eacute; obrigat&oacute;rio");
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

            $tbDiligenciaDbTable = new \Diligencia_Model_DbTable_TbDiligencia();
            $diligencias = $tbDiligenciaDbTable->listarDiligencias($whereDiligencia)->toArray();

            $result = \TratarArray::utf8EncodeArray($diligencias);

            return $result;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function obter()
    {
        try {

            $idPronac = $this->request->getParam('idPronac');
            $idDiligencia = $this->request->getParam('idDiligencia');

            if (empty($idPronac) || empty($idDiligencia)) {
                throw new \Exception("Identificadores obrigat&oacute;rios n&atilde;o informados");
            }

            $tbDiligenciaDbTable = new \Diligencia_Model_DbTable_TbDiligencia();
            $DocumentosExigidosDao = new \DocumentosExigidos();

            $whereDiligencia = [
                'pro.IdPRONAC = ?' => $idPronac,
                'dil.idDiligencia = ?' => $idDiligencia,
            ];

            $diligencia = $tbDiligenciaDbTable->listarDiligencias($whereDiligencia)->current()->toArray();
            if ($diligencia['idCodigoDocumentosExigidos']) {
                $diligencia['documentosExigidos'] = $DocumentosExigidosDao->listarDocumentosExigido(
                    $diligencia['idCodigoDocumentosExigidos']
                )->toArray();
            }

            $tbDiligenciaXArquivo = new \Diligencia_Model_DbTable_TbDiligenciaXArquivo();
            $anexos = $tbDiligenciaXArquivo->obterAnexosDiligencia(['idDiligencia = ?' => $idDiligencia])->toArray();

            if (!empty($anexos)) {
                $diligencia['anexos'] = $anexos;
            }

            $result = \TratarArray::utf8EncodeArray($diligencia);

            return $result;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function salvar()
    {
        try {
            $idPronac = $this->request->getParam('idPronac');
            $idProduto = $this->request->getParam('idProduto', null);
            $situacao = $this->request->getParam('situacao');
            $idTipoDiligencia = $this->request->getParam('tpDiligencia');
            $solicitacao = $this->request->getParam('solicitacao');
            $confirmaEnvio = $this->request->getParam('btnEnvio');

            if (empty($idPronac)) {
                throw new \Exception("Dados obrigat&oacute;rios n&atilde;o informados");
            }

            if (empty($solicitacao)) {
                throw new \Exception("Texto da dilig&ecirc;ncia &eacute; obrigat&oacute;rio");
            }

            $diligenciaDAO = new \Diligencia();
            $diligenciaCadastrada = $diligenciaDAO->buscar(
                [
                    'idPronac = ?' => $idPronac,
                    'DtResposta ?' => [new \Zend_Db_Expr('IS NULL')],
                    'stEnviado = ?' => 'S'
                ],
                ['idDiligencia DESC'],
                0,
                0,
                $idProduto
            );

            if (count($diligenciaCadastrada) > 0) {
                throw new \Exception("Existe dilig&ecirc;ncia aguardando resposta!");
            }
            $auth = \Zend_Auth::getInstance();
            $idAgente = $auth->getIdentity()->usu_codigo;
            $idProduto = $idProduto ?? new \Zend_Db_Expr('null');

            $dados = array(
                'idPronac' => $idPronac,
                'DtSolicitacao' => null,
                'Solicitacao' => $solicitacao,
                'idSolicitante' => null,
                'idTipoDiligencia' => $idTipoDiligencia,
                'idProduto' => $idProduto,
                'stEstado' => 0,
                'stEnviado' => 'N'
            );

            if ($confirmaEnvio == 1) {
                $dados['DtSolicitacao'] = new \Zend_Db_Expr('GETDATE()');
                $dados['idSolicitante'] = $idAgente;
                $dados['stEnviado'] = 'S';
            }
            $idDiligencia = $diligenciaDAO->inserir($dados);

            return $idDiligencia;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
