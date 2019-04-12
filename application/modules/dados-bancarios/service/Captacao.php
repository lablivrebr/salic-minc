<?php

namespace Application\Modules\DadosBancarios\Service\Captacao;

class Captacao
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

    public function buscarCaptacao()
    {
        $idPronac = $this->request->idPronac;
        $dtReciboInicio = $this->request->dtReciboInicio;
        $dtReciboFim = $this->request->dtReciboFim;

        if (strlen($idPronac) > 7) {
            $idPronac = \Seguranca::dencrypt($idPronac);
        }
        if (!empty($idPronac)) {
            $Captacao = new \Captacao();

            $where = array();
            $where['p.idPronac = ?'] = $idPronac;

            if (!empty($dtReciboInicio) && !empty($dtReciboFim)) {
                $di = ConverteData($dtReciboInicio, 13)." 00:00:00";
                $df = ConverteData($dtReciboFim, 13)." 00:00:00";
                $where["DtRecibo BETWEEN '$di' AND '$df'"] = '';
            }

            $dadosCaptacao = $Captacao->painelDadosBancariosCaptacao($where, ['DtRecibo DESC'], null, null, false)->toArray();

            $valorTotal = $this->calculaValorTotalCaptado($dadosCaptacao);

            $result['vlTotal'] = $valorTotal;
            $result['captacao'] = $dadosCaptacao;

            return $result;
        }
    }

    private function calculaValorTotalCaptado($value) {
        foreach ($value as &$item) {
            $valorTotal += $item['CaptacaoReal'];
        }

        return $valorTotal;
    }

    public function resultadoExtratoDeContaCaptacaoAction()
    {
        //DEFINE PARAMETROS DE ORDENACAO / QTDE. REG POR PAG. / PAGINACAO
        if (isset($this->_request->qtde)) {
            $this->intTamPag = $this->_request->qtde;
        } else {
            $this->intTamPag = '10';
        }
        $order = array();

        //==== parametro de ordenacao  ======//
        if (isset($this->_request->ordem)) {
            $ordem = $this->_request->ordem;
            if ($ordem == "ASC") {
                $novaOrdem = "DESC";
            } else {
                $novaOrdem = "ASC";
            }
        } else {
            $ordem = "ASC";
            $novaOrdem = "ASC";
        }

        //==== campo de ordenacao  ======//
        if (isset($this->_request->campo)) {
            $campo = $this->_request->campo;
            $order = array($campo." ".$ordem);
            $ordenacao = "&campo=".$campo."&ordem=".$ordem;
        } else {
            $campo = null;
            $order = array(1); //PRONAC
            $ordenacao = null;
        }

        $pag = 1;
//        $post  = Zend_Registry::get('get');
        if (isset($post->pag)) {
//            $pag = $post->pag;
        }
        $inicio = ($pag>1) ? ($pag-1)*$this->intTamPag : 0;

        /* ================== PAGINACAO ======================*/
        $Usuariosorgaosgrupos = new \DadosBancarios_Model_Usuariosorgaosgrupos();
        $orgaoSuperior = $Usuariosorgaosgrupos->buscarOrgaoSuperiorUnico(272);

        $where = array();
        $where['c.siTransferenciaRecurso = ?'] = 0;
        $where['o.idSecretaria = ?'] = $orgaoSuperior->org_superior;

        if ((isset($_POST['pronac']) && !empty($_POST['pronac'])) || (isset($_GET['pronac']) && !empty($_GET['pronac']))) {
            $where["c.AnoProjeto+c.Sequencial = ?"] = isset($_POST['pronac']) ? $_POST['pronac'] : $_GET['pronac'];
            $this->view->pronacProjeto = isset($_POST['pronac']) ? $_POST['pronac'] : $_GET['pronac'];
        }

        if (isset($_POST['tipoFiltro']) || isset($_GET['tipoFiltro'])) {
            $filtro = isset($_POST['tipoFiltro']) ? $_POST['tipoFiltro'] : $_GET['tipoFiltro'];
            $this->view->filtro = $filtro;
            switch ($filtro) {
                case '': //captou 20%
                    $where['SAC.dbo.fnPercentualCaptado(c.AnoProjeto, c.Sequencial) >= ?'] = 20;
                    break;
                case 'nc': //nao captou 20%
                    $where['SAC.dbo.fnPercentualCaptado(c.AnoProjeto, c.Sequencial) < ?'] = 20;
                    break;
            }
        } else {
            $where['SAC.dbo.fnPercentualCaptado(c.AnoProjeto, c.Sequencial) >= ?'] = 20;
        }

        $tbCaptacao = new \DadosBancarios_Model_Captacao();
        $total = $tbCaptacao->buscaExtratoCaptacao($where, $order, null, null, true);
        $fim = $inicio + $this->intTamPag;

        $totalPag = (int)(($total % $this->intTamPag == 0)?($total/$this->intTamPag):(($total/$this->intTamPag)+1));
        $tamanho = ($fim > $total) ? $total - $inicio : $this->intTamPag;

        if ((isset($_POST['pronac']) && !empty($_POST['pronac'])) || (isset($_GET['pronac']) && !empty($_GET['pronac']))) {
            $busca = $tbCaptacao->buscaExtratoCaptacao($where, $order);
        } else {
            $busca = $tbCaptacao->buscaExtratoCaptacao($where, $order, $tamanho, $inicio);
        }

        $paginacao = array(
            "pag"=>$pag,
            "qtde"=>$this->intTamPag,
            "campo"=>$campo,
            "ordem"=>$ordem,
            "ordenacao"=>$ordenacao,
            "novaOrdem"=>$novaOrdem,
            "total"=>$total,
            "inicio"=>($inicio+1),
            "fim"=>$fim,
            "totalPag"=>$totalPag,
            "Itenspag"=>$this->intTamPag,
            "tamanho"=>$tamanho
        );
//        $data = ;
        $dataEncoded = \TratarArray::utf8EncodeArray($busca->toArray());

        return json_encode([
            "data"=> $dataEncoded,
            "itensPagina"=>$this->intTamPag,
            "paginacao"=>$paginacao,
            "totalItens"=>$total
        ], JSON_PRETTY_PRINT);
    }
}

