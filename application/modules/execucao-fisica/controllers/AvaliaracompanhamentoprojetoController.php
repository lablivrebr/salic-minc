<?php

class ExecucaoFisica_AvaliaracompanhamentoprojetoController extends MinC_Controller_Action_Abstract
{
    private $intTamPag = 10;
    private $getIdOrgao = 0;

    public function init()
    {
        $this->view->title = "Salic - Sistema de Apoio às Leis de Incentivo à Cultura";
        $auth = Zend_Auth::getInstance();
        $Usuario = new UsuarioDAO();
        $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo');

        if ($auth->hasIdentity()) { // caso o usuario esteja autenticado
            // verifica as permissoes
            $PermissoesGrupo = array();
            $PermissoesGrupo[] = 122;
            $PermissoesGrupo[] = 121;
            $PermissoesGrupo[] = 129;
            $PermissoesGrupo[] = 94;  // Parecerista
            $PermissoesGrupo[] = 135; // tecnico
            $PermissoesGrupo[] = 134; // coordenador
            $PermissoesGrupo[] = 138; // tecnico de avaliacao
            $PermissoesGrupo[] = 139; // coordenador de avaliacao
            $PermissoesGrupo[] = 124; //Tecnico de Prestacao de Contas
            $PermissoesGrupo[] = 132; // Chefe de Divisao
            $PermissoesGrupo[] = 151;
            $PermissoesGrupo[] = 148;

            if (!in_array($GrupoAtivo->codGrupo, $PermissoesGrupo)) { // verifica se o grupo ativo esta no array de permissoes
                parent::message("Voc&ecirc; n&atilde;o tem permiss&atilde;o para acessar essa &aacute;rea do sistema!", "principal/index", "ALERT");
            }

            // pega as unidades autorizadas, orgaos e grupos do usuario (pega todos os grupos)
            $grupos = $Usuario->buscarUnidades(isset($auth->getIdentity()->usu_codigo) ? $auth->getIdentity()->usu_codigo : $auth->getIdentity()->IdUsuario, 21);

            // manda os dados para a visao
            $this->view->usuario = $auth->getIdentity(); // manda os dados do usuario para a visao
            $this->view->arrayGrupos = $grupos; // manda todos os grupos do usuario para a visao
            $this->view->grupoAtivo = $GrupoAtivo->codGrupo; // manda o grupo ativo do usuario para a visao
            $this->view->orgaoAtivo = $GrupoAtivo->codOrgao; // manda o orgao ativo do usuario para a visao
            $this->orgSup = $GrupoAtivo->codOrgao;
            $this->usu_orgao = $GrupoAtivo->codOrgao;

            $this->getIdOrgao = $GrupoAtivo->codOrgao;
        }
        else { // caso o usuario n&atilde;o esteja autenticado
            return $this->_helper->redirector->goToRoute(array('controller' => 'index', 'action' => 'logout'), null, true);
        }


        $idPronac = $this->_request->getParam("idPronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }
        $this->view->idPronac = $idPronac;


        //recupera ID do pre projeto (proposta)
        parent::init(); // chama o init() do pai GenericControllerNew
    }

    public function indexAction()
    {
        //** Usuario Logado ************************************************/
        $auth               = Zend_Auth::getInstance(); // pega a autenticacao
        $idusuario          = isset($auth->getIdentity()->usu_codigo) ? $auth->getIdentity()->usu_codigo : $auth->getIdentity()->IdUsuario;
        $GrupoAtivo         = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessao com o grupo ativo
        $codOrgao           = $GrupoAtivo->codOrgao; //  orgao ativo na sessao
        $codPerfil          = $GrupoAtivo->codGrupo; //  orgao ativo na sessao
        $this->view->codOrgao = $codOrgao;
        $this->view->idUsuarioLogado = $idusuario;
        /******************************************************************/

        //DEFINE PARAMETROS DE ORDENACAO / QTDE. REG POR PAG. / PAGINACAO
        if ($this->_request->getParam("qtde")) {
            $this->intTamPag = $this->_request->getParam("qtde");
        }
        $order = array();

        //==== parametro de ordenacao  ======//
        if ($this->_request->getParam("ordem")) {
            $ordem = $this->_request->getParam("ordem");
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
        if ($this->_request->getParam("campo")) {
            $campo = $this->_request->getParam("campo");
            $order = array($campo." ".$ordem);
            $ordenacao = "&campo=".$campo."&ordem=".$ordem;
        } else {
            $campo = null;
            $order = array(2); //Pronac
            $ordenacao = null;
        }

        $pag = 1;
        $get = Zend_Registry::get('get');
        if (isset($get->pag)) {
            $pag = $get->pag;
        }
        $inicio = ($pag>1) ? ($pag-1)*$this->intTamPag : 0;

        /* ================== PAGINACAO ======================*/
        $where = array();
        $where['b.Orgao = ?'] = $codOrgao;

        if ((isset($_POST['pronac']) && !empty($_POST['pronac'])) || (isset($_GET['pronac']) && !empty($_GET['pronac']))) {
            $where['AnoProjeto+Sequencial = ?'] = isset($_POST['pronac']) ? $_POST['pronac'] : $_GET['pronac'];
            $this->view->pronacProjeto = isset($_POST['pronac']) ? $_POST['pronac'] : $_GET['pronac'];
        }

        if (isset($_POST['tipoFiltro']) || isset($_GET['tipoFiltro'])) {
            $tipoFiltro = isset($_POST['tipoFiltro']) ? $_POST['tipoFiltro'] : $_GET['tipoFiltro'];
            switch ($tipoFiltro) {
                case 'emanalise': //Em an&aacute;lise
                    $tipoFiltro = 'emanalise';
                    $filtro = 'Em an&aacute;lise';
                    $where['a.siCumprimentoObjeto = ?'] = 3;
                    break;
                case 'analisados': //Analisados
                    $tipoFiltro = 'analisados';
                    $filtro = 'Analisados';
                    $where['a.siCumprimentoObjeto = ?'] = 5;
                    break;
                default: //Aguardando An&aacute;lise
                    $tipoFiltro = 'aguardando';
                    $filtro = 'Aguardando An&aacute;lise';
                    $where['a.siCumprimentoObjeto = ?'] = 2;
                    break;
            }
        } else { //Aguardando An&aacute;lise
            $tipoFiltro = 'aguardando';
            $filtro = 'Aguardando An&aacute;lise';
            $where['a.siCumprimentoObjeto = ?'] = 2;
        }

        $tbCumprimentoObjeto = new ExecucaoFisica_Model_DbTable_TbCumprimentoObjeto();
        $total = $tbCumprimentoObjeto->listaRelatorios($where, $order, null, null, true);
        $fim = $inicio + $this->intTamPag;

        $totalPag = (int)(($total % $this->intTamPag == 0)?($total/$this->intTamPag):(($total/$this->intTamPag)+1));
        $tamanho = ($fim > $total) ? $total - $inicio : $this->intTamPag;
        $busca = $tbCumprimentoObjeto->listaRelatorios($where, $order, $tamanho, $inicio);

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

        $this->view->paginacao      = $paginacao;
        $this->view->qtdRegistros   = $total;
        $this->view->dados          = $busca;
        $this->view->intTamPag      = $this->intTamPag;
        $this->view->filtro         = $filtro;
        $this->view->tipoFiltro     = $tipoFiltro;

        $vw = new vwUsuariosOrgaosGrupos();
        $usuarios = $vw->buscarUsuarios($codPerfil, $codOrgao);
        $this->view->Usuarios = $usuarios;
    }

    public function imprimirPainelAction()
    {
        $this->_helper->layout->disableLayout(); // Desabilita o Zend Layout

        //** Usuario Logado ************************************************/
        $auth               = Zend_Auth::getInstance(); // pega a autenticacao
        $idusuario          = isset($auth->getIdentity()->usu_codigo) ? $auth->getIdentity()->usu_codigo : $auth->getIdentity()->IdUsuario;
        $GrupoAtivo         = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessao com o grupo ativo
        $codOrgao           = $GrupoAtivo->codOrgao; //  orgao ativo na sessao
        $codPerfil          = $GrupoAtivo->codGrupo; //  orgao ativo na sessao
        $this->view->codOrgao = $codOrgao;
        $this->view->idUsuarioLogado = $idusuario;
        /******************************************************************/

        //DEFINE PARAMETROS DE ORDENACAO / QTDE. REG POR PAG. / PAGINACAO
        if ($this->_request->getParam("qtde")) {
            $this->intTamPag = $this->_request->getParam("qtde");
        }
        $order = array();

        //==== parametro de ordenacao  ======//
        if ($this->_request->getParam("ordem")) {
            $ordem = $this->_request->getParam("ordem");
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
        if ($this->_request->getParam("campo")) {
            $campo = $this->_request->getParam("campo");
            $order = array($campo." ".$ordem);
            $ordenacao = "&campo=".$campo."&ordem=".$ordem;
        } else {
            $campo = null;
            $order = array(2); //Pronac
            $ordenacao = null;
        }

        $pag = 1;
        $post = Zend_Registry::get('post');
        if (isset($post->pag)) {
            $pag = $post->pag;
        }
        $inicio = ($pag>1) ? ($pag-1)*$this->intTamPag : 0;

        /* ================== PAGINACAO ======================*/
        $where = array();
        $where['b.Orgao = ?'] = $codOrgao;

        if ((isset($_POST['pronac']) && !empty($_POST['pronac'])) || (isset($_GET['pronac']) && !empty($_GET['pronac']))) {
            $where['AnoProjeto+Sequencial = ?'] = isset($_POST['pronac']) ? $_POST['pronac'] : $_GET['pronac'];
            $this->view->pronacProjeto = isset($_POST['pronac']) ? $_POST['pronac'] : $_GET['pronac'];
        }
        if (isset($_POST['tipoFiltro']) || isset($_GET['tipoFiltro'])) {
            $tipoFiltro = isset($_POST['tipoFiltro']) ? $_POST['tipoFiltro'] : $_GET['tipoFiltro'];
            switch ($tipoFiltro) {
                case 'emanalise': //Em an&aacute;lise
                    $tipoFiltro = 'emanalise';
                    $filtro = 'Em an&aacute;lise';
                    $where['a.siCumprimentoObjeto = ?'] = 3;
                    break;
                case 'analisados': //Analisados
                    $tipoFiltro = 'analisados';
                    $filtro = 'Analisados';
                    $where['a.siCumprimentoObjeto = ?'] = 5;
                    break;
                default: //Aguardando An&aacute;lise
                    $tipoFiltro = 'aguardando';
                    $filtro = 'Aguardando An&aacute;lise';
                    $where['a.siCumprimentoObjeto = ?'] = 2;
                    break;
            }
        } else { //Aguardando An&aacute;lise
            $tipoFiltro = 'aguardando';
            $filtro = 'Aguardando An&aacute;lise';
            $where['a.siCumprimentoObjeto = ?'] = 2;
        }

        $tbCumprimentoObjeto = new ExecucaoFisica_Model_DbTable_TbCumprimentoObjeto();
        $total = $tbCumprimentoObjeto->listaRelatorios($where, $order, null, null, true);
        $fim = $inicio + $this->intTamPag;

        $totalPag = (int)(($total % $this->intTamPag == 0)?($total/$this->intTamPag):(($total/$this->intTamPag)+1));
        $tamanho = ($fim > $total) ? $total - $inicio : $this->intTamPag;
        $busca = $tbCumprimentoObjeto->listaRelatorios($where, $order, $tamanho, $inicio);

        if (isset($post->xls) && $post->xls) {
            $colspan = 7;
            if (isset($tipoFiltro) && $tipoFiltro != 'aguardando') {
                $colspan = 8;
            }

            $html = '';
            $html .= '<table style="border: 1px">';
            $html .='<tr><td style="border: 1px dotted black; background-color: #EAF1DD; font-size: 16px; font-weight: bold;" colspan="'.$colspan.'">Analisar Comprova&ccedil;&atilde;o do Objeto - '.$filtro.'</td></tr>';
            $html .='<tr><td style="border: 1px dotted black; background-color: #EAF1DD; font-size: 10px" colspan="'.$colspan.'">Data do Arquivo: '. Data::mostraData() .'</td></tr>';
            $html .='<tr><td colspan="'.$colspan.'"></td></tr>';

            $html .= '<tr>';
            $html .= '<th style="border: 1px dotted black; background-color: #9BBB59;">#</th>';
            $html .= '<th style="border: 1px dotted black; background-color: #9BBB59;">PRONAC</th>';
            $html .= '<th style="border: 1px dotted black; background-color: #9BBB59;">Nome do Projeto</th>';
            $html .= '<th style="border: 1px dotted black; background-color: #9BBB59;">UF</th>';
            $html .= '<th style="border: 1px dotted black; background-color: #9BBB59;">Mecanismo</th>';
            $html .= '<th style="border: 1px dotted black; background-color: #9BBB59;">Situa&ccedil;&atilde;o</th>';
            $html .= '<th style="border: 1px dotted black; background-color: #9BBB59;">Dt. Relat&aacute;rio</th>';
            if (isset($tipoFiltro) && $tipoFiltro != 'aguardando') {
                $html .= '<th style="border: 1px dotted black; background-color: #9BBB59;">T&eacute;cnico</th>';
            }
            $html .= '</tr>';

//            $pa = new paUsuariosDoPerfil();
            $vw = new vwUsuariosOrgaosGrupos();
            $usuarios = $vw->buscarUsuarios($codPerfil, $codOrgao);

            $i=1;
            foreach ($busca as $dp) {
                if ($dp->Mecanismo == 1) {
                    $mecanismo = 'Incentivo Fiscal Federal';
                } elseif ($dp->Mecanismo != 2) {
                    $mecanismo = 'FNC';
                } elseif ($dp->Mecanismo != 6) {
                    $mecanismo = 'Recursos do Tesouro';
                }

                if (isset($tipoFiltro) && $tipoFiltro != 'aguardando') {
                    foreach ($usuarios as $user) {
                        if ($user->idUsuario == $dp->idTecnicoAvaliador) {
                            $nomeTec = $user->Nome;
                        }
                    }
                }

                $html .= '<tr>';
                $html .= '<td style="border: 1px dotted black;">'.$i.'</td>';
                $html .= '<td style="border: 1px dotted black;">'.$dp->Pronac.'</td>';
                $html .= '<td style="border: 1px dotted black;">'.$dp->NomeProjeto.'</td>';
                $html .= '<td style="border: 1px dotted black;">'.$dp->UfProjeto.'</td>';
                $html .= '<td style="border: 1px dotted black;">'.$mecanismo.'</td>';
                $html .= '<td style="border: 1px dotted black;">'.$dp->Situacao.' - '.$dp->dsSituacao.'</td>';
                $html .= '<td style="border: 1px dotted black;">'.Data::tratarDataZend($dp->dtCadastro, 'Brasileiro').'</td>';
                if (isset($tipoFiltro) && $tipoFiltro != 'aguardando') {
                    $html .= '<td style="border: 1px dotted black;">'.$nomeTec.'</td>';
                }
                $html .= '</tr>';
                $i++;
            }
            $html .= '</table>';

            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: inline; filename=Analisar_Comprovacao_do_Objeto.ods;");
            echo $html;
            $this->_helper->viewRenderer->setNoRender(true);
        } else {
            $this->view->dados = $busca;
            $this->view->filtro = $filtro;
            $this->view->tipoFiltro = $tipoFiltro;

//            $pa = new paUsuariosDoPerfil();
            $vw = new vwUsuariosOrgaosGrupos();
            $usuarios = $vw->buscarUsuarios($codPerfil, $codOrgao);
            $this->view->Usuarios = $usuarios;
        }
    }

    public function visualizarRelatorioAction()
    {
        $idPronac = $this->_request->getParam("idPronac");
        $this->view->idPronac = $idPronac;
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        //****** Dados do Projeto - Cabecalho *****//
        $projetos = new Projetos();
        $DadosProjeto = $projetos->buscarProjetoXProponente(array('idPronac = ?' => $idPronac))->current();
        $this->view->DadosProjeto = $DadosProjeto;

        $tbCumprimentoObjeto = new ExecucaoFisica_Model_DbTable_TbCumprimentoObjeto();
        $DadosRelatorio = $tbCumprimentoObjeto->buscarCumprimentoObjeto(array('idPronac = ?' => $idPronac, 'siCumprimentoObjeto in (?)'=>array(2,5)));
        $this->view->DadosRelatorio = $DadosRelatorio;
        $this->view->cumprimentoDoObjeto = $tbCumprimentoObjeto;
        if (count($DadosRelatorio)==0) {
            parent::message("Relat&aacute;rio n&atilde;o encontrado!", "execucao-fisica/avaliaracompanhamentoprojeto/index", "ALERT");
        }

        $LocaisDeRealizacao = $projetos->buscarLocaisDeRealizacao($idPronac);
        $this->view->LocaisDeRealizacao = $LocaisDeRealizacao;

        $PlanoDeDivulgacao = $projetos->buscarPlanoDeDivulgacao($idPronac);
        $this->view->PlanoDeDivulgacao = $PlanoDeDivulgacao;

        $PlanoDistribuicaoProduto = new Proposta_Model_DbTable_PlanoDistribuicaoProduto();
        $PlanoDeDistribuicao = $PlanoDistribuicaoProduto->buscarPlanoDeDistribuicao($idPronac);
        $this->view->PlanoDeDistribuicao = $PlanoDeDistribuicao;

        $tbBeneficiarioProdutoCultural = new tbBeneficiarioProdutoCultural();
        $PlanosCadastrados = $tbBeneficiarioProdutoCultural->buscarPlanosCadastrados($idPronac);
        $this->view->PlanosCadastrados = $PlanosCadastrados;

        $DadosCompMetas = $projetos->buscarMetasComprovadas($idPronac);
        $this->view->DadosCompMetas = $DadosCompMetas;

        $DadosItensOrcam = $projetos->buscarItensComprovados($idPronac);
        $this->view->DadosItensOrcam = $DadosItensOrcam;

        $Arquivo = new Arquivo();
        $dadosComprovantes = $Arquivo->buscarComprovantesExecucao($idPronac);
        $this->view->DadosComprovantes = $dadosComprovantes;

        $tbTermoAceiteObra = new ExecucaoFisica_Model_DbTable_TbTermoAceiteObra();
        $AceiteObras = $tbTermoAceiteObra->buscarTermoAceiteObraArquivos(array('idPronac=?'=>$idPronac));
        $this->view->AceiteObras = $AceiteObras;

        $tbBensDoados = new ExecucaoFisica_Model_DbTable_TbBensDoados();
        $BensCadastrados = $tbBensDoados->buscarBensCadastrados(array('a.idPronac=?'=>$idPronac), array('b.Descricao'));
        $this->view->BensCadastrados = $BensCadastrados;

        if ($DadosRelatorio->siCumprimentoObjeto >= 5) {
            $Usuario = new UsuarioDAO();
            $nmUsuarioCadastrador = $Usuario->buscarUsuario($DadosRelatorio->idTecnicoAvaliador);
            $nmChefiaImediata = $Usuario->buscarUsuario($DadosRelatorio->idChefiaImediata);
            $this->view->TecnicoAvaliador = $nmUsuarioCadastrador;
            $this->view->ChefiaImediata = $nmChefiaImediata;
        }
    }

    public function imprimirAction()
    {
        $idPronac = $this->_request->getParam("pronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        //****** Dados do Projeto - Cabecalho *****//
        $projetos = new Projetos();
        $DadosProjeto = $projetos->buscarProjetoXProponente(array('idPronac = ?' => $idPronac))->current();
        $this->view->DadosProjeto = $DadosProjeto;

        $tbCumprimentoObjeto = new ExecucaoFisica_Model_DbTable_TbCumprimentoObjeto();
        $DadosRelatorio = $tbCumprimentoObjeto->buscarCumprimentoObjeto(array('idPronac = ?' => $idPronac, 'siCumprimentoObjeto!=?'=>1));
        $this->view->DadosRelatorio = $DadosRelatorio;
        if (count($DadosRelatorio)==0) {
            parent::message("Relat&aacute;rio n&atilde;o encontrado!", "execucao-fisica/avaliaracompanhamentoprojeto/index", "ALERT");
        }

        $LocaisDeRealizacao = $projetos->buscarLocaisDeRealizacao($idPronac);
        $this->view->LocaisDeRealizacao = $LocaisDeRealizacao;

        $PlanoDeDivulgacao = $projetos->buscarPlanoDeDivulgacao($idPronac);
        $this->view->PlanoDeDivulgacao = $PlanoDeDivulgacao;

        $PlanoDistribuicaoProduto = new Proposta_Model_DbTable_PlanoDistribuicaoProduto();
        $PlanoDeDistribuicao = $PlanoDistribuicaoProduto->buscarPlanoDeDistribuicao($idPronac);
        $this->view->PlanoDeDistribuicao = $PlanoDeDistribuicao;

        $tbBeneficiarioProdutoCultural = new tbBeneficiarioProdutoCultural();
        $PlanosCadastrados = $tbBeneficiarioProdutoCultural->buscarPlanosCadastrados($idPronac);
        $this->view->PlanosCadastrados = $PlanosCadastrados;

        $DadosCompMetas = $projetos->buscarMetasComprovadas($idPronac);
        $this->view->DadosCompMetas = $DadosCompMetas;

        $DadosItensOrcam = $projetos->buscarItensComprovados($idPronac);
        $this->view->DadosItensOrcam = $DadosItensOrcam;

        $Arquivo = new Arquivo();
        $dadosComprovantes = $Arquivo->buscarComprovantesExecucao($idPronac);
        $this->view->DadosComprovantes = $dadosComprovantes;

        $tbTermoAceiteObra = new ExecucaoFisica_Model_DbTable_TbTermoAceiteObra();
        $AceiteObras = $tbTermoAceiteObra->buscarTermoAceiteObraArquivos(array('idPronac=?'=>$idPronac));
        $this->view->AceiteObras = $AceiteObras;

        $tbBensDoados = new ExecucaoFisica_Model_DbTable_TbBensDoados();
        $BensCadastrados = $tbBensDoados->buscarBensCadastrados(array('a.idPronac=?'=>$idPronac), array('b.Descricao'));
        $this->view->BensCadastrados = $BensCadastrados;

        if ($DadosRelatorio->siCumprimentoObjeto >= 5) {
            $Usuario = new UsuarioDAO();
            $nmUsuarioCadastrador = $Usuario->buscarUsuario($DadosRelatorio->idTecnicoAvaliador);
            $nmChefiaImediata = $Usuario->buscarUsuario($DadosRelatorio->idChefiaImediata);
            $this->view->TecnicoAvaliador = $nmUsuarioCadastrador;
            $this->view->ChefiaImediata = $nmChefiaImediata;
        }

        $this->_helper->layout->disableLayout();// Desabilita o Zend Layout
    }

    public function encaminharRelatorioAction()
    {
        $post = Zend_Registry::get('post');
        $idPronac = (int) $post->pronac;

        $dados = array();
        $dados['idTecnicoAvaliador'] = (int) $post->tecnico;
        $dados['siCumprimentoObjeto'] = 3;
        $where = "idPronac = $idPronac";

        $tbCumprimentoObjeto = new ExecucaoFisica_Model_DbTable_TbCumprimentoObjeto();
        $return = $tbCumprimentoObjeto->update($dados, $where);

        if ($return) {
            parent::message("Relat&aacute;rio encaminhado com sucesso!", "execucao-fisica/avaliaracompanhamentoprojeto/index", "CONFIRM");
        } else {
            parent::message("Relat&aacute;rio n&atilde;o foi encaminhado. Contate o Administrador do sistema!", "execucao-fisica/avaliaracompanhamentoprojeto/index", "ERROR");
        }
    }

    public function indexTecnicoAction()
    {
        //** Usuario Logado ************************************************/
        $auth               = Zend_Auth::getInstance(); // pega a autenticacao
        $idusuario          = $auth->getIdentity()->usu_codigo;
        $GrupoAtivo         = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessao com o grupo ativo
        $codOrgao           = $GrupoAtivo->codOrgao; //  orgao ativo na sessao
        $codPerfil          = $GrupoAtivo->codGrupo; //  orgao ativo na sessao
        $this->view->codOrgao = $codOrgao;
        $this->view->idUsuarioLogado = $idusuario;
        /******************************************************************/
        if ($codPerfil!=139 && $codPerfil!=148 && $codPerfil!=151) {
            parent::message("Voc&ecirc; n&atilde;o tem permissao para acessar essa funcionalidade!", "principal", "ALERT");
        }

        //DEFINE PARAMETROS DE ORDENACAO / QTDE. REG POR PAG. / PAGINACAO
        if ($this->_request->getParam("qtde")) {
            $this->intTamPag = $this->_request->getParam("qtde");
        }
        $order = array();

        //==== parametro de ordenacao  ======//
        if ($this->_request->getParam("ordem")) {
            $ordem = $this->_request->getParam("ordem");
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
        if ($this->_request->getParam("campo")) {
            $campo = $this->_request->getParam("campo");
            $order = array($campo." ".$ordem);
            $ordenacao = "&campo=".$campo."&ordem=".$ordem;
        } else {
            $campo = null;
            $order = array('NomeProjeto');
            $ordenacao = null;
        }

        $pag = 1;
        $get = Zend_Registry::get('get');
        if (isset($get->pag)) {
            $pag = $get->pag;
        }
        $inicio = ($pag>1) ? ($pag-1)*$this->intTamPag : 0;

        /* ================== PAGINACAO ======================*/
        $where = array();
        $where['b.Orgao = ?'] = $codOrgao;
        $where['a.siCumprimentoObjeto in (?)'] = array(3,4);
        $where['a.idTecnicoAvaliador = ?'] = $idusuario;

        if ((isset($_POST['pronac']) && !empty($_POST['pronac'])) || (isset($_GET['pronac']) && !empty($_GET['pronac']))) {
            $where['AnoProjeto+Sequencial = ?'] = isset($_POST['pronac']) ? $_POST['pronac'] : $_GET['pronac'];
            $this->view->pronacProjeto = isset($_POST['pronac']) ? $_POST['pronac'] : $_GET['pronac'];
        }

        $tbCumprimentoObjeto = new ExecucaoFisica_Model_DbTable_TbCumprimentoObjeto();
        $total = $tbCumprimentoObjeto->listaRelatorios($where, $order, null, null, true);
        $fim = $inicio + $this->intTamPag;

        $totalPag = (int)(($total % $this->intTamPag == 0)?($total/$this->intTamPag):(($total/$this->intTamPag)+1));
        $tamanho = ($fim > $total) ? $total - $inicio : $this->intTamPag;

        $busca = $tbCumprimentoObjeto->listaRelatorios($where, $order, $tamanho, $inicio);

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
        $this->view->qtdRelatorios = $total;
        $this->view->dados         = $busca;
        $this->view->intTamPag     = $this->intTamPag;
    }

    public function devolverRelatorioAction()
    {
        $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout

        //** Usuario Logado ************************************************/
        $auth               = Zend_Auth::getInstance(); // pega a autenticacao
        /******************************************************************/

        $post = Zend_Registry::get('post');
        $idPronac = (int) $post->pronac;

        $dados = array();
        $dados['idTecnicoAvaliador'] = null;
        $dados['siCumprimentoObjeto'] = 2;
        $where = "idPronac = $idPronac";

        $tbCumprimentoObjeto = new ExecucaoFisica_Model_DbTable_TbCumprimentoObjeto();
        $return = $tbCumprimentoObjeto->update($dados, $where);

        if ($return) {
            $this->_helper->json(array('resposta'=>true));
        } else {
            $this->_helper->json(array('resposta'=>false));
        }
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function parecerTecnicoAction()
    {

        //** Usuario Logado ************************************************/
        $auth               = Zend_Auth::getInstance(); // pega a autenticacao
        $idusuario          = $auth->getIdentity()->usu_codigo;
        $nmusuario          = $auth->getIdentity()->usu_nome;
        $GrupoAtivo         = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessao com o grupo ativo
        $codOrgao           = $GrupoAtivo->codOrgao; //  orgao ativo na sessao
        $codPerfil          = $GrupoAtivo->codGrupo; //  orgao ativo na sessao
        /******************************************************************/

        if ($codPerfil!=139) {
            parent::message("Voc&ecirc; n&atilde;o tem permissao para acessar essa funcionalidade!", "principal", "ALERT");
        }

        $idPronac = $this->_request->getParam("idPronac");
        $idrelatorio = $this->_request->getParam("relatorio");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        $where = array();
        $where['a.idPronac = ?'] = $idPronac;
        $where['a.idTecnicoAvaliador = ?'] = $idusuario;
        $where['a.siCumprimentoObjeto in (?)'] = array(3,4);
        $where['b.Orgao = ?'] = $codOrgao;

        $tbCumprimentoObjeto = new ExecucaoFisica_Model_DbTable_TbCumprimentoObjeto();
        $DadosRelatorio = $tbCumprimentoObjeto->listaRelatorios($where, array(), null, null, false);

        if (count($DadosRelatorio)==0) {
            parent::message('Relat&oacute;rio n&atilde;o encontrado!', "execucao-fisica/avaliaracompanhamentoprojeto/index-tecnico", "ALERT");
        }

        $this->view->DadosRelatorio = $DadosRelatorio;
        $this->view->idPronac = $idPronac;
        $this->view->idRelatorio = $idrelatorio;
        $this->view->idusuario = $idusuario;
        $this->view->nmusuario = $nmusuario;

        //****** Dados do Projeto - Cabecalho *****//
        $projetos = new Projetos();
        $DadosProjeto = $projetos->buscarProjetoXProponente(array('idPronac = ?' => $idPronac))->current();
        $this->view->DadosProjeto = $DadosProjeto;

        $dadosParecer = $tbCumprimentoObjeto->buscarCumprimentoObjeto(array('idPronac=?'=>$idPronac,'idTecnicoAvaliador=?'=>$idusuario));
        $this->view->DadosParecer = $dadosParecer;

        $pa = new paCoordenadorDoPerfil();
        $usuarios = $pa->buscarUsuarios($codPerfil, $codOrgao);
        $this->view->Usuarios = $usuarios;
    }

    public function etapasDeTrabalhoFinalAction()
    {
        //** Verifica se o usuario logado tem permissao de acesso **//
        $this->verificarPermissaoAcesso(false, true, false);

        $idPronac = $this->_request->getParam("idPronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        //****** Dados do Projeto - Cabecalho *****//
        $projetos = new Projetos();
        $DadosProjeto = $projetos->buscarProjetoXProponente(array('idPronac = ?' => $idPronac))->current();
        $this->view->DadosProjeto = $DadosProjeto;

        $tbCumprimentoObjeto = new ExecucaoFisica_Model_DbTable_TbCumprimentoObjeto();
        $DadosRelatorio = $tbCumprimentoObjeto->buscarCumprimentoObjeto(array('idPronac=?'=>$idPronac));
        $this->view->DadosRelatorio = $DadosRelatorio;
        $this->view->cumprimentoDoObjeto = $tbCumprimentoObjeto;
        $this->view->idPronac = $idPronac;
    }

    public function localDeRealizacaoFinalAction()
    {

        //** Verifica se o usuario logado tem permissao de acesso **//
        $this->verificarPermissaoAcesso(false, true, false);

        $idPronac = $this->_request->getParam("idPronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        //****** Dados do Projeto - Cabecalho *****//
        $projetos = new Projetos();
        $DadosProjeto = $projetos->buscarProjetoXProponente(array('idPronac = ?' => $idPronac))->current();
        $this->view->DadosProjeto = $DadosProjeto;

        $LocaisDeRealizacao = $projetos->buscarLocaisDeRealizacao($idPronac);
        $this->view->LocaisDeRealizacao = $LocaisDeRealizacao;
        $this->view->idPronac = $idPronac;
    }

    public function planoDeDivulgacaoFinalAction()
    {

        //** Verifica se o usuario logado tem permissao de acesso **//
        $this->verificarPermissaoAcesso(false, true, false);

        $idPronac = $this->_request->getParam("idPronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        //****** Dados do Projeto - Cabecalho *****//
        $projetos = new Projetos();
        $dadosProjeto = $projetos->buscarProjetoXProponente(array('idPronac = ?' => $idPronac))->current();
        $this->view->DadosProjeto = $dadosProjeto;

        $PlanoDeDivulgacao = $projetos->buscarPlanoDeDivulgacao($idPronac);
        $this->view->PlanoDeDivulgacao = $PlanoDeDivulgacao;

        $Verificacao = new Verificacao();
        $Peca = $Verificacao->buscar(array('idTipo =?'=>1,'stEstado =?'=>1));
        $this->view->Peca = $Peca;

        $Veiculo = $Verificacao->buscar(array('idTipo =?'=>2,'stEstado =?'=>1));
        $this->view->Veiculo = $Veiculo;
        $this->view->idPronac = $idPronac;

        $tbArquivoImagem = new tbArquivoImagem();
        $this->view->marcas = $tbArquivoImagem->marcasAnexadas($dadosProjeto->pronac);
    }

    public function planoDeDistribuicaoFinalAction()
    {

        //** Verifica se o usuario logado tem permissao de acesso **//
        $this->verificarPermissaoAcesso(false, true, false);

        $idPronac = $this->_request->getParam("idPronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        //****** Dados do Projeto - Cabecalho *****//
        $projetos = new Projetos();
        $DadosProjeto = $projetos->buscarProjetoXProponente(array('idPronac = ?' => $idPronac))->current();
        $this->view->DadosProjeto = $DadosProjeto;

        $PlanoDistribuicaoProduto = new Proposta_Model_DbTable_PlanoDistribuicaoProduto();
        $PlanoDeDistribuicao = $PlanoDistribuicaoProduto->buscarPlanoDeDistribuicao($idPronac);
        $this->view->PlanoDeDistribuicao = $PlanoDeDistribuicao;

        $tbBeneficiarioProdutoCultural = new tbBeneficiarioProdutoCultural();
        $PlanosCadastrados = $tbBeneficiarioProdutoCultural->buscarPlanosCadastrados($idPronac);
        $this->view->PlanosCadastrados = $PlanosCadastrados;
        $this->view->idPronac = $idPronac;
    }

    public function metasComprovadasFinalAction()
    {

        //** Verifica se o usuario logado tem permissao de acesso **//
        $this->verificarPermissaoAcesso(false, true, false);

        $idPronac = $this->_request->getParam("idPronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        //****** Dados do Projeto - Cabecalho *****//
        $projetos = new Projetos();
        $DadosProjeto = $projetos->buscarProjetoXProponente(array('idPronac = ?' => $idPronac))->current();
        $this->view->DadosProjeto = $DadosProjeto;

        //****** Dados da Comprova��o de Metas *****//
        $DadosCompMetas = $projetos->buscarMetasComprovadas($idPronac);
        $this->view->DadosCompMetas = $DadosCompMetas;
        $this->view->idPronac = $idPronac;
    }

    public function itensComprovadosFinalAction()
    {

        //** Verifica se o usuario logado tem permissao de acesso **//
        $this->verificarPermissaoAcesso(false, true, false);

        $idPronac = $this->_request->getParam("idPronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        //****** Dados do Projeto - Cabecalho *****//
        $projetos = new Projetos();
        $DadosProjeto = $projetos->buscarProjetoXProponente(array('idPronac = ?' => $idPronac))->current();
        $this->view->DadosProjeto = $DadosProjeto;

        $DadosItensOrcam = $projetos->buscarItensComprovados($idPronac);
        $this->view->DadosItensOrcam = $DadosItensOrcam;
        $this->view->idPronac = $idPronac;
    }

    public function comprovantesDeExecucaoFinalAction()
    {

        //** Verifica se o usuario logado tem permissao de acesso **//
        $this->verificarPermissaoAcesso(false, true, false);

        $idPronac = $this->_request->getParam("idPronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        //****** Dados do Projeto - Cabecalho *****//
        $projetos = new Projetos();
        $DadosProjeto = $projetos->buscarProjetoXProponente(array('idPronac = ?' => $idPronac))->current();
        $this->view->DadosProjeto = $DadosProjeto;

        $Arquivo = new Arquivo();
        $dadosComprovantes = $Arquivo->buscarComprovantesExecucao($idPronac);
        $this->view->DadosComprovantes = $dadosComprovantes;
        $this->view->idPronac = $idPronac;
    }

    public function aceiteDeObraFinalAction()
    {
        //** Verifica se o usuario logado tem permissao de acesso **//
        $this->verificarPermissaoAcesso(false, true, false);

        $idPronac = $this->_request->getParam("idPronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        //****** Dados do Projeto - Cabecalho *****//
        $projetos = new Projetos();
        $DadosProjeto = $projetos->buscarProjetoXProponente(array('idPronac = ?' => $idPronac))->current();
        $this->view->DadosProjeto = $DadosProjeto;

        $tbTermoAceiteObra = new ExecucaoFisica_Model_DbTable_TbTermoAceiteObra();
        $DadosRelatorio = $tbTermoAceiteObra->buscarTermoAceiteObraArquivos(array('idPronac=?'=>$idPronac));
        $this->view->DadosRelatorio = $DadosRelatorio;
        $this->view->idPronac = $idPronac;
    }

    public function bensFinalAction()
    {
        //** Verifica se o usuario logado tem permissao de acesso **//
        $this->verificarPermissaoAcesso(false, true, false);

        $idPronac = $this->_request->getParam("idPronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        //****** Dados do Projeto - Cabecalho *****//
        $projetos = new Projetos();
        $DadosProjeto = $projetos->buscarProjetoXProponente(array('idPronac = ?' => $idPronac))->current();
        $this->view->DadosProjeto = $DadosProjeto;

        $tbPlanilhaAprovacao = new tbPlanilhaAprovacao();
        $DadosItens = $tbPlanilhaAprovacao->buscarItensOrcamentarios(array('a.idPronac=?'=>$idPronac), array('b.Descricao'));
        $this->view->DadosItens = $DadosItens;

        $tbBensDoados = new ExecucaoFisica_Model_DbTable_TbBensDoados();
        $BensCadastrados = $tbBensDoados->buscarBensCadastrados(array('a.idPronac=?'=>$idPronac), array('b.Descricao'));
        $this->view->BensCadastrados = $BensCadastrados;
        $this->view->idPronac = $idPronac;
    }

    public function avaliarRelatorioAction()
    {

        //** Usuario Logado ************************************************/
        $auth               = Zend_Auth::getInstance(); // pega a autenticacao
        $idusuario          = $auth->getIdentity()->usu_codigo;
        $GrupoAtivo         = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessao com o grupo ativo
        $codOrgao           = $GrupoAtivo->codOrgao; //  orgao ativo na sessao
        /******************************************************************/

        $idPronac = $this->_request->getParam("idPronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        $where = array();
        $where['idPronac = ?'] = $idPronac;
        $where['idTecnicoAvaliador = ?'] = $idusuario;
        $where['siCumprimentoObjeto in (?)'] = array(3,4);

        $tbCumprimentoObjeto = new ExecucaoFisica_Model_DbTable_TbCumprimentoObjeto();
        $DadosRelatorio = $tbCumprimentoObjeto->buscarCumprimentoObjeto($where);

        if (empty($DadosRelatorio)) {
            parent::message('Relat&aacute;rio n&atilde;o encontrado!', "execucao-fisica/avaliaracompanhamentoprojeto/index-tecnico", "ALERT");
        }

        $siComprovante = 4;
        $msg = 'Relat&aacute;rio salvo com sucesso!';
        $controller = "execucao-fisica/avaliaracompanhamentoprojeto/parecer-tecnico?idPronac=".$idPronac;
        if (isset($_POST['finalizar']) && !empty($_POST['finalizar'])) {
            $siComprovante = 5;
            $msg = 'Relat&aacute;rio finalizado com sucesso!';
            $controller = 'execucao-fisica/avaliaracompanhamentoprojeto/index-tecnico';
        }

        $dados = array(
            'dsInformacaoAdicional' => $_POST['informacaoAdicional'],
            'dsOrientacao' => $_POST['orientacao'],
            'dsConclusao' => $_POST['conclusao'],
            'stResultadoAvaliacao' => $_POST['resultadoAvaliacao'],
            'idChefiaImediata' => $_POST['chefiaImediata'],
            'siCumprimentoObjeto' => $siComprovante
        );

        $whereFinal = 'idCumprimentoObjeto = '.$DadosRelatorio->idCumprimentoObjeto;
        $resultado = $tbCumprimentoObjeto->alterar($dados, $whereFinal);

        if ($resultado) {
            parent::message($msg, $controller, "CONFIRM");
        } else {
            parent::message('N&atilde;o foi poss&iacute;vel salvar o relat&oacute;rio!', "analisarexecucaofisicatecnico", "ERROR");
        }
    }

    public function finalizarRelatorioAction()
    {
        $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout

        $post = Zend_Registry::get('post');
        $idPronac = (int) $post->pronac;

        $dados = array();
        $dados['siCumprimentoObjeto'] = 6;
        $where = "idPronac = $idPronac";

        $tbCumprimentoObjeto = new ExecucaoFisica_Model_DbTable_TbCumprimentoObjeto();
        $projetoModel = new Projetos();
        $return = $tbCumprimentoObjeto->update($dados, $where);
        $projetoModel->mudarSituacao($idPronac, 'E68', 'E24');

        if ($return) {
            $this->_helper->json(array('resposta'=>true));
        } else {
            $this->_helper->json(array('resposta'=>false));
        }
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function recursosPorFonteAction()
    {
        $planilhaAprovacaoModel = new PlanilhaAprovacao();
        $this->view->recursosPorFonte = $planilhaAprovacaoModel
                ->buscarRecursosDaFonte($this->getRequest()->getParam('idPronac'));
        //Passando o pronac para ser usada no menu lateral esquerdo
        $this->view->idPronac = $this->getRequest()->getParam('idPronac');
    }
}
