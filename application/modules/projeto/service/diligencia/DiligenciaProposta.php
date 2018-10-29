<?php

namespace Application\Modules\Projeto\Service\Diligencia;

class DiligenciaProposta implements \MinC\Servico\IServicoRestZend
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

    public function listaDiligenciaProjeto()
    {

    }

    public function visualizarDiligenciaProposta()
    {
        $idPreProjeto = $this->request->idPreProjeto;
        $idAvaliacaoProposta = (int) $this->request->idAvaliacaoProposta;

        if (empty($idAvaliacaoProposta) || empty($idPreProjeto)) {
            return [];
        }

        $tblPreProjeto = new \Proposta_Model_DbTable_PreProjeto();
        $diligenciasProposta = $tblPreProjeto->listarDiligenciasPreProjeto(
            [
                'pre.idPreProjeto = ?' => $idPreProjeto,
                'aval.idAvaliacaoProposta = ?' => $idAvaliacaoProposta,
                'aval.ConformidadeOK = ? '=> 0
            ]
        )->current();

        return $this->obterDiligenciaProposta($diligenciasProposta);
    }

    private function obterDiligenciaProposta($diligencia)
    {
        $Solicitacao = html_entity_decode(utf8_encode($diligencia['Solicitacao']));
        $Resposta = html_entity_decode(utf8_encode($diligencia['Resposta']));
        $nomeProjeto = html_entity_decode(utf8_encode($diligencia['nomeProjeto']));

        $objDateTimedataSolicitacao = new \DateTime($diligencia['dataSolicitacao']);
        $objDateTimedataResposta = new \DateTime($diligencia['dataResposta']);

        $resultArray = [
            'idPreprojeto' => $diligencia['pronac'],
            'dataSolicitacao' => $objDateTimedataSolicitacao->format('d/m/Y'),
            'dataResposta' => $objDateTimedataResposta->format('d/m/Y'),
            'nomeProjeto' => $nomeProjeto,
            'Solicitacao' => $Solicitacao,
            'Resposta' => $Resposta,
        ];

        return $resultArray;
    }
}
