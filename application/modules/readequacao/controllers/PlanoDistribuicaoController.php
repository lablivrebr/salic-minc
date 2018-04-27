<?php

class Readequacao_PlanodistribuicaoController extends MinC_Controller_Action_Abstract
{
    public function init()
    {
        parent::init();
    }

    public function obterPlanoDistribuicaoReadequacaoAjaxAction()
    {
        $this->_helper->layout->disableLayout();

        $dados = [];

        $this->_helper->layout->disableLayout();

        $idPreProjeto = $this->_request->getParam('idPronac');

        try {

            if (empty($idPreProjeto)) {
                throw new Exception("Proposta inv&aacute;lida");
            }

            $tbPlanoDistribuicao = new Proposta_Model_DbTable_PlanoDistribuicaoProduto();
            $dados['planodistribuicaoproduto'] = $tbPlanoDistribuicao->buscar(['idProjeto = ?' => $idPreProjeto])->toArray();
            $dados['tbdetalhaplanodistribuicao'] = $tbPlanoDistribuicao->buscarPlanoDistribuicaoDetalhadoByIdProjeto($idPreProjeto);
            $dados = TratarArray::prepararArrayMultiParaJson($dados);

            $this->_helper->json(['data' => $dados, 'success' => 'true']);
        } catch (Exception $e) {
            $this->_helper->json(['msg' => utf8_encode($e->getMessage()), 'data' => $dados, 'success' => 'false']);
        }
    }

