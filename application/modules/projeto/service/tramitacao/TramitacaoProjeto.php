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

        $tramitacoes = \TratarArray::utf8EncodeArray($tramitacoes);

        return $tramitacoes;
    }

    private function obterTramitacaoProjeto($tramitacoes)
    {
        $resultArray = [];
        foreach ($tramitacoes as $tramitacao) {
            $meDespacho = $tramitacao['meDespacho'];
            $objDateTimeDtTramitacaoEnvio = ' ';
            $objDateTimedtTramitacaoRecebida = ' ';

            if (!empty($tramitacao['dtTramitacaoEnvio'])) {
                $objDateTimeDtTramitacaoEnvio = new \DateTime($tramitacao['dtTramitacaoEnvio']);
                $objDateTimeDtTramitacaoEnvio = $objDateTimeDtTramitacaoEnvio->format('d/m/Y');
            }

            if (!empty($tramitacao['dtTramitacaoRecebida'])) {
                $objDateTimedtTramitacaoRecebida = new \DateTime($tramitacao['dtTramitacaoRecebida']);
                $objDateTimedtTramitacaoRecebida = $objDateTimedtTramitacaoRecebida->format('d/m/Y');
            }

            $resultArray[] = [
                'dtTramitacaoEnvio' => $objDateTimeDtTramitacaoEnvio,
                'dtTramitacaoRecebida' => $objDateTimedtTramitacaoRecebida,
                'Situacao' => $tramitacao['Situacao'],
                'Origem' => $tramitacao['Origem'],
                'Destino' => $tramitacao['Destino'],
                'meDespacho' => $meDespacho,
            ];
        }

        return $resultArray;
    }

}
