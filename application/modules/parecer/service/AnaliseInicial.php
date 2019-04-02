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
            throw new \Exception("ID da distribui&ccedil;&atilde;o n&atilde;o informado!");
        }

        $dadosWhere = [];
        $dadosWhere["t.idDistribuirParecer = ?"] = $idDistribuirParecer;
        $tbDistribuirParecer = new \Parecer_Model_DbTable_TbDistribuirParecer();
        $distribuicao = $tbDistribuirParecer->dadosParaDistribuir($dadosWhere)->current();
        $isProdutoPrincipal = $distribuicao->stPrincipal == 1;

        if ($distribuicao->FecharAnalise != 0) {
            throw new \Exception("Produto n&atilde;o dispon&iacute;vel para avalia&ccedil;&atilde;o!");
        }

        if ($distribuicao->idAgenteParecerista != $this->idAgente) {
            throw new \Exception("Voc&ecirc; n&atilde;o tem permiss&atilde;o para finalizar esse parecer!");
        }

        if ($this->isConteudoNaoAnalisado($distribuicao->IdPRONAC, $distribuicao->idProduto)) {
            throw new \Exception("Conte&uacute;do ainda n&atilde;o analisado!");
        }

        if ($this->isProdutoComDiligenciaAberta($distribuicao->IdPRONAC, $distribuicao->idProduto)) {
            throw new \Exception("Existe dilig&ecirc;ncia aberta aguardando resposta!");
        }

        if ($this->isItemDeCustosNaoAvaliados($distribuicao->IdPRONAC, $distribuicao->idProduto)) {
            throw new \Exception("An&aacute;lise de custos: existem itens obrigat&oacute;rios da planilha que não foram avaliados!");
        }

        if ($isProdutoPrincipal) {
            if ($this->isProdutosSecundariosNaoAnalisados($distribuicao->IdPRONAC, $distribuicao->idProduto)) {
                throw new \Exception("Existem produtos secund&aacute;rios aguardando an&aacute;lise!");
            }

            if (!$this->isProjetoSemParecer($distribuicao->IdPRONAC)) {
                throw new \Exception("A consolida&ccedil;&atilde;o do projeto &eacute; obrigat&oacute;ria");
            }

            if (!$this->isProjetoDisponivelParaAssinatura($distribuicao->IdPRONAC)) {
                throw new \Exception("Projeto n&atilde;o dispon&iacute;vel para assinatura");
            }
        }

        try {
            $tbDistribuirParecer->getAdapter()->beginTransaction();

            $fecharAnalise = $isProdutoPrincipal
                ? \Parecer_Model_TbDistribuirParecer::FECHAR_ANALISE_ASSINATURA
                : \Parecer_Model_TbDistribuirParecer::FECHAR_ANALISE_FECHADA;

            $dados = array(
                'idOrgao' => $distribuicao->idOrgao,
                'DtEnvio' => $distribuicao->DtEnvio,
                'idAgenteParecerista' => $distribuicao->idAgenteParecerista,
                'DtDistribuicao' => $distribuicao->DtDistribuicao,
                'DtDevolucao' => \MinC_Db_Expr::date(),
                'DtRetorno' => null,
                'FecharAnalise' => $fecharAnalise,
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
            $tbDistribuirParecer->alterar(array('stEstado' => 1), $where);
            $tbDistribuirParecer->inserir($dados);

            $tbDistribuirParecer->getAdapter()->commit();

            if ($isProdutoPrincipal) {
                $this->iniciarAssinatura($distribuicao->IdPRONAC);
            }

        } catch (\Zend_Db_Exception $e) {
            $tbDistribuirParecer->getAdapter()->rollBack();
            throw $e;
        }
        return $distribuicao;
    }

    private function isProdutoComDiligenciaAberta($idPronac, $idProduto)
    {
        $where = [
            'idPronac = ?' => $idPronac,
            'idProduto = ?' => $idProduto,
            'DtResposta IS NULL' => ''
        ];

        $diligenciaDbTable = new \Diligencia_Model_DbTable_TbDiligencia();
        $diligencia = $diligenciaDbTable->findBy($where);

        return !empty($diligencia);
    }

    private function isConteudoNaoAnalisado($idPronac, $idProduto)
    {
        $tbAnaliseDeConteudoDAO = new \Analisedeconteudo();
        $where = [
            'IdPRONAC = ?' => $idPronac,
            'idProduto = ?' => $idProduto
        ];
        $parecerDeConteudo = $tbAnaliseDeConteudoDAO->dadosAnaliseconteudo(null, $where)
            ->current()['ParecerDeConteudo'];

        return (strlen(trim($parecerDeConteudo)) == 0);
    }

    private function isProdutosSecundariosNaoAnalisados($idPronac)
    {
        $where = [
            "stEstado = ?" => 0,
            "FecharAnalise = ?" => 0,
            "TipoAnalise = ?" => 3,
            "IdPRONAC = ?" => $idPronac,
            "stPrincipal = ?" => 0,
            "DtDevolucao is null" => ''
        ];

        $tbDistribuirParecerDAO = new \Parecer_Model_DbTable_TbDistribuirParecer();
        $produtosSecundariosAtivos = $tbDistribuirParecerDAO->findAll($where);

        return (count($produtosSecundariosAtivos) > 0);
    }

    private function isItemDeCustosNaoAvaliados($idPronac, $idProduto)
    {
        $where = [
            'IdPRONAC = ?' => $idPronac,
            'idProduto = ?' => $idProduto,
            'stCustoPraticado = ?' => 1,
            'Justificativa = \'\'' => '',
        ];

        $planilhaProjeto = new \PlanilhaProjeto();
        $itensNaoAvaliados = $planilhaProjeto->findAll($where);
        return (count($itensNaoAvaliados) > 0);
    }

    private function isProjetoDisponivelParaAssinatura($idPronac)
    {
        $objModelDocumentoAssinatura = new \Assinatura_Model_DbTable_TbDocumentoAssinatura();
        $isProjetoDisponivelParaAssinatura = $objModelDocumentoAssinatura->isProjetoDisponivelParaAssinatura(
            $idPronac,
            self::ID_ATO_ADMINISTRATIVO
        );

        return $isProjetoDisponivelParaAssinatura;
    }

    private function isProjetoSemParecer($idPronac)
    {
        $where = [
            'IdPRONAC' => $idPronac,
            'idTipoAgente' => 1,
            'stAtivo' => 1,
            'ResumoParecer IS NOT NULL',
            'Logon' => $this->idUsuario
        ];

        $parecerDbTable = new \Parecer();
        return empty($parecerDbTable->findBy($where));
    }

    private function iniciarAssinatura($idPronac) : int
    {
        try {
            if (!empty($idPronac)) {
                $parecer = new \Parecer();
                $parecerTecnico = $parecer->getIdAtoAdministrativoParecerTecnico(
                    $idPronac,
                    1
                )->current();

                $servicoDocumentoAssinatura = new \Application\Modules\Parecer\Service\Assinatura\AnaliseInicial\DocumentoAssinatura(
                    $idPronac,
                    \Assinatura_Model_DbTable_TbAssinatura::TIPO_ATO_ANALISE_INICIAL,
                    $parecerTecnico['idParecer']
                );
                return $servicoDocumentoAssinatura->iniciarFluxo();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
