<?php

namespace Application\Modules\Parecer\Service;

class ConsolidacaoParecer implements \MinC\Servico\IServicoRestZend
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

    public function obter()
    {
        $idPronac = $this->request->getParam('idPronac');

        $parecerDAO = new \Parecer();
        $whereParecer = [];
        $whereParecer['idPRONAC = ?'] = $idPronac;
        $parecer = $parecerDAO->findBy($whereParecer);
        $planilhaprojeto = new \PlanilhaProjeto();
        $total = $planilhaprojeto->somarPlanilhaProjeto($idPronac, 109);
        $parecer['SugeridoReal'] = $total['soma'];

        return \TratarArray::utf8EncodeArray($parecer);
    }

    public function salvar()
    {
        $idPronac = $this->request->getParam('IdPRONAC');
        $resumoParecer = $this->request->getParam("ResumoParecer");
        $parecerFavoravel = $this->request->getParam('ParecerFavoravel');

        try {

            if (empty($idPronac) || count($resumoParecer) > 10 || empty($parecerFavoravel) ) {
                throw new \Exception("Dados obrigatórios não informados");
            }

            $enquadramentoDAO = new \Admissibilidade_Model_Enquadramento();
            $enquadramento = $enquadramentoDAO->buscarDados($idPronac, null, false);

            $planilhaprojeto = new \PlanilhaProjeto();
            $soma = $planilhaprojeto->somarPlanilhaProjeto(
                $idPronac,
                \Mecanismo::INCENTIVO_FISCAL_FEDERAL
            );
            $sugeridoReal = $soma['Soma'];

            $parecerDAO = new \Parecer();
            $dadosParecer = [
                'idPRONAC' => $idPronac,
                'AnoProjeto' => $enquadramento['AnoProjeto'],
                'Sequencial' => $enquadramento['Sequencial'],
                'TipoParecer' => 1,
                'ParecerFavoravel' => $parecerFavoravel,
                'DtParecer' => \MinC_Db_Expr::date(),
                'NumeroReuniao' => null,
                'ResumoParecer' => utf8_decode($resumoParecer),
                'SugeridoReal' => $sugeridoReal,
                'Atendimento' => 'S',
                'idEnquadramento' => $enquadramento['IdEnquadramento'],
                'stAtivo' => 1,
                'idTipoAgente' => 1,
                'Logon' => $this->idUsuario
            ];
            $buscarParecer = $parecerDAO->buscar(['IdPRONAC = ?' => $idPronac]);

            if (count($buscarParecer) > 0) {
                $buscarParecer = $buscarParecer->current();
                $whereUpdateParecer = 'IdParecer = ' . $buscarParecer->IdParecer;
                $parecerDAO->alterar($dadosParecer, $whereUpdateParecer);
                $idParecer = $buscarParecer->IdParecer;
            } else {
                $idParecer = $parecerDAO->inserir($dadosParecer);
            }

            $tbAcaoAlcanceProjeto = new \tbAcaoAlcanceProjeto();
            $buscarAcaoAlcanceProjeto = $tbAcaoAlcanceProjeto->buscar(
                ['idPronac = ?' => $idPronac, 'idParecer = ?' => $idParecer]
            );

            $dados = array(
                'idPronac' => $idPronac,
                'idParecer' => $idParecer,
                'tpAnalise' => 1,
                'dtAnalise' => \MinC_Db_Expr::date(),
                'dsAcaoAlcanceProduto' => '', # @todo o Rômulo pediu pra retirar esse campo, verificar se é preciso salvar
                'idUsuario' => $this->idUsuario,
                'stEstado' => 1
            );

            if (count($buscarAcaoAlcanceProjeto) > 0) {
                $where = array(
                    'idPronac = ?' => $idPronac,
                    'idParecer = ?' => $idParecer
                );

                $tbAcaoAlcanceProjeto->update($dados, $where);
            } else {
                $tbAcaoAlcanceProjeto->insert($dados);
            }

        } catch (\Exception $e) {
            throw $e;
        }

    }

}
