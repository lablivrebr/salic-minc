<?php

namespace Application\Modules\Projeto\Service\LocalRealizacaoDeslocamento;

use Seguranca;

class LocalRealizacaoDeslocamento implements \MinC\Servico\IServicoRestZend
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

    public function buscarLocalRealizacaoDeslocamento()
    {
        $idPronac = $this->request->idPronac;

        if (strlen($idPronac) > 7) {
            $idPronac = \Seguranca::dencrypt($idPronac);
        }

        $Projetos = new \Projetos();
        $result = $Projetos->buscar(array('IdPRONAC = ?' => $idPronac))->current();

        if (!empty($idPronac)) {
            $buscarLocalRealizacao = \RealizarAnaliseProjetoDAO::localrealizacao($idPronac);
            $buscarDeslocamento = \RealizarAnaliseProjetoDAO::deslocamento($idPronac);
        }

        $resultArray = [];

        $localRealizacoes = $this->montaArrayLocalRealizacao($buscarLocalRealizacao);
        $deslocamento = $this->montaArrayDeslocamento($buscarDeslocamento);

        $resultArray['localRealizacoes'] = $localRealizacoes;
        $resultArray['Deslocamento'] = $deslocamento;

        return $resultArray;
    }

    private function montaArrayLocalRealizacao($local) {
        $localRealizacoes = [];

        foreach ($local as $item) {
            $descricao = html_entity_decode(utf8_encode($item->Descricao));
            $uf = html_entity_decode(utf8_encode($item->UF));
            $cidade = html_entity_decode(utf8_encode($item->Cidade));

            $localRealizacoes[] = [
                'Descricao' => $descricao,
                'UF' => $uf,
                'Cidade' => $cidade,
            ];

        }
        return $localRealizacoes;
    }

    private function montaArrayDeslocamento($deslocamento) {
        $deslocamentoResult = [];

        foreach ($deslocamento as $item) {
            $PaisOrigem = html_entity_decode(utf8_encode($item->PaisOrigem));
            $UFOrigem = html_entity_decode(utf8_encode($item->UFOrigem));
            $MunicipioOrigem = html_entity_decode(utf8_encode($item->MunicipioOrigem));
            $PaisDestino = html_entity_decode(utf8_encode($item->PaisDestino));
            $UFDestino = html_entity_decode(utf8_encode($item->UFDestino));
            $MunicipioDestino = html_entity_decode(utf8_encode($item->MunicipioDestino));

            $deslocamentoResult[] = [
                'Qtde' => $item->Qtde,
                'PaisOrigem' => $PaisOrigem,
                'UFOrigem' => $UFOrigem,
                'MunicipioOrigem' => $MunicipioOrigem,
                'PaisDestino' => $PaisDestino,
                'UFDestino' => $UFDestino,
                'MunicipioDestino' => $MunicipioDestino,
            ];

        }
        return $deslocamentoResult;
    }
}
