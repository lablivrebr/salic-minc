<?php

namespace Application\Modules\Projeto\Service\DadosComplementares;

use Seguranca;

class DadosComplementares implements \MinC\Servico\IServicoRestZend
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

    public function buscarDadosComplementares()
    {
        $idPronac = $this->request->idPronac;

        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        $tblProjeto = new \Projetos();
        $projeto = $tblProjeto->buscar(array('IdPronac=?'=>$idPronac))->current();

        if (!empty($idPronac)) {
            $rsProjeto = $tblProjeto->buscar(array('IdPronac=?'=>$idPronac,'idProjeto IS NOT NULL'=>'?'))->current();

            if (is_object($rsProjeto) && count($rsProjeto) > 0) {
                $tblProposta = new \Proposta_Model_DbTable_PreProjeto();
                $rsProposta = $tblProposta->buscar(array('idPreProjeto=?'=>$rsProjeto->idProjeto))->current();
                $tbCustosVinculadosMapper = new \Proposta_Model_TbCustosVinculadosMapper();
                $itensCustosVinculados = $tbCustosVinculadosMapper->obterCustosVinculados($rsProjeto->idProjeto);
            }
        }

        $CustosVinculadosArray = $this->montaArrayCustosVinculados($itensCustosVinculados);
        $PropostaArray = $this->montaArrayProposta($rsProposta);

        $resultArray = [];

        $resultArray['CustosVinculados'] = $CustosVinculadosArray;
        $resultArray['Proposta'] = $PropostaArray;

        return $resultArray;
    }

    private function montaArrayCustosVinculados($itensCustosVinculados) {
        foreach ($itensCustosVinculados as $item) {
            $descricao = html_entity_decode(utf8_encode($item['Descricao']));

            $CustosVinculadosArray[] = [
                'Descricao' => $descricao,
                'Percentual' => $item['percentualProponente'],
            ];

        }
        return $CustosVinculadosArray;
    }

    private function montaArrayProposta($rsProposta) {
        $PropostaArray = [];
        $Objetivos = html_entity_decode(utf8_encode($rsProposta['Objetivos']));
        $Justificativa = html_entity_decode(utf8_encode($rsProposta['Justificativa']));
        $Acessibilidade = html_entity_decode(utf8_encode($rsProposta['Acessibilidade']));
        $DemocratizacaoDeAcesso = html_entity_decode(utf8_encode($rsProposta['DemocratizacaoDeAcesso']));
        $EtapaDeTrabalho = html_entity_decode(utf8_encode($rsProposta['EtapaDeTrabalho']));
        $FichaTecnica = html_entity_decode(utf8_encode($rsProposta['FichaTecnica']));
        $ImpactoAmbiental = html_entity_decode(utf8_encode($rsProposta['ImpactoAmbiental']));
        $EspecificacaoTecnica = html_entity_decode(utf8_encode($rsProposta['EspecificacaoTecnica']));
        $OutrasInformacoes = html_entity_decode(utf8_encode($rsProposta['EstrategiadeExecucao']));
        $Sinopse = html_entity_decode(utf8_encode($rsProposta['Sinopse']));

        $PropostaArray = [
            'Objetivos' => $Objetivos,
            'Justificativa' => $Justificativa,
            'Acessibilidade' => $Acessibilidade,
            'DemocratizacaoDeAcesso' => $DemocratizacaoDeAcesso,
            'EtapaDeTrabalho' => $EtapaDeTrabalho,
            'FichaTecnica' => $FichaTecnica,
            'ImpactoAmbiental' => $ImpactoAmbiental,
            'EspecificacaoTecnica' => $EspecificacaoTecnica,
            'OutrasInformacoes' => $OutrasInformacoes,
            'Sinopse' => $Sinopse,
        ];

        return $PropostaArray;
    }
}
