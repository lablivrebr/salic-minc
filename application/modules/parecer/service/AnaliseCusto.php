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
                'PPJ.IdProduto = ?' => $idProduto,
                'PD.Descricao is not null' => null
            );
        }

        $planilha = $PlanilhaDAO->buscarAnaliseCustos($where)->toArray();


        /* Analise de conteudo */
        $analisedeConteudoDAO = new \Analisedeconteudo();
        $analisedeConteudo = $analisedeConteudoDAO->dadosAnaliseconteudo(
            false,
            array(
                'idPronac = ?' => $idPronac,
                'idProduto = ?' => $idProduto
            )
        );

        $planilhaTratada  = $this->montarPlanilha($planilha, $analisedeConteudo);
        $itensCusto = array('fonte' => array(), 'totalSolicitado' => 0, 'totalSugerido' => 0);
        $cont = true;

        $resp['somenteLeitura'] = $this->isPermitidoAvaliar($idProduto, $idPronac);

        $resp = \TratarArray::utf8EncodeArray($planilhaTratada);

        return $resp;

    }

    public function montarPlanilha($planilhaOrcamentaria, $analisedeConteudo)
    {
        $planilha = array();
        $count = 0;
        $i = 1;

        foreach ($planilhaOrcamentaria as $item) {
            $row = $item;

            $produto = !empty($item['idProduto'])
                ? $item['Produto']
                : html_entity_decode('Administração do Projeto');

            $fonte = $item['FonteRecurso'];
            $etapa = $item['Etapa'];
            $regiao = $item['UF'] . ' - ' . $item['Cidade'];

            $row["Seq"] = $i;
//            $row["idPlanilhaProjeto"] = $item['idPlanilhaProjeto'];
//            $row["IdPRONAC"] = $item['IdPRONAC'];
//            $row["idPlanilhaProposta"] = $item['idPlanilhaProposta'];
//            $row["Item"] = $item['Item'];
//            $row["Unidade"] = $item['DescricaoUnidade'];
//            $row["idUnidade"] = $item['idUnidade'];
//            $row["UnidadeProposta"] = $item['UnidadeProjeto'];
//            $row["UnidadeProjeto"] = $item['UnidadeProjeto'];
//            $row['FonteRecurso'] = $item['FonteRecurso'];
//            $row['Cidade'] = $item['Cidade'];
//            $row['UF'] = $item['UF'];
//            $row['idEtapa'] = $item['idEtapa'];
//            $row['Etapa'] = $item['Etapa'];
//            $row['Ocorrencia'] = $item['Ocorrencia'];
//            $row['Quantidade'] = $item['Quantidade'];
//            $row['QtdeDias'] = $item['QtdeDias'];
//            $row['vlUnitario'] = $item['ValorUnitario'];
//            $row["vlSolicitado"] = $item['Quantidade'] * $item['Ocorrencia'] * $item['ValorUnitario'];
//            $row['JustProponente'] = $item['dsJustificativa'];
//            $row['stCustoPraticado'] = $item['custopraticado'];
//            $row['VlSugeridoParecerista'] = $item['VlSugeridoParecerista'];

            // So pode alterar se for incentivo fiscal - FonteRecurso = 109
//            if (($analisedeConteudo[0]->ParecerFavoravel == 1) && ($val->idEtapa != 4)) {
//                if ($this->idGrupo == \Autenticacao_Model_Grupos::PARECERISTA) {
//                    $planilha[$fonte][$val->FonteRecurso][$produto][$val->idEtapa . ' - ' . $val->Etapa][$val->UF . ' - ' . $val->Cidade]['itens'][$val->idPlanilhaProjeto]['Item'] = "<a href='javascript:void(0);' onclick='javascript:AlterarItem({$val->idPlanilhaProjeto},{$idPronac},{$idProduto},{$stPrincipal})'>{$val->Item}</a>";
//                } elseif ($this->idGrupo == \Autenticacao_Model_Grupos::COORDENADOR_DE_PARECER) {
//                    $planilha[$fonte][$val->FonteRecurso][$produto][$val->idEtapa . ' - ' . $val->Etapa][$val->UF . ' - ' . $val->Cidade]['itens'][$val->idPlanilhaProjeto]['Item'] = $val->Item;
//                }
//            } elseif (($analisedeConteudo[0]->ParecerFavoravel == 1) && ($stPrincipal == 1)) {
//                if ($this->idGrupo == \Autenticacao_Model_Grupos::PARECERISTA) {
//                    $planilha[$fonte][$val->FonteRecurso][$produto][$val->idEtapa . ' - ' . $val->Etapa][$val->UF . ' - ' . $val->Cidade]['itens'][$val->idPlanilhaProjeto]['Item'] = "<a href='javascript:void(0);' onclick='javascript:AlterarItem({$val->idPlanilhaProjeto},{$idPronac},{$idProduto},{$stPrincipal})'>{$val->Item}</a>";
//                } elseif ($this->idGrupo == \Autenticacao_Model_Grupos::COORDENADOR_DE_PARECER) {
//                    $planilha[$fonte][$val->FonteRecurso][$produto][$val->idEtapa . ' - ' . $val->Etapa][$val->UF . ' - ' . $val->Cidade]['itens'][$val->idPlanilhaProjeto]['Item'] = $val->Item;
//                }
//            } else {
//                $planilha[$fonte][$produto][$etapa][$regiao]['itens'][$val->idPlanilhaProjeto]['Item'] = "{$val->Item}";
//            }

            $planilha[$fonte]['total'] += $row["VlSolicitado"];
            $planilha[$fonte][$produto]['total'] += $row["VlSolicitado"];
            $planilha[$fonte][$produto][$etapa]['total'] += $row["VlSolicitado"];
            $planilha[$fonte][$produto][$etapa][$regiao]['total'] += $row["VlSolicitado"];
            $planilha[$fonte][$produto][$etapa][$regiao]['itens'][] = $row;

            $planilha['total'] += $item["vlSolicitado"];

            $count++;
            $i++;
        }

        return $planilha;
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
