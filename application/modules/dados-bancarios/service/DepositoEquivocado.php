<?php

namespace Application\Modules\DadosBancarios\Service\DepositoEquivocado;

class DepositoEquivocado
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

    public function buscarDepositosEquivocados()
    {
        $idPronac = $this->request->idPronac;
        $dtDevolucaoInicio = $this->request->dtDevolucaoInicio;
        $dtDevolucaoFim = $this->request->dtDevolucaoFim;

        if (strlen($idPronac) > 7) {
            $idPronac = \Seguranca::dencrypt($idPronac);
        }

        $where = ['idPronac = ?' => $idPronac, 'nrLote = ?' => -1];

        if (!empty($dtDevolucaoInicio) && !empty($dtDevolucaoFim)) {
            $di = ConverteData($dtDevolucaoInicio, 13)." 00:00:00";
            $df = ConverteData($dtDevolucaoFim, 13)." 00:00:00";
            $where["dtLote BETWEEN '$di' AND '$df'"] = '';
        }

        $aporteModel = new \tbAporteCaptacao();
        $dados = $aporteModel->pesquisarDepositoEquivocado($where)->toArray();

        return $dados;
    }
}
