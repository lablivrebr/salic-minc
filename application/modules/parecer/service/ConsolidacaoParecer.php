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

    const ID_ATO_ADMINISTRATIVO = \Assinatura_Model_DbTable_TbAssinatura::TIPO_ATO_ANALISE_INICIAL;

    function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;

        $auth = \Zend_Auth::getInstance();
        $this->idUsuario = $auth->getIdentity()->usu_codigo;

        $GrupoAtivo = new \Zend_Session_Namespace('GrupoAtivo');
        $this->idOrgao = $GrupoAtivo->codOrgao;

        $usuarioDao = new \Autenticacao_Model_DbTable_Usuario();
        $agente = $usuarioDao->getIdUsuario($this->idUsuario);
        $this->idAgente = $agente['idagente'];

        if (empty($this->idAgente)) {
            throw new \Exception("Agente n&atilde;o cadastrado");
        }
    }

    private function isPermitidoAvaliar($idProduto, $idPronac)
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
        $buscaParecer = $parecerDAO->findBy($whereParecer);

        return \TratarArray::utf8EncodeArray($buscaParecer);
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
                'SugeridoReal' => 0, # @todo obter este valor
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
                'dsAcaoAlcanceProduto' => '', # o Rômulo pediu pra retirar esse campo, verificar se é preciso salvar
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
