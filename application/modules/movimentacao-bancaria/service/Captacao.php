<?php

namespace Application\Modules\MovimentacaoBancaria\Service;

class Captacao
{
    public function resultadoExtratoDeContaCaptacaoAction()
    {
        //DEFINE PARAMETROS DE ORDENACAO / QTDE. REG POR PAG. / PAGINACAO
        if (isset($this->_request->qtde)) {
            $this->intTamPag = $this->_request->qtde;
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
        $Usuariosorgaosgrupos = new \MovimentacaoBancaria_Model_Usuariosorgaosgrupos();
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

        $tbCaptacao = new \MovimentacaoBancaria_Model_Captacao();
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

        $this->view->paginacao     = $paginacao;
        $this->view->qtdRegistros  = $total;
        $this->view->dados         = $busca;
        $this->view->intTamPag     = $this->intTamPag;
    }

}