    public function carregarPlanosDeDistribuicaoAction()
    {
        $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
        $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo'); // cria a sess�o com o grupo ativo
        $this->view->idPerfil = $GrupoAtivo->codGrupo;

        $idPronac = $this->_request->getParam("idPronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        $tbPlanoDistribuicao = new tbPlanoDistribuicao();
        $planosDistribuicao = $tbPlanoDistribuicao->buscarPlanosDistribuicaoReadequacao($idPronac, 'tbPlanoDistribuicao');

        if (count($planosDistribuicao) == 0) {
            $planosDistribuicao = $tbPlanoDistribuicao->buscarPlanosDistribuicaoReadequacao($idPronac, 'PlanoDistribuicaoProduto');
        }

        $Produtos = new Produto();
        $produtos = $Produtos->buscar(array('stEstado=?' => 0), array('Descricao'));

        $Verificacao = new Verificacao();
        $posicoesLogomarca = $Verificacao->buscar(array('idTipo=?' => 3), array('Descricao'));

        $Area = new Area();
        $areas = $Area->buscar(array('Codigo != ?' => 7), array('Descricao'));

        $get = Zend_Registry::get('get');
        $link = isset($get->link) ? true : false;

        $this->montaTela(
            'plano-distribuicao/carregar-planos-de-distribuicao.phtml',
            array(
                'idPronac' => $idPronac,
                'planosDistribuicao' => $planosDistribuicao,
                'produtos' => $produtos,
                'posicoesLogomarca' => $posicoesLogomarca,
                'areas' => $areas,
                'link' => $link
            )
        );
    }

    public function carregarPlanosDeDistribuicaoReadequacoesAction()
    {
        $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
        $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo'); // cria a sess�o com o grupo ativo
        $this->view->idPerfil = $GrupoAtivo->codGrupo;

        $idPronac = $this->_request->getParam("idPronac");
        $idReadequacao = $this->_request->getParam("idReadequacao");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        $tbPlanoDistribuicao = new tbPlanoDistribuicao();
        $planosDistribuicao = $tbPlanoDistribuicao->buscarPlanosDistribuicaoConsolidadoReadequacao($idReadequacao);

        $tbReadequacao = new Readequacao_Model_tbReadequacao();
        $dadosReadequacao = $tbReadequacao->buscar(array('idReadequacao=?' => $idReadequacao))->current();
        $siEncaminhamento = $dadosReadequacao->siEncaminhamento;

        $get = Zend_Registry::get('get');
        $link = isset($get->link) ? true : false;

        $this->montaTela(
            'plano-distribuicao/carregar-planos-de-distribuicao.phtml',
            array(
                'idPronac' => $idPronac,
                'planosDeDistribuicao' => $planosDistribuicao,
                'link' => $link,
                'idReadequacao' => $idReadequacao,
                'siEncaminhamento' => $siEncaminhamento
            )
        );
    }

    public function incluirPlanosDeDistribuicaoAction()
    {
        $this->_helper->layout->disableLayout();
        $idPronac = $this->_request->getParam("idPronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        //VERIFICA SE JA POSSUI OS PLANOS DE DISTRIBUI��O NA TABELA tbPlanoDistribuicao (READEQUACAO), SE N�O TIVER, COPIA DA ORIGINAL, E DEPOIS INCLUI O ITEM DESEJADO.
        $tbPlanoDistribuicao = new tbPlanoDistribuicao();
        $readequacaoPDDist = $tbPlanoDistribuicao->buscar(array('idPronac=?' => $idPronac, 'stAtivo=?' => 'S'));

        $planosAtivos = $tbPlanoDistribuicao->buscarPlanosDistribuicaoReadequacao($idPronac);

        if (count($readequacaoPDDist) == 0) {
            $planosCopiados = array();
            foreach ($planosAtivos as $value) {
                $planosCopiados['idReadequacao'] = null;
                $planosCopiados['idProduto'] = $value->idProduto;
                $planosCopiados['cdArea'] = $value->idArea;
                $planosCopiados['cdSegmento'] = $value->idSegmento;
                $planosCopiados['idPosicaoLogo'] = $value->idPosicaoDaLogo;
                $planosCopiados['qtProduzida'] = $value->QtdeProduzida;
                $planosCopiados['qtPatrocinador'] = $value->QtdePatrocinador;
                $planosCopiados['qtOutros'] = $value->QtdeOutros;
                $planosCopiados['qtProponente'] = $value->QtdeProponente;
                $planosCopiados['qtVendaNormal'] = $value->QtdeVendaNormal;
                $planosCopiados['qtVendaPromocional'] = $value->QtdeVendaPromocional;
                $planosCopiados['vlUnitarioNormal'] = $value->PrecoUnitarioNormal;
                $planosCopiados['vlUnitarioPromocional'] = $value->PrecoUnitarioPromocional;
                $planosCopiados['stPrincipal'] = $value->stPrincipal;
                $planosCopiados['tpSolicitacao'] = 'N'; # N - nenhuma, I - inclusao, A - alteracao
                $planosCopiados['stAtivo'] = 'S';
                $planosCopiados['idPronac'] = $idPronac;
                $tbPlanoDistribuicao->inserir($planosCopiados);
            }
        }

        $verificaPlanoRepetido = $tbPlanoDistribuicao->buscar(array('idPronac=?' => $idPronac, 'stAtivo=?' => 'S', 'idProduto=?' => $_POST['newPlanoDistribuicao'], 'idReadequacao IS NULL' => ''));
        if (count($verificaPlanoRepetido) == 1) {
            $QtdeProduzida = $_POST['newQntdNormal'] + $_POST['newQntdPromocional'] + $_POST['newQntdPatrocinador'] + $_POST['newQntdPopulacaoBaixaRenda'] + $_POST['newQntdDivulgacao'];
            $preconormal = str_replace(",", ".", str_replace(".", "", $_POST['newVlNormal']));
            $precopromocional = str_replace(",", ".", str_replace(".", "", $_POST['newVlPromocional']));

            /* DADOS PARA ATUALIZACAO */
            $dadosInclusao = array();
            $dadosInclusao['qtProduzida'] = $QtdeProduzida;
            $dadosInclusao['qtPatrocinador'] = $_POST['newQntdPatrocinador'];
            $dadosInclusao['qtProponente'] = $_POST['newQntdDivulgacao'];
            $dadosInclusao['qtOutros'] = $_POST['newQntdPopulacaoBaixaRenda'];
            $dadosInclusao['qtVendaNormal'] = $_POST['newQntdNormal'];
            $dadosInclusao['qtVendaPromocional'] = $_POST['newQntdPromocional'];
            $dadosInclusao['vlUnitarioNormal'] = $preconormal;
            $dadosInclusao['vlUnitarioPromocional'] = $precopromocional;
            $dadosInclusao['tpSolicitacao'] = 'A'; # N - nenhuma, I - inclusao, A - alteracao
            $dadosInclusao['stAtivo'] = 'S';
            $where = ['idPronac = ?' => $idPronac, 'idProduto = ?' => $_POST['newPlanoDistribuicao']];
            $update = $tbPlanoDistribuicao->update($dadosInclusao, $where);

            if ($update) {
                //$jsonEncode = json_encode($dadosPlanilha);
                $this->_helper->json(array('resposta' => true));
            } else {
                $this->_helper->json(array('resposta' => false, 'msg' => "Erro ao salvar!"));
            }
        } else {
            $msg = utf8_encode('N&atilde;o foi poss&iacute;vel salvar sua solicita&ccedil;&atilde;o, delete a readequa&ccedil;&atilde;o antes de alterar!');
            $this->_helper->json(array('resposta' => false, 'msg' => $msg));
        }
        $this->_helper->viewRenderer->setNoRender(true);
    }

    /*
     * @deprecated: 27/04/2018 - nao utilizado
     */
    public function excluirPlanoDeDistribuicaoAction()
    {
        $this->_helper->layout->disableLayout();
        $idPlanoDistribuicao = $this->_request->getParam("idPlanoDistribuicao");
        $idPronac = $this->_request->getParam("idPronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        //VERIFICA SE JA POSSUI OS PLANOS DE DISTRIBUI��O NA TABELA tbPlanoDistribuicao (READEQUACAO), SE N�O TIVER, COPIA DA ORIGINAL, E DEPOIS INCLUI O ITEM DESEJADO.
        $tbPlanoDistribuicao = new tbPlanoDistribuicao();
        $readequacaoPDDist = $tbPlanoDistribuicao->buscar(array('idPronac=?' => $idPronac, 'stAtivo=?' => 'S'));
        $planosAtivos = $tbPlanoDistribuicao->buscarPlanosDistribuicaoReadequacao($idPronac);

        if (count($readequacaoPDDist) == 0) {
            $planosCopiados = array();
            foreach ($planosAtivos as $value) {
                $planosCopiados['idReadequacao'] = null;
                $planosCopiados['idProduto'] = $value->idProduto;
                $planosCopiados['cdArea'] = $value->idArea;
                $planosCopiados['cdSegmento'] = $value->idSegmento;
                $planosCopiados['idPosicaoLogo'] = $value->idPosicaoDaLogo;
                $planosCopiados['qtProduzida'] = $value->QtdeProduzida;
                $planosCopiados['qtPatrocinador'] = $value->QtdePatrocinador;
                $planosCopiados['qtOutros'] = $value->QtdeOutros;
                $planosCopiados['qtProponente'] = $value->QtdeProponente;
                $planosCopiados['qtVendaNormal'] = $value->QtdeVendaNormal;
                $planosCopiados['qtVendaPromocional'] = $value->QtdeVendaPromocional;
                $planosCopiados['vlUnitarioNormal'] = $value->PrecoUnitarioNormal;
                $planosCopiados['vlUnitarioPromocional'] = $value->PrecoUnitarioPromocional;
                $planosCopiados['stPrincipal'] = $value->stPrincipal;
                $planosCopiados['tpSolicitacao'] = 'N';
                $planosCopiados['stAtivo'] = 'S';
                $planosCopiados['idPronac'] = $idPronac;
                $tbPlanoDistribuicao->inserir($planosCopiados);
            }
        }

        /* DADOS DO ITEM PARA EXCLUSAO L�GICA DO ITEM DA READEQUACAO */
        $dados = array();
        $dados['tpSolicitacao'] = 'E';

        $itemPDDist = $tbPlanoDistribuicao->buscar(array('idPlanoDistribuicao=?' => $idPlanoDistribuicao))->current();
        if ($itemPDDist) {
            if ($itemPDDist->tpSolicitacao == 'I') {
                $exclusaoLogica = $tbPlanoDistribuicao->delete(array('idPlanoDistribuicao = ?' => $idPlanoDistribuicao));
            } else {
                $where = "stAtivo = 'S' AND idPlanoDistribuicao = $idPlanoDistribuicao";
                $exclusaoLogica = $tbPlanoDistribuicao->update($dados, $where);
            }
        } else {
            $PlanoDistribuicao = new PlanoDistribuicao();
            $itemPDDist = $PlanoDistribuicao->find(array('idPlanoDistribuicao=?' => $idPlanoDistribuicao))->current();
            $dadosArray = array(
                'idProduto =?' => $itemPDDist->idProduto,
                'idPronac =?' => $idPronac,
                'stAtivo =?' => 'S',
            );
            $itemPDDist = $tbPlanoDistribuicao->buscar($dadosArray)->current();
            $where = "stAtivo = 'S' AND idPlanoDistribuicao = $itemPDDist->idPlanoDistribuicao";
            $exclusaoLogica = $tbPlanoDistribuicao->update($dados, $where);
        }

        if ($exclusaoLogica) {
            //$jsonEncode = json_encode($dadosPlanilha);
            $this->_helper->json(array('resposta' => true));
        } else {
            $this->_helper->json(array('resposta' => false));
        }
        $this->_helper->viewRenderer->setNoRender(true);
    }
}