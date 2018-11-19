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

        $tblProjetos = new \Projetos();
        $rst = $tblProjetos->buscarDadosUC75($idPronac);

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

            if (!empty($tramitacao['DtTramitacaoEnvio'])) {
                $objDateTimeDtTramitacaoEnvio = new \DateTime($tramitacao['DtTramitacaoEnvio']);
                $objDateTimeDtTramitacaoEnvio = $objDateTimeDtTramitacaoEnvio->format('d/m/Y H:i:s');
            }
            if (!empty($tramitacao['dtTramitacaoRecebida'])) {
                $objDateTimedtTramitacaoRecebida = new \DateTime($tramitacao['dtTramitacaoRecebida']);
                $objDateTimedtTramitacaoRecebida = $objDateTimedtTramitacaoRecebida->format('d/m/Y H:i:s');
            }

            $resultArray[] = [
                'Emissor' => $Emissor,
                'dtTramitacaoEnvio' => $objDateTimeDtTramitacaoEnvio,
                'Receptor' => $Receptor,
                'dtTramitacaoRecebida' => $objDateTimedtTramitacaoRecebida,
                'Estado' => $tramitacao['Estado'],
                'Destino' => $tramitacao['Destino'],
                'meDespacho' => $meDespacho,
            ];
        }

        return $resultArray;
    }

}
