<?php

namespace Application\Modules\PrestacaoContas\Service\RelatoriosTrimestrais;


class RelatoriosTrimestrais implements \MinC\Servico\IServicoRestZend
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

    public function listaRelatoriosTrimestrais()
    {
        $idPronac = $this->request->idPronac;

        if (strlen($idPronac) > 7) {
            $idPronac = \Seguranca::dencrypt($idPronac);
        }

        if (!empty($idPronac)) {
            $tbComprovanteTrimestral = new \ComprovacaoObjeto_Model_DbTable_TbComprovanteTrimestral();
            $qtdRelatorioCadastrados = $tbComprovanteTrimestral->buscarComprovantes(array('idPronac=?'=>$idPronac), true, array('nrComprovanteTrimestral'))->toArray();
        }

        $relatoriosTrimestrais = $this->montaArrayRelatoriosTrimestrais($qtdRelatorioCadastrados);
        return $relatoriosTrimestrais;
    }

    private function montaArrayRelatoriosTrimestrais($dados)
    {
        if ($dados) {
            foreach ($dados as $item) {
                $result[] = [
                    'dtInicio' => $item['dtInicioPeriodo'],
                    'dtFim' => $item['dtFimPeriodo'],
                    'dtComprovante' => $item['dtComprovante'],
                    'siComprovanteTrimestral' => $this->situacaoComprovanteTrimestral($item['siComprovanteTrimestral']),
                ];
    
            }
            return $result;
        }
        return [];
    }

    private function situacaoComprovanteTrimestral($dado)
    {
        switch ($dado) {
            case 1:
                $result = 'Em cadastramento';
                break;
            case 2:
                $result = 'Enviado';
                break;
            case 3:
            case 4:
            case 5:
                $result = 'Em an&aacute;lise';
                break;
            case 6:
                $result = 'Analisado';
                break;
            default:
                $result = ' - ';
        }

        return $result;
    }
}
