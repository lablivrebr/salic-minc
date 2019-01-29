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
                $tbCustosVinculadosMapper = new \Proposta_Model_DbTable_TbCustosVinculados();
                $itensCustosVinculados = $tbCustosVinculadosMapper->buscarCustosVinculados(['idProjeto = ?' => $rsProjeto->idProjeto]);
            }
        }

        $CustosVinculadosArray = $this->montaArrayCustosVinculados($itensCustosVinculados);
        $PropostaArray = $this->montaArrayProposta($rsProposta);

        $resultArray = [];

        $resultArray['CustosVinculados'] = $CustosVinculadosArray;
        $resultArray['Proposta'] = $PropostaArray;

        $resultArray = \TratarArray::utf8EncodeArray($resultArray);

        return $resultArray;
    }

    private function montaArrayCustosVinculados($itensCustosVinculados) {
        foreach ($itensCustosVinculados as $item) {

            $CustosVinculadosArray[] = [
                'Descricao' => $item['item'],
                'Percentual' => $item['pcCalculo'],
                'dtCadastro' => $item['dtCadastro']
            ];

        }
        return $CustosVinculadosArray;
    }

    private function montaArrayProposta($rsProposta) {
        $PropostaArray = [];
        $Objetivos = $rsProposta['Objetivos'];
        $Justificativa = $rsProposta['Justificativa'];
        $Acessibilidade = $rsProposta['Acessibilidade'];
        $DemocratizacaoDeAcesso = $rsProposta['DemocratizacaoDeAcesso'];
        $EtapaDeTrabalho = $rsProposta['EtapaDeTrabalho'];
        $FichaTecnica = $rsProposta['FichaTecnica'];
        $ImpactoAmbiental = $rsProposta['ImpactoAmbiental'];
        $EspecificacaoTecnica = $rsProposta['EspecificacaoTecnica'];
        $OutrasInformacoes = $rsProposta['EstrategiadeExecucao'];
        $Sinopse = $rsProposta['Sinopse'];

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
