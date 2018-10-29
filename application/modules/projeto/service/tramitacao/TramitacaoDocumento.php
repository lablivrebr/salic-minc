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

        return $tramitacoes;
    }

    private function obterTramitacaoDocumento($tramitacoes)
    {
        $resultArray = [];
        foreach ($tramitacoes as $tramitacao) {
            $dsTipoDocumento = html_entity_decode(utf8_encode($tramitacao['dsTipoDocumento']));
            $noArquivo = html_entity_decode(utf8_encode($tramitacao['noArquivo']));
            $objDateTimedtDocumento = new \DateTime($tramitacao['dtDocumento']);
            $objDateTimedtJuntada = new \DateTime($tramitacao['dtJuntada']);

            $resultArray[] = [
                'idDocumento' => $tramitacao['idDocumento'],
                'dsTipoDocumento' => $dsTipoDocumento,
                'dtDocumento' => $objDateTimedtDocumento->format('d/m/Y H:i:s'),
                'noArquivo' => $noArquivo,
                'dtAnexacao' => $objDateTimedtJuntada->format('d/m/Y H:i:s'),
                'Usuario' => $tramitacao['Usuario'],
                'idLote' => $tramitacao['idLote'],
                'Situacao' => $tramitacao['Situacao'],
            ];
        }

        return $resultArray;
    }

}
