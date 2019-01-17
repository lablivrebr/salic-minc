<?php

namespace Application\Modules\Parecer\Service;

class AnaliseConteudo implements \MinC\Servico\IServicoRestZend
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

    const ID_ATO_ADMINISTRATIVO = \Assinatura_Model_DbTable_TbAssinatura::TIPO_ATO_ANALISE_INICIAL;

    function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;

        $auth = \Zend_Auth::getInstance();
        $this->idUsuario = $auth->getIdentity()->usu_codigo;

        $GrupoAtivo = new \Zend_Session_Namespace('GrupoAtivo');
        $this->idOrgao = $GrupoAtivo->codOrgao;
    }

    public function index()
    {

        $UsuarioDAO = new \Autenticacao_Model_DbTable_Usuario();
        $agente = $UsuarioDAO->getIdUsuario($this->idUsuario);
        $idAgenteParecerista = $agente['idagente'];

        if (empty($idAgenteParecerista)) {
            throw new \Exception("Agente n&atilde;o cadastrado");
        }

        return [];
    }

    private function isPermitidoAvaliar($idProduto, $idPronac)
    {
        $auth = \Zend_Auth::getInstance();
        $idUsuario = $auth->getIdentity()->usu_codigo;

        $GrupoAtivo = new \Zend_Session_Namespace('GrupoAtivo');
        $codGrupo = $GrupoAtivo->codGrupo;

        $tbDistribuirParecer = new \tbDistribuirParecer();
        $whereProduto = array();
        $whereProduto['idPRONAC = ?'] = $idPronac;
        $whereProduto['idProduto = ?'] = $idProduto;
        $whereProduto["stEstado = ?"] = 0;

        $distribuicao = $tbDistribuirParecer->buscar($whereProduto)->current()->toArray();

        $pareceristaAtivo = ($idUsuario == $distribuicao['idAgenteParecerista']);

        return ($codGrupo == \Autenticacao_Model_Grupos::PARECERISTA && $pareceristaAtivo);
    }

    public function obter()
    {
        $idProduto = (int) $this->request->getParam('id');
        $idPronac = (int) $this->request->getParam('idPronac');

        if(empty($idProduto) || empty($idPronac))  {
            throw new \Exception("Dados obrigat&oacute;rios n&atilde;o informados");
        }

        $analisedeConteudoDAO = new \Analisedeconteudo();  //@todo migrar para o modulo
        $analisedeConteudo = $analisedeConteudoDAO->dadosAnaliseconteudo(
            false,
            [
                'idProduto = ?' => $idProduto,
                'idPronac = ?' => $idPronac
            ]
        )->current();

        $resp = count($analisedeConteudo) > 0 ? $analisedeConteudo->toArray() : [];
        $resp['somenteLeitura'] = $this->isPermitidoAvaliar($idProduto, $idPronac)
            && count($analisedeConteudo) > 0;

        $resp = \TratarArray::utf8EncodeArray($resp);

        return $resp;

    }

    public function salvar()
    {
        $idAnaliseDeConteudo = $this->request->getParam('idAnaliseDeConteudo');
        $idPronac = $this->request->getParam('IdPRONAC');
        $idProduto = $this->request->getParam('idProduto');
        $parecerFavoravel= $this->request->getParam('ParecerFavoravel');
        $parecerDeConteudo = utf8_decode($this->request->getParam('ParecerDeConteudo'));
        $stPrincipal = utf8_decode($this->request->getParam('stPrincipal'));

        if (!$idPronac) {
            throw new \Exception('Falta idPronac');
        }

        if (!$idProduto) {
            throw new \Exception('Falta id do Produto');
        }

        if (strlen(trim($parecerDeConteudo)) == 0) {
            throw new \Exception('Falta parecer de conte&uacute;do');
        }

        if (!$parecerFavoravel) {
            $dadosZerarPlanilha = [
                'idUnidade' => 1,
                'Quantidade' => 0,
                'Ocorrencia' => 0,
                'ValorUnitario' => 0,
                'QtdeDias' => 0,
                'idUsuario' => $this->idUsuario,
                'Justificativa' => ''
            ];

            $whereZerarPlanilha = [
                'idPronac = ?' => $idPronac
            ];

            if (!$stPrincipal) {
                $whereZerarPlanilha[ 'idProduto = ?'] = $idProduto;
            }

            $planilhaProjeto = new \PlanilhaProjeto();
            $planilhaProjeto->alterar($dadosZerarPlanilha, $whereZerarPlanilha);
        }

        $dados = [
            'idPronac' => $idPronac,
            'idProduto' => $idProduto,
            'Lei8313' => 0,
            'Artigo3' => 0,
            'IncisoArtigo3' => 0,
            'AlineaArtigo3' => " ",
            'Artigo18' => 0,
            'AlineaArtigo18' => " ",
            'Artigo26' => 0,
            'Lei5761' => 0,
            'Artigo27' => 0,
            'IncisoArtigo27_I' => 0,
            'IncisoArtigo27_II' => 0,
            'IncisoArtigo27_III' => 0,
            'IncisoArtigo27_IV' => 0,
            'TipoParecer' => 1,
            'ParecerFavoravel' => $parecerFavoravel,
            'ParecerDeConteudo' => $parecerDeConteudo,
            'idParecer' => null,
            'idUsuario' => $this->idUsuario,
        ];

        $analisedeConteudoDAO = new \Analisedeconteudo();

        if (empty($idAnaliseDeConteudo)) {
            $analisedeConteudo = $analisedeConteudoDAO->dadosAnaliseconteudo(
                false,
                [
                    'idProduto = ?' => $idProduto,
                    'idPronac = ?' => $idPronac
                ]
            )->current();

            if ($analisedeConteudo) {
                $idAnaliseDeConteudo = $analisedeConteudo->idAnaliseDeConteudo;
            }
        }

        if (empty($idAnaliseDeConteudo)) {
            $idAnaliseDeConteudo = $analisedeConteudoDAO->insert($dados);
        } else {
            $where = [];
            $where['idAnaliseDeConteudo = ?'] = $idAnaliseDeConteudo;
            $where['idPRONAC = ?'] = $idPronac;
            $where['idProduto = ?'] = $idProduto;
            $analisedeConteudoDAO->update($dados, $where);
        }

        $dados['idAnaliseDeConteudo'] = $idAnaliseDeConteudo;

        return $dados;
    }
}
