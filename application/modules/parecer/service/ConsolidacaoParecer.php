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

    const ID_ATO_ADMINISTRATIVO = \Assinatura_Model_DbTable_TbAssinatura::TIPO_ATO_ANALISE_INICIAL;

    function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function obter()
    {
        $id = $this->request->getParam('id');
        $idPronac = $this->request->getParam('idPronac');

        $produtoPrincipalConsolidado = 'N';

        $parecerDAO = new \Parecer();
        $whereParecer = [];
        $whereParecer['idPRONAC = ?'] = $idPronac;
        $buscaParecer = $parecerDAO->buscar($whereParecer)->current()->toArray();
        $countParecerP = count($buscaParecer);
        if ($countParecerP != 0) {
            $produtoPrincipalConsolidado = 'S';
        }

        $resp = [];
        $resp['consolidado'] = $produtoPrincipalConsolidado;
        $resp['parecer'] = $buscaParecer;

        $resp = \TratarArray::utf8EncodeArray($resp);

        return $resp;
    }

    public function salvar()
    {
        throw new \Exception("Em desenvolvimento ...");

    }

}
