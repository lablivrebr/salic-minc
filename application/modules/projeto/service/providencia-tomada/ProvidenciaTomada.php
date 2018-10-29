<?php

namespace Application\Modules\Projeto\Service\ProvidenciaTomada;

class ProvidenciaTomada implements \MinC\Servico\IServicoRestZend
{
    /**
     * @var \Zend_Controller_Request_Abstract $request
     */
    private $request;

    /**
     * @var \Zend_Controller_Response_Abstract $response
     */
    private $response;

    function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function buscarProvidenciaTomada()
    {
        $idPronac = $this->request->idPronac;

        if (strlen($idPronac) > 7) {
            $idPronac = \Seguranca::dencrypt($idPronac);
        }

        $pronacArray = [];
        $pronacArray['p.IdPRONAC = ?'] = $idPronac;

        $tblHisSituacao = new \HistoricoSituacao();
        $result = $tblHisSituacao->buscarHistoricosEncaminhamentoIdPronac($pronacArray, null, null, null, false);

        $providenciaTomada = $this->montaArrayProvidenciaTomada($result);
        $resultArray['providenciaTomada'] = $providenciaTomada;

        return $resultArray;
    }

    private function montaArrayProvidenciaTomada($providenciaTomada)
    {
        $resultArray = [];

        foreach ($providenciaTomada as $providencia) {
            $ProvidenciaTomada = html_entity_decode(utf8_encode($providencia['ProvidenciaTomada']));
            $usuario = html_entity_decode(utf8_encode($providencia['usuario']));
            $objDateTimeDtSituacao = new \DateTime($providencia['DtSituacao']);
            $resultArray[] = [
                'DtSituacao' => $objDateTimeDtSituacao->format('d/m/Y H:i:s'),
                'Situacao' => $providencia['Situacao'],
                'ProvidenciaTomada' => $ProvidenciaTomada,
                'cnpjcpf' => $providencia['cnpjcpf'],
                'usuario' => $usuario
            ];
        }

        return $resultArray;
    }

}
