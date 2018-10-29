<?php

namespace Application\Modules\Projeto\Service\Tramitacao;

use Seguranca;

class TramitacaoProjeto implements \MinC\Servico\IServicoRestZend
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

    public function buscarTramitacaoProjetoProjeto()
    {
        $idPronac = $this->request->idPronac;

        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        $tblHistDoc = new \tbHistoricoDocumento();
        $total = $tblHistDoc->buscarHistoricoTramitacaoProjeto(array("p.IdPronac =?"=>$idPronac), null, null, null, false);

        $tramitacoes = $this->obterTramitacaoProjeto($total);

        return $tramitacoes;
    }

    private function obterTramitacaoProjeto($tramitacoes)
    {
        $resultArray = [];
        foreach ($tramitacoes as $tramitacao) {
            $meDespacho = html_entity_decode(utf8_encode($tramitacao['meDespacho']));
            $objDateTimeDtTramitacaoEnvio = new \DateTime($tramitacao['dtTramitacaoEnvio']);
            $objDateTimedtTramitacaoRecebida = new \DateTime($tramitacao['dtTramitacaoRecebida']);

            $resultArray[] = [
                'dtTramitacaoEnvio' => $objDateTimeDtTramitacaoEnvio->format('d/m/Y H:i:s'),
                'dtTramitacaoRecebida' => $objDateTimedtTramitacaoRecebida->format('d/m/Y H:i:s'),
                'Situacao' => $tramitacao['Situacao'],
                'Origem' => $tramitacao['Origem'],
                'Destino' => $tramitacao['Destino'],
                'meDespacho' => $meDespacho,
            ];
        }

        return $resultArray;
    }

}
