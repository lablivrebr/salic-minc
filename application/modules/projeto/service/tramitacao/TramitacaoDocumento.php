<?php

namespace Application\Modules\Projeto\Service\Tramitacao;

use Seguranca;

class TramitacaoDocumento implements \MinC\Servico\IServicoRestZend
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

    public function buscarTramitacaoDocumentoProjeto()
    {
        $idPronac = $this->request->idPronac;

        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        $tblHistDoc = new \tbHistoricoDocumento();
        $total = $tblHistDoc->buscarHistoricoTramitacaoDocumento(array("p.IdPronac =?"=>$idPronac), null, null, null, false);

        $tramitacoes = $this->obterTramitacaoDocumento($total);

        $tramitacoes = \TratarArray::utf8EncodeArray($tramitacoes);

        return $tramitacoes;
    }

    private function obterTramitacaoDocumento($tramitacoes)
    {
        $resultArray = [];
        foreach ($tramitacoes as $tramitacao) {
            $dsTipoDocumento = $tramitacao['dsTipoDocumento'];
            $noArquivo = $tramitacao['noArquivo'];
            $objDateTimedtDocumento = ' ';
            $objDateTimedtJuntada = ' ';

            if (!empty($tramitacao['dtDocumento'])) {
                $objDateTimedtDocumento = new \DateTime($tramitacao['dtDocumento']);
                $objDateTimedtDocumento = $objDateTimedtDocumento->format('d/m/Y');
            }

            if (!empty($tramitacao['dtJuntada'])) {
                $objDateTimedtJuntada = new \DateTime($tramitacao['dtJuntada']);
                $objDateTimedtJuntada = $objDateTimedtJuntada->format('d/m/Y');
            }

            $resultArray[] = [
                'idDocumento' => $tramitacao['idDocumento'],
                'dsTipoDocumento' => $dsTipoDocumento,
                'dtDocumento' => $objDateTimedtDocumento,
                'noArquivo' => $noArquivo,
                'dtAnexacao' => $objDateTimedtJuntada,
                'Usuario' => $tramitacao['Usuario'],
                'idLote' => $tramitacao['idLote'],
                'Situacao' => $tramitacao['Situacao'],
            ];
        }

        return $resultArray;
    }

}
