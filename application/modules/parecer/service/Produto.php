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

    function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function listar()
    {
        $auth = \Zend_Auth::getInstance();
        $idusuario = $auth->getIdentity()->usu_codigo;

        $GrupoAtivo = new \Zend_Session_Namespace('GrupoAtivo');
        $idOrgao = $GrupoAtivo->codOrgao; //  ¿rg¿o ativo na sess¿o

        $UsuarioDAO = new \Autenticacao_Model_DbTable_Usuario();
        $agente = $UsuarioDAO->getIdUsuario($idusuario);
        $idAgenteParecerista = $agente['idagente'];

        if (empty($idAgenteParecerista)) {
            throw new \Exception("Agente n&atilde;o cadastrado");
        }

        $situacao = $this->request->getParam('situacao');

        $projeto = new \Projetos();
        $resp = $projeto->buscaProjetosProdutosParaAnalise(
            array(
                'distribuirParecer.idAgenteParecerista = ?' => $idAgenteParecerista,
                'distribuirParecer.idOrgao = ?' => $idOrgao,
            )
        )->toArray();

        $resp = \TratarArray::utf8EncodeArray($resp);

        $objTbAtoAdministrativo = new \Assinatura_Model_DbTable_TbAtoAdministrativo();
        $quantidadeAssinaturas = $objTbAtoAdministrativo->obterQuantidadeMinimaAssinaturas(
            self::ID_ATO_ADMINISTRATIVO,
            $auth->getIdentity()->usu_org_max_superior
        );

        return [
            'quantidadeAssinaturas' =>  $quantidadeAssinaturas,
            'data' => $resp
        ];
    }

    public function buscar()
    {
        $id = $this->request->getParam('id');
        $idPronac = $this->request->getParam('idPronac');

        $projeto = new \Projetos();
        $resp = $projeto->buscaProjetosProdutosParaAnalise(
            array(
                'distribuirParecer.idProduto = ?' => $id,
                'projeto.IdPRONAC = ?' => $idPronac,
            )
        )->current()->toArray();

        $resp = \TratarArray::utf8EncodeArray($resp);

        return $resp;

    }

    public function salvar()
    {

    }

    public function validaRegra20Porcento($idPronac)
    {
        $planilhaProjeto = new \PlanilhaProjeto();
        $valorProjeto = $planilhaProjeto->somarPlanilhaProjeto(
            $idPronac,
            109
        );
        $valorProjetoDivulgacao = $planilhaProjeto->somarPlanilhaProjetoDivulgacao(
            $idPronac,
            109,
            null,
            null
        );
        $somaProjetoDivulgacao = $valorProjetoDivulgacao->soma ? $valorProjetoDivulgacao->soma : 0;

        if ($somaProjetoDivulgacao != 0) {
            $this->view->totalsugerido = $valorProjeto['soma'] ? $valorProjeto['soma'] : 0;
            $porcentValorProjeto = ($valorProjeto['soma'] * 0.20);
            $totalValorProjetoDivulgacao = $valorProjetoDivulgacao->soma;

            $valorRetirar = $totalValorProjetoDivulgacao - $porcentValorProjeto;
            $this->view->valorRetirar = $valorRetirar;

            if ($totalValorProjetoDivulgacao > $porcentValorProjeto) {
                return false;
            }
        }

        return true;
    }

    public function obterProdutoSecundario()
    {
        $idProduto = (int) $this->request->getParam('id');
        $idPronac = (int) $this->request->getParam('idPronac');

        if(empty($idPronac))  {
            throw new \Exception("Dados obrigat&oacute;rios n&atilde;o informados");
        }

        $dadosWhere["t.stEstado = ?"] = 0;
        $dadosWhere["t.TipoAnalise in (?)"] = array(1, 3);
        $dadosWhere["p.Situacao IN ('B11', 'B14')"] = '';
        $dadosWhere["p.IdPRONAC = ?"] = $idPronac;
        $dadosWhere["t.stPrincipal = ?"] = 0;
        $tbDistribuirParecer = new \tbDistribuirParecer();
        $produtosEmAnalise = $tbDistribuirParecer->dadosParaDistribuirSecundarios($dadosWhere);

        $dadosWhere["t.DtDistribuicao is not null"] = '';
        $dadosWhere["t.DtDevolucao is null"] = '';
        $SecundariosAtivos = $tbDistribuirParecer->dadosParaDistribuir($dadosWhere);
        $pscount = count($SecundariosAtivos);

        $i = 1;
        foreach ($Secundarios as $ps) {
            $wherePS['PAP.idPRONAC = ?'] = $ps->IdPRONAC;
            $wherePS['PAP.idProduto = ?'] = $ps->idProduto;
            $PlanilhaDAO = new \PlanilhaProjeto();
            $valorSugerido = $PlanilhaDAO->somaDadosPlanilha($wherePS);

            $produtosSecundarios[$i]['IdPRONAC'] = $ps->IdPRONAC;
            $produtosSecundarios[$i]['idProduto'] = $ps->idProduto;
            $produtosSecundarios[$i]['stPrincipal'] = $ps->stPrincipal;
            $produtosSecundarios[$i]['Produto'] = $ps->Produto;
            $i++;
        }

        $this->view->produtosSecundarios = $Secundarios;
        // $this->view->produtosSecundarios = $produtosSecundarios;
        $this->view->produtosSecundariosEmAnalise = $pscount;

        /** Verificar se o Produto principal j� foi dado a consolida��o ********************/
        $consolidado = 'N';
        $enquadramentoDAO = new Admissibilidade_Model_Enquadramento();
        $buscaEnquadramento = $enquadramentoDAO->buscarDados($idPronac, null, false);
        $countEnquadramentoP = count($buscaEnquadramento);

        $parecerDAO = new Parecer();
        $whereParecer['idPRONAC = ?'] = $idPronac;
        $buscaParecer = $parecerDAO->buscar($whereParecer)->toArray();
        $countParecerP = count($buscaParecer);
        /***********************************************************************************/
        if (($countEnquadramentoP != 0) && ($countParecerP != 0)) {
            $consolidado = 'S';
        }

        return ;
    }
}
