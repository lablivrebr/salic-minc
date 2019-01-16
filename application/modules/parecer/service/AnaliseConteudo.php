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

    const ID_ATO_ADMINISTRATIVO = \Assinatura_Model_DbTable_TbAssinatura::TIPO_ATO_ANALISE_INICIAL;

    function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function index()
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

    }

}
