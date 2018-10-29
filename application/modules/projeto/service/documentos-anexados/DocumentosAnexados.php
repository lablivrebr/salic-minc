<?php

namespace Application\Modules\Projeto\Service\DocumentosAnexados;

use Seguranca;

class DocumentosAnexados implements \MinC\Servico\IServicoRestZend
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

    public function buscarDocumentosAnexados()
    {
        $idPronac = $this->request->idPronac;

        if (strlen($idPronac) > 7) {
            $idPronac = \Seguranca::dencrypt($idPronac);
        }

        $Projetos = new \Projetos();
        $projeto = $Projetos->buscar(array('IdPRONAC = ?' => $idPronac))->current();

        $tbDoc = new \paDocumentos();
        $documentos = $tbDoc->marcasAnexadas($idPronac);
        $docs = $this->montaArrayDocumentos($documentos);

        $resultArray = [];

        $resultArray['documentos'] = $docs;

        return $resultArray;
    }

    private function montaArrayDocumentos($documentos) {
        $docs = [];
        foreach ($documentos as $item) {
            switch ($item->Anexado){
                case 1:
                    $item->Anexado = 'Documento do Proponente';
                    break;
                case 2:
                    $item->Anexado = 'Documento da Proposta';
                    break;
                case 3:
                    $item->Anexado = 'Documento do Projeto Anexado no Minc';
                    break;
                case 4:
                    $item->Anexado = 'Documento do Projeto';
                    break;
                case 5:
                    $item->Anexado = 'Documento do Projeto';
                    break;
            }
            $itemArray = [
                'Anexado' => $item->Anexado,
                'Data' => $item->Data,
                'Descricao' => utf8_encode($item->Descricao),
                'NoArquivo' => utf8_encode($item->NoArquivo),
                'idArquivo' => $item->idDocumentosAgentes,
                'AgenteDoc' => $item->AgenteDoc,
            ];
            $docs[] = $itemArray;
        }

        return $docs;
    }
}
