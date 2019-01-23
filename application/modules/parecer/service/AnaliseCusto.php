<?php

namespace Application\Modules\Parecer\Service;

class AnaliseCusto implements \MinC\Servico\IServicoRestZend
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

    const ETAPAS_NAO_EDITAVEIS = [
        \Proposta_Model_TbPlanilhaEtapa::CUSTOS_ADMINISTRATIVOS,
        \Proposta_Model_TbPlanilhaEtapa::CUSTOS_VINCULADOS,
        \Proposta_Model_TbPlanilhaEtapa::CAPTACAO_DE_RECURSOS,
    ];

    function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;

        $auth = \Zend_Auth::getInstance();
        $this->idUsuario = $auth->getIdentity()->usu_codigo;

        $grupoAtivo = new \Zend_Session_Namespace('GrupoAtivo');
        $this->idOrgao = $grupoAtivo->codOrgao;
        $this->idGrupo = $grupoAtivo->codGrupo;
    }

    public function index()
    {
        $usuarioDao = new \Autenticacao_Model_DbTable_Usuario();
        $agente = $usuarioDao->getIdUsuario($this->idUsuario);
        $idAgenteParecerista = $agente['idagente'];

        if (empty($idAgenteParecerista)) {
            throw new \Exception("Agente n&atilde;o cadastrado");
        }

        return [];
    }

    private function isPermitidoAvaliar($idProduto, $idPronac)
    {
        $tbDistribuirParecer = new \tbDistribuirParecer();
        $whereProduto = array();
        $whereProduto['idPRONAC = ?'] = $idPronac;
        $whereProduto['idProduto = ?'] = $idProduto;
        $whereProduto["stEstado = ?"] = 0;

        $distribuicao = $tbDistribuirParecer->buscar($whereProduto)->current()->toArray();

        $pareceristaAtivo = ($this->idUsuario == $distribuicao['idAgenteParecerista']);

        return ($this->idGrupo == \Autenticacao_Model_Grupos::PARECERISTA && $pareceristaAtivo);
    }

    public function obter()
    {
        $idProduto = (int) $this->request->getParam('id');
        $idPronac = (int) $this->request->getParam('idPronac');
        $stPrincipal = (int) $this->request->getParam('stPrincipal');

        if(empty($idProduto) || empty($idPronac))  {
            throw new \Exception("Dados obrigat&oacute;rios n&atilde;o informados");
        }

        $PlanilhaDAO = new \PlanilhaProjeto();
        if ($stPrincipal == 1) {
            $where = array('PPJ.IdPRONAC = ?' => $idPronac, 'PPJ.IdProduto in (0, ?)' => $idProduto);
        } else {
            $where = array(
                'PPJ.IdPRONAC = ?' => $idPronac,
//                'PPJ.IdProduto = ?' => $idProduto,
                'PD.Descricao is not null' => null
            );
        }

        $planilha = $PlanilhaDAO->buscarAnaliseCustos($where)->toArray();

        $analisedeConteudoDAO = new \Analisedeconteudo();
        $analisedeConteudo = $analisedeConteudoDAO->dadosAnaliseconteudo(
            false,
            array(
                'idPronac = ?' => $idPronac,
                'idProduto = ?' => $idProduto
            )
        );

        $planilhaTratada  = $this->montarPlanilha($planilha, $analisedeConteudo, $stPrincipal);
        $itensCusto = array('fonte' => array(), 'totalSolicitado' => 0, 'totalSugerido' => 0);
        $cont = true;

        $resp['somenteLeitura'] = $this->isPermitidoAvaliar($idProduto, $idPronac) && $analisedeConteudo[0]->ParecerFavoravel == 1;

        $resp = \TratarArray::utf8EncodeArray($planilhaTratada);

        return $resp;

    }

    public function montarPlanilha($planilhaOrcamentaria, $analisedeConteudo, $stPrincipal)
    {
        $planilha = array();
        $i = 0;

        foreach ($planilhaOrcamentaria as $item) {
            $row = $item;
            $i++;

            if (!$this->isVisualizarProdutoItem($item, $analisedeConteudo, $stPrincipal)) {
                continue;
            }

            $produto = !empty($item['idProduto'])
                ? $item['Produto']
                : html_entity_decode('Administração do Projeto');

            $fonte = $item['FonteRecurso'];
            $etapa = $item['Etapa'];
            $regiao = $item['UF'] . ' - ' . $item['Cidade'];

            $row["Seq"] = $i;
            $row['isDisponivelParaAnalise'] = $this->isItemDisponivelParaAnalise($item) ;

            $planilha['total'] += $row["vlSolicitado"];
            $planilha[$fonte]['total'] += $row["VlSolicitado"];
            $planilha[$fonte]['totalSugerido'] += $row["VlSugeridoParecerista"];
            $planilha[$fonte][$produto]['total'] += $row["VlSolicitado"];
            $planilha[$fonte][$produto]['totalSugerido'] += $row["VlSugeridoParecerista"];
            $planilha[$fonte][$produto][$etapa]['total'] += $row["VlSolicitado"];
            $planilha[$fonte][$produto][$etapa]['totalSugerido'] += $row["VlSugeridoParecerista"];
            $planilha[$fonte][$produto][$etapa][$regiao]['total'] += $row["VlSolicitado"];
            $planilha[$fonte][$produto][$etapa][$regiao]['totalSugerido'] += $row["VlSugeridoParecerista"];
            $planilha[$fonte][$produto][$etapa][$regiao]['itens'][] = $row;
        }

        return $planilha;
    }

    private function isVisualizarProdutoItem($item, $analisedeConteudo, $stPrincipal) {
        $idProdutoAnalise = $analisedeConteudo[0]->idProduto;

        if (empty($item['idProduto']) && $stPrincipal == 1) {
            return true;
        }


        if($item['idProduto'] == $idProdutoAnalise) {
            return true;
        }

        return false;
    }

    private function isItemDisponivelParaAnalise($item) {

        if (in_array($item['idEtapa'], self::ETAPAS_NAO_EDITAVEIS)) {
            return false;
        }

        if ($this->idGrupo != \Autenticacao_Model_Grupos::PARECERISTA) {
            return false;
        }

        return true;
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


        return [];
    }
}
