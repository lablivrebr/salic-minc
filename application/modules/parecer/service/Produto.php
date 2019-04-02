<?php

namespace Application\Modules\Parecer\Service;

class Produto implements \MinC\Servico\IServicoRestZend
{
    /**
     * @var \Zend_Controller_Request_Abstract $request
     */
    private $request;

    /**
     * @var \Zend_Controller_Response_Abstract $response
     */
    private $response;

    const ID_ATO_ADMINISTRATIVO = \Assinatura_Model_DbTable_TbAssinatura::TIPO_ATO_ANALISE_INICIAL;

    private $idUsuario;
    private $idOrgao;
    private $idGrupo;
    private $idAgente;
    private $auth;

    function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->auth = \Zend_Auth::getInstance()->getIdentity();
        $this->idUsuario = $this->auth->usu_codigo;

        $grupoAtivo = new \Zend_Session_Namespace('GrupoAtivo');
        $this->idOrgao = $grupoAtivo->codOrgao;
        $this->idGrupo = $grupoAtivo->codGrupo;

        $tbUsuario = new \Autenticacao_Model_DbTable_Usuario();
        $usuario = $tbUsuario->getIdUsuario($this->idUsuario);
        $this->idAgente = $usuario['idagente'];

        if (empty($this->idAgente)) {
            throw new \Exception("Agente n&atilde;o cadastrado");
        }
    }

    private function isPermitidoAvaliar($idPronac, $idProduto)
    {
        $tbDistribuirParecer = new \Parecer_Model_DbTable_TbDistribuirParecer();
        $whereProduto = array();
        $whereProduto['idPRONAC = ?'] = $idPronac;
        $whereProduto['idProduto = ?'] = $idProduto;
        $whereProduto["stEstado = ?"] = 0;

        $distribuicao = $tbDistribuirParecer->buscar($whereProduto)->current()->toArray();
        $pareceristaAtivo = ($this->idAgente == $distribuicao['idAgenteParecerista']);

        return ($this->idGrupo == \Autenticacao_Model_Grupos::PARECERISTA && $pareceristaAtivo);
    }

    public function listar()
    {
        $projeto = new \Projetos();
        $resp = $projeto->buscaProjetosProdutosParaAnalise(
            array(
                'distribuirParecer.idAgenteParecerista = ?' => $this->idAgente,
                'distribuirParecer.idOrgao = ?' => $this->idOrgao,
                'distribuirParecer.stEstado = ?' => 0,
            )
        )->toArray();

        foreach ($resp as &$produto) {
            $produto['stDiligencia'] = $this->definirStatusDiligencia($produto);
            $produto['diasEmDiligencia'] = $this->obterTempoDiligencia($produto);
            $produto['diasEmAvaliacao'] = $this->obterTempoRestanteDeAvaliacao($produto);
        }

        $resp = \TratarArray::utf8EncodeArray($resp);

        $objTbAtoAdministrativo = new \Assinatura_Model_DbTable_TbAtoAdministrativo();
        $quantidadeAssinaturasDoATo = $objTbAtoAdministrativo->obterQuantidadeMinimaAssinaturas(
            self::ID_ATO_ADMINISTRATIVO,
            $this->auth->usu_org_max_superior
        );

        return [
            'quantidadeAssinaturasDoATo' => $quantidadeAssinaturasDoATo,
            'data' => $resp
        ];
    }

    public function buscar()
    {
        $id = $this->request->getParam('id');
        $idPronac = $this->request->getParam('idPronac');

        $projeto = new \Projetos();
        $produto = $projeto->buscaProjetosProdutosParaAnalise(
            [
                'distribuirParecer.idProduto = ?' => $id,
                'projeto.IdPRONAC = ?' => $idPronac,
            ]
        )->current()->toArray();

        $produto['stDiligencia'] = $this->definirStatusDiligencia($produto);
        $produto['diasEmDiligencia'] = $this->obterTempoDiligencia($produto);
        $produto['diasEmAvaliacao'] = $this->obterTempoRestanteDeAvaliacao($produto);

        if ($produto['stPrincipal'] && $produto['FecharAnalise'] != '0') {
            $produto['idDocumentoAssinatura'] = $this->getIdDocumentoAssinatura($idPronac);
        }

        $produto = \TratarArray::utf8EncodeArray($produto);

        return $produto;
    }

    public function salvar()
    {

    }

    private function definirStatusDiligencia($produto)
    {
        $diligencia = 0;
        if ($produto['DtSolicitacao'] && $produto['DtResposta'] == NULL) {
            $diligencia = 1;
        } else if ($produto['DtSolicitacao'] && $produto['DtResposta'] != NULL) {
            $diligencia = 2;
        } else if ($produto['DtSolicitacao']
            && round(\data::CompararDatas($produto['DtDistribuicao'])) > $produto['tempoFimDiligencia']) {
            $diligencia = 3;
        }

        return $diligencia;
    }

    private function obterTempoRestanteDeAvaliacao($produto)
    {
        switch ($produto['stDiligencia']) {
            case 1:
                $tempoRestante = round(\data::CompararDatas($produto['DtDistribuicao'], $produto['DtSolicitacao']));
                break;
            case 2:
                $tempoRestante = round(\data::CompararDatas($produto['DtResposta']));
                break;
            case 3:
                $tempoRestante = round(\data::CompararDatas($produto['DtResposta']));
                break;
            default:
                $tempoRestante = round(\data::CompararDatas($produto['DtDistribuicao']));
                break;
        }

        return $tempoRestante;
    }

    private function obterTempoDiligencia($produto)
    {
        switch ($produto['stDiligencia']) {
            case 1:
                $tempoDiligencia = round(\data::CompararDatas($produto['DtSolicitacao']));
                break;
            default:
                $tempoDiligencia = 0;
                break;
        }

        return $tempoDiligencia;
    }

    public function obterOutrosProdutosDoProjeto()
    {
        $idProduto = (int)$this->request->getParam('id');
        $idPronac = (int)$this->request->getParam('idPronac');

        if (empty($idPronac) || empty($idProduto)) {
            throw new \Exception("Dados obrigat&oacute;rios n&atilde;o informados");
        }

        $dadosWhere = [];
        $dadosWhere["t.stEstado = ?"] = 0;
        $dadosWhere["p.Situacao IN ('B11', 'B14')"] = '';
        $dadosWhere["p.IdPRONAC = ?"] = $idPronac;
        $dadosWhere["t.idProduto <> ?"] = $idProduto;
        $tbDistribuirParecer = new \Parecer_Model_DbTable_TbDistribuirParecer();

        return $tbDistribuirParecer->dadosParaDistribuir($dadosWhere)->toArray();
    }

    private function getIdDocumentoAssinatura($idPronac)
    {
        $objDocumentoAssinatura = new \Assinatura_Model_DbTable_TbDocumentoAssinatura();
        return $objDocumentoAssinatura->getIdDocumentoAssinatura($idPronac, self::ID_ATO_ADMINISTRATIVO);
    }

}
