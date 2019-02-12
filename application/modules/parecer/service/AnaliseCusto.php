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
        $tbDistribuirParecer = new \Parecer_Model_DbTable_TbDistribuirParecer();
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
        $idProduto = (int)$this->request->getParam('id');
        $idPronac = (int)$this->request->getParam('idPronac');
        $stPrincipal = (int)$this->request->getParam('stPrincipal');

        if (empty($idProduto) || empty($idPronac)) {
            throw new \Exception("Dados obrigat&oacute;rios n&atilde;o informados");
        }

        $where = [
            'PPJ.IdPRONAC = ?' => $idPronac,
            'PD.Descricao is not null' => null
        ];

        if ($stPrincipal == 1) {
            $where = [
                'PPJ.IdPRONAC = ?' => $idPronac,
                'PPJ.IdProduto in (0, ?)' => $idProduto
            ];
        }

        $PlanilhaDAO = new \PlanilhaProjeto();
        $planilha = $PlanilhaDAO->buscarAnaliseCustos($where)->toArray();

        $analisedeConteudoDAO = new \Analisedeconteudo();
        $analisedeConteudo = $analisedeConteudoDAO->dadosAnaliseconteudo(
            false,
            [
                'idPronac = ?' => $idPronac,
                'idProduto = ?' => $idProduto
            ]
        );
        $planilha = \TratarArray::utf8EncodeArray($planilha);
        $planilhaMontada = $this->montarPlanilha($planilha, $analisedeConteudo, $stPrincipal);
        $resp['items'] = $planilhaMontada;
        $resp['somenteLeitura'] = $this->isPermitidoAvaliar($idProduto, $idPronac) && $analisedeConteudo[0]->ParecerFavoravel == 1;

        return $resp;

    }

    public function montarPlanilha($planilhaOrcamentaria, $analisedeConteudo, $stPrincipal)
    {
        $planilha = array();
        $i = 0;

        foreach ($planilhaOrcamentaria as $item) {
            $row = $item;
            $i++;

            if (!$this->isItemDisponivelParaVisualizacao($item, $analisedeConteudo, $stPrincipal)) {
                continue;
            }

            $produto = !empty($item['idProduto'])
                ? $item['Produto']
                : html_entity_decode('Administra&ccedil;&atilde;o do Projeto');

            $fonte = $item['FonteRecurso'];
            $etapa = $item['Etapa'];
            $regiao = $item['UF'] . ' - ' . $item['Cidade'];

            $row["Seq"] = $i;
            $row['isDisponivelParaAnalise'] = $this->isItemDisponivelParaAnalise($item);
            $row['Produto'] = $produto;
            $planilha[] = $row;
//            $planilha['total'] += $row["VlSolicitado"];
//            $planilha['totalSugerido'] += $row["VlSugeridoParecerista"];
//            $planilha[$fonte]['total'] += $row["VlSolicitado"];
//            $planilha[$fonte]['totalSugerido'] += $row["VlSugeridoParecerista"];
//            $planilha[$fonte][$produto]['total'] += $row["VlSolicitado"];
//            $planilha[$fonte][$produto]['totalSugerido'] += $row["VlSugeridoParecerista"];
//            $planilha[$fonte][$produto][$etapa]['total'] += $row["VlSolicitado"];
//            $planilha[$fonte][$produto][$etapa]['totalSugerido'] += $row["VlSugeridoParecerista"];
//            $planilha[$fonte][$produto][$etapa][$regiao]['total'] += $row["VlSolicitado"];
//            $planilha[$fonte][$produto][$etapa][$regiao]['totalSugerido'] += $row["VlSugeridoParecerista"];
        }

        return $planilha;
    }

    private function isItemDisponivelParaVisualizacao($item, $analisedeConteudo, $stPrincipal)
    {
        $idProdutoAnalise = $analisedeConteudo[0]->idProduto;

        if (empty($item['idProduto']) && $stPrincipal == 1) {
            return true;
        }


        if ($item['idProduto'] == $idProdutoAnalise) {
            return true;
        }

        return false;
    }

    private function isItemDisponivelParaAnalise($item)
    {

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
        $params = $this->request->getParams();

        unset($params['module']);
        unset($params['controller']);
        unset($params['action']);

        if (!$params['idPlanilhaProjeto']) {
            throw new \Exception('Falta id do item');
        }

        if (!$params['IdPRONAC']) {
            throw new \Exception('Falta id do projeto');
        }

        if (strlen(trim($params['dsJustificativaParecerista'])) < 11) {
            throw new \Exception('Parecer incompleto ou n&atilde;o informado');
        }
        if (round($params['VlSugeridoParecerista'], 2) > round($params['VlSolicitado'], 2)) {
            throw new \Exception('Valor sugerido não pode ser maior que o solicitado');
        }

        $dados = [
            'idUnidade' => $params['idUnidade'],
            'Quantidade' => $params['quantidadeparc'],
            'Ocorrencia' => $params['ocorrenciaparc'],
            'ValorUnitario' => $params['valorUnitarioparc'],
            'QtdeDias' => $params['diasparc'],
            'Justificativa' => utf8_decode($params['dsJustificativaParecerista']),
            'idUsuario' => $this->idUsuario,
        ];

        $where = ['idPlanilhaProjeto = ?' => $params['idPlanilhaProjeto']];
        $planilhaProjeto = new \PlanilhaProjeto();
        $planilhaProjeto->alterar($dados, $where);

        $tbPlanilhaProjetoMapper = new \Planilha_Model_TbPlanilhaProjetoMapper();
        $tbPlanilhaProjetoMapper->atualizarCustosVinculadosDaTbPlanilhaProjeto($params['IdPRONAC']);

        $where = [
            'PPJ.IdPRONAC = ?' => $params['IdPRONAC'],
            'PPJ.idEtapa in (?)' => [
                \Proposta_Model_TbPlanilhaEtapa::CUSTOS_VINCULADOS,
                \Proposta_Model_TbPlanilhaEtapa::CAPTACAO_DE_RECURSOS,
            ]
        ];

        $PlanilhaDAO = new \PlanilhaProjeto();
        $itensCustosVinculados = $PlanilhaDAO->buscarAnaliseCustos($where)->toArray();

        return [
            'item' => $params,
            'custosVinculados' => $itensCustosVinculados
        ];
    }

}
