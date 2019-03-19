<?php

namespace Application\Modules\Parecer\Service;

class AnaliseInicial implements \MinC\Servico\IServicoRestZend
{
    /**
     * @var \Zend_Controller_Request_Abstract $request
     */
    private $request;

    /**
     * @var \Zend_Controller_Response_Abstract $response
     */
    private $response;

    private $idUsuario;
    private $idOrgao;
    private $idGrupo;
    private $idAgente;
    private $auth;

    const ID_ATO_ADMINISTRATIVO = \Assinatura_Model_DbTable_TbAssinatura::TIPO_ATO_ANALISE_INICIAL;

    function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->auth = \Zend_Auth::getInstance()->getIdentity();
        $this->idUsuario = $this->auth->usu_codigo;

        $GrupoAtivo = new \Zend_Session_Namespace('GrupoAtivo');
        $this->idOrgao = $GrupoAtivo->codOrgao;

        $tbUsuario = new \Autenticacao_Model_DbTable_Usuario();
        $usuario = $tbUsuario->getIdUsuario($this->idUsuario);
        $this->idAgente = $usuario['idagente'];

        if (empty($this->idAgente)) {
            throw new \Exception("Agente n&atilde;o cadastrado");
        }
    }

    public function listar()
    {
        $projeto = new \Projetos();
        $resp = $projeto->buscaProjetosProdutosParaAnalise(
            array(
                'distribuirParecer.idAgenteParecerista = ?' => $this->idAgente,
                'distribuirParecer.idOrgao = ?' => $this->idOrgao,
            )
        )->toArray();

        $resp = \TratarArray::utf8EncodeArray($resp);

        $objTbAtoAdministrativo = new \Assinatura_Model_DbTable_TbAtoAdministrativo();
        $quantidadeAssinaturas = $objTbAtoAdministrativo->obterQuantidadeMinimaAssinaturas(
            self::ID_ATO_ADMINISTRATIVO,
            $this->auth->usu_org_max_superior
        );

        return [
            'quantidadeAssinaturas' => $quantidadeAssinaturas,
            'data' => $resp
        ];
    }

    public function analisar()
    {
        $idProduto = $this->request->getParam('id');
        $idPronac = $this->request->getParam('idPronac');

        $projeto = new \Projetos();
        $resp = $projeto->buscaProjetosProdutosParaAnalise(
            array(
                'distribuirParecer.idProduto = ?' => $idProduto,
                'projeto.IdPRONAC = ?' => $idPronac,
            )
        )->current()->toArray();

        $resp = \TratarArray::utf8EncodeArray($resp);

        return $resp;

    }

    public function finalizarParecer()
    {
        $idDistribuirParecer = $this->request->getParam("idDistribuirParecer");

        if (empty($idDistribuirParecer)) {
            throw new \Exception("ID da distribuição não informado!");
        }

        $dadosWhere = [];
        $dadosWhere["t.idDistribuirParecer = ?"] = $idDistribuirParecer;
        $tbDistribuirParecer = new \Parecer_Model_DbTable_TbDistribuirParecer();
        $distribuicao = $tbDistribuirParecer->dadosParaDistribuir($dadosWhere)->current();
        if ($distribuicao->idAgenteParecerista != $this->idAgente) {
            throw new \Exception("Você não tem permissão para finalizar esse parecer!");
        }

        try {
            $tbDistribuirParecer->getAdapter()->beginTransaction();

            $dados = array(
                'idOrgao' => $distribuicao->idOrgao,
                'DtEnvio' => $distribuicao->DtEnvio,
                'idAgenteParecerista' => $distribuicao->idAgenteParecerista,
                'DtDistribuicao' => $distribuicao->DtDistribuicao,
                'DtDevolucao' => \MinC_Db_Expr::date(),
                'DtRetorno' => null,
                'FecharAnalise' => 0,
                'Observacao' => '',
                'idUsuario' => $this->idUsuario,
                'idPRONAC' => $distribuicao->IdPRONAC,
                'idProduto' => $distribuicao->idProduto,
                'TipoAnalise' => $distribuicao->TipoAnalise,
                'stEstado' => 1,
                'stPrincipal' => $distribuicao->stPrincipal,
                'stDiligenciado' => null
            );

            $where = [];
            $where['idDistribuirParecer = ?'] = $idDistribuirParecer;
//            $tbDistribuirParecer->alterar(array('stEstado' => 1), $where);
//            $tbDistribuirParecer->inserir($dados);

            $tbDistribuirParecer->getAdapter()->commit();
        } catch (\Zend_Db_Exception $e) {
            $tbDistribuirParecer->getAdapter()->rollBack();
            throw $e;
        }
        return $distribuicao;
    }
}
