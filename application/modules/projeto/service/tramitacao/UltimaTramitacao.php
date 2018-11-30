<?php

namespace Application\Modules\Projeto\Service\Tramitacao;

use Seguranca;

class UltimaTramitacao implements \MinC\Servico\IServicoRestZend
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

    public function buscarUltimaTramitacaoProjeto()
    {
        $idPronac = $this->request->idPronac;

        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }


        $tblProjetos = new \tbHistoricoDocumento();
        $rst = $tblProjetos->buscarHistoricoTramitacaoProjeto(
            [
                'h.idPronac = ?' => $idPronac,
                'h.idDocumento = ?' => 0,
                ' h.stEstado = ?' => 1
            ]
        );

        $tramitacoes = $this->obterUltimaTramitacao($rst);

        $tramitacoes = \TratarArray::utf8EncodeArray($tramitacoes);

        return $tramitacoes;
    }

    private function obterUltimaTramitacao($tramitacoes)
    {
        $resultArray = [];
        foreach ($tramitacoes as $tramitacao) {
            $Emissor = $tramitacao['Emissor'];
            $Receptor = $tramitacao['Receptor'];
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
                'Emissor' => $Emissor,
                'dtTramitacaoEnvio' => $objDateTimeDtTramitacaoEnvio,
                'Receptor' => $Receptor,
                'dtTramitacaoRecebida' => $objDateTimedtTramitacaoRecebida,
                'Situacao' => $tramitacao['Situacao'],
                'Destino' => $tramitacao['Destino'],
                'meDespacho' => $meDespacho,
            ];
        }

        return $resultArray;
    }

}
