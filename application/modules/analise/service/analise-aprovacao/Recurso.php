<?php

namespace Application\Modules\Analise\Service\AnaliseAprovacao;

use Seguranca;

class Recurso implements \MinC\Servico\IServicoRestZend
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

    public function buscarRecursos()
    {
        $idPronac = $this->request->idPronac;

        if (strlen($idPronac) > 7) {
            $idPronac = \Seguranca::dencrypt($idPronac);
        }
        if (!empty($idPronac)) {
            $Projetos = new \Projetos();

            $tbRecurso = new \tbRecurso();
            $recursos = $tbRecurso->buscarDadosRecursos(['IdPRONAC = ?'=> $idPronac])->toArray();

            $data=[];
            if (count($recursos) > 0) {
                foreach ($recursos as $recurso) {
                    if ($recurso['tpSolicitacao'] == 'PI'
                        || $recurso['tpSolicitacao'] == 'EO'
                        || $recurso['tpSolicitacao'] == 'OR'
                    ) {
                        $planoDistribuicaoProduto = new \Proposta_Model_DbTable_PlanoDistribuicaoProduto();
                        $dadosProdutos = $planoDistribuicaoProduto->buscarProdutosProjeto($recurso['IdPRONAC'])->toArray();

                        $tipoDaPlanilha = 2; // 2=Planilha Aprovada Parecerista
//                                    if ($data['tpSolicitacao'] == 'EO' || $data['tpSolicitacao'] == 'OR') {
                            $tipoDaPlanilha = 4; // 4=Cortes Or�ament�rios Aprovados
//                            }
//                                $spPlanilhaOrcamentaria = new \spPlanilhaOrcamentaria();
//
//                                $planilhaOrcamentaria = $spPlanilhaOrcamentaria->exec($recurso['IdPRONAC'], $tipoDaPlanilha);
//                                $data['planilhaRecurso'] = $this->montarPlanilhaOrcamentaria($planilhaOrcamentaria, $tipoDaPlanilha);
                    }

                    if ($recurso['tpSolicitacao'] == 'PI'
                        || $recurso['tpSolicitacao'] == 'EO'
                        || $recurso['tpSolicitacao'] == 'OR'
                        || $recurso['tpSolicitacao'] == 'EN'
                    ) {
                        $projetosENRecurso = $Projetos->buscaAreaSegmentoProjeto($recurso['IdPRONAC'])->toArray();

                        $projetosENRecurso['artigo'] = $this->obterArtigoEnquadramento(
                            $projetosENRecurso['cdSegmento']
                        );
                        $parecer = new \Parecer();
                        $parecerRecurso = $parecer->buscar(
                            [
                                'IdPRONAC = ?' => $recurso['IdPRONAC'],
                                'TipoParecer in (?)' => array(1,7),
                                'stAtivo = ?' => 1
                            ]
                        );
                        $parecer = $parecerRecurso ? $parecerRecurso->toArray() : [];

                    }

                    $data[] = [
                        'dadosRecurso' => $recurso ,
                        'desistenciaRecurso' => $recurso['siRecurso'] == 0 ? true : false,
                        'produtosRecurso' => $dadosProdutos,
                        'projetosENRecurso' => $projetosENRecurso,
                        'parecerRecurso' => $parecer
                    ];
                }
            }
        }
                return $data;
    }

    private function obterArtigoEnquadramento($segmento) {
        return in_array($segmento, \Segmento::SEGMENTOS_ARTIGO_18) ? 'Artigo 18' : 'Artigo 26';
    }
}
