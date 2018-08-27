<?php
/**
 * Controller Solicitar Recurso de Decis�o
 * @author Equipe RUP - Politec
 * @since 21/07/2010
 * @version 1.0
 * @package application
 * @subpackage application.controller
 * @link http://www.cultura.gov.br
 * @copyright � 2010 - Minist�rio da Cultura - Todos os direitos reservados.
 */

class SolicitarRecursoDecisaoController extends MinC_Controller_Action_Abstract
{
    /**
     * Variavel com o id do usuario logado
     */
    private $getIdUsuario = 0;

    /**
     * Reescreve o metodo init()
     * @access public
     * @param void
     * @return void
     */
    public function init()
    {

        // verifica as permissoes
        $PermissoesGrupo = array();
        $auth = Zend_Auth::getInstance(); // instancia da autenticacao
        $GrupoAtivo   = new Zend_Session_Namespace('GrupoAtivo');

        $idPronac = $this->_request->getParam("idPronac"); // pega o id do pronac via get
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        if (isset($auth->getIdentity()->usu_codigo)) {
            parent::perfil(1, $PermissoesGrupo);
        } else {
            parent::perfil(4, $PermissoesGrupo);
            $this->getIdUsuario = (isset($_GET["idusuario"])) ? $_GET["idusuario"] : 0;

            /* =============================================================================== */
            /* ==== VERIFICA PERMISSAO DE ACESSO DO PROPONENTE A PROPOSTA OU AO PROJETO ====== */
            /* =============================================================================== */
            if (!isset($idPronac) || empty($idPronac)) {
                parent::message('É necessário o número do PRONAC para acessar essa página!', "principalproponente", "ERROR");
            }
//            $this->verificarPermissaoAcesso(false, true, false);
        }

        if (!$auth->hasIdentity()) {
            $url = Zend_Controller_Front::getInstance()->getBaseUrl();
            JS::redirecionarURL($url);
        }

        parent::init();
    }



    /**
     * Redireciona para o fluxo inicial do sistema
     * @access public
     * @param void
     * @return void
     */
    public function indexAction()
    {
        // despacha para recurso.phtml
        $this->forward("recurso");
    }

    /**
     * Metodo com os recursos (Projetos Aprovados e Nao Aprovados)
     * @param void
     * @return void
     */
    public function recursoAction()
    {
        if ($this->getRequest()->isPost()) {
            $post          	= Zend_Registry::get('post');
            $idPronac      	= $post->idPronac;
            $tpSolicitacao 	= $post->tpSolicitacao;
            $StatusProjeto	= $post->StatusProjeto;
            $auth           = Zend_Auth::getInstance();

            try {
                if (isset($_POST['checkEnquadramento']) && !empty($_POST['checkEnquadramento']) && isset($_POST['checkOrcamento']) && !empty($_POST['checkOrcamento'])) {
                    $tpSolicitacao = 'EO';
                } elseif (isset($_POST['checkEnquadramento']) && !empty($_POST['checkEnquadramento']) && !isset($_POST['checkOrcamento'])) {
                    $tpSolicitacao = 'EN';
                } elseif (isset($_POST['checkOrcamento']) && !empty($_POST['checkOrcamento']) && !isset($_POST['checkEnquadramento'])) {
                    $tpSolicitacao = 'OR';
                } else {
                    $tpSolicitacao = 'PI';
                }

                $dados = array(
                    'IdPRONAC'              => $_POST['idPronac'],
                    'dtSolicitacaoRecurso'  => new Zend_Db_Expr('GETDATE()'),
                    'dsSolicitacaoRecurso'  => $_POST['dsRecurso'],
                    'idAgenteSolicitante'   => $auth->getIdentity()->IdUsuario,
                    'stAtendimento'         => 'N',
                    'tpSolicitacao'         => $tpSolicitacao
                );

                $tbRecurso = new tbRecurso();
                $resultadoPesquisa = $tbRecurso->buscar(array('IdPRONAC = ?'=>$_POST['idPronac']));

                $dados['tpRecurso'] = 1;
                if (count($resultadoPesquisa)>0) {
                    $dados['tpRecurso'] = 2;
                }

                // tenta cadastrar o recurso
//                $cadastrar = RecursoDAO::cadastrar($dados);
                $cadastrar = $tbRecurso->inserir($dados);

                if ($cadastrar) {
                    $alterarSituacao = ProjetoDAO::alterarSituacao($idPronac, 'D20');
                    parent::message('Solicita&ccedil;&atilde;o enviada com sucesso!', "consultardadosprojeto/index?idPronac=".Seguranca::encrypt($idPronac), "CONFIRM");
                } // fecha if
                else {
                    throw new Exception("Erro ao cadastrar recurso!");
                }
            } catch (Exception $e) {
                parent::message($e->getMessage(), "solicitarrecursodecisao/recurso?idPronac=".$idPronac, "ERROR");
            }
        } else {
            $idPronac = $this->_request->getParam("idPronac"); // pega o id do pronac via get
            if (strlen($idPronac) > 7) {
                $idPronac = Seguranca::dencrypt($idPronac);
            }
            $this->view->idPronac = $idPronac;

            // recebe os dados via get
            $cpf_cnpj = $this->_request->getParam('cpf_cnpj', '');

            if (empty($idPronac)) {
                parent::message('&Eacute; necess&aacute;rio o n&uacute;mero do PRONAC para acessar essa p&aacute;gina!', "consultardadosprojeto?idPronac=" . $idPronac, "ERROR");
            }

//            // busca os projetos
//            $buscarProjetos = SolicitarRecursoDecisaoDAO::buscarProjetos($idPronac, $cpf_cnpj);
//            $dbTableProjetos = new Projeto_Model_DbTable_Projetos();
//            $where = [];
//            $where = ['projeto.Situacao in (?)' => Recurso_Model_TbRecurso::SITUACOES_PASSIVEIS_DE_RECURSO];
//
//            if($cpf_cnpj) {
//                $where = ['projeto.CgcCpf = ?' => $cpf_cnpj;
//            }
//            $projetoComRecurso = $dbTableProjetos->obterProjetoComSituacao($idPronac, $cpf_cnpj);

            $tbRecursoMapper = new Recurso_Model_TbRecursoMapper();
            $this->view->projeto = $tbRecursoMapper->obterProjetoPassivelDeRecurso($idPronac, $cpf_cnpj);
        }
    }

    /**
     * Meodo para chamar a tela de descricao do termo de deisitencia do recurso
     */
    public function recursoDesistirAction()
    {
        $idPronac = $this->_request->getParam("idPronac"); // pega o id do pronac via get
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        $Projetos = new Projetos();
        $dadosProj = $Projetos->buscar(array('IdPRONAC = ?' => $idPronac))->current();
        $this->view->projetos = $dadosProj;
    }

    public function recursoDesistirEnquadramentoAction()
    {
        $idPronac = $this->_request->getParam("idPronac");

        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        $Projetos = new Projetos();
        $dadosProj = $Projetos->buscar(array('IdPRONAC = ?' => $idPronac))->current();
        $this->view->projetos = $dadosProj;
    }


    /**
     * Metodo para aplicar no banco de dados a desistencia do recurso
     */
    public function recursoDesistenciaAction()
    {
        $post = Zend_Registry::get('post');
        $idPronac = $this->_request->getParam("idPronac");
        $auth = Zend_Auth::getInstance();

        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        if ($post->deacordo) {
            $dados = array(
                'IdPRONAC'              => $post->idPronac,
                'dtSolicitacaoRecurso'  => new Zend_Db_Expr('GETDATE()'),
                'dsSolicitacaoRecurso'  => 'Desist�ncia do prazo recursal',
                'idAgenteSolicitante'   => $auth->getIdentity()->IdUsuario,
                'stAtendimento'         => 'N',
                'siFaseProjeto'         => 2,
                'siRecurso'             => 0,
                'tpSolicitacao'         => 'DR',
                'tpRecurso'             => 1,
                'stAnalise'             => null,
                'stEstado'              => 1
            );

            $tbRecurso = new tbRecurso();
            $resultadoPesquisa = $tbRecurso->buscar(array('IdPRONAC = ?'=>$_POST['idPronac']));

            if (count($resultadoPesquisa)>0) {
                $dados['tpRecurso'] = 2;
            }

            RecursoDAO::cadastrar($dados);
            parent::message('A desist&ecirc;ncia do prazo recursal foi cadastrada com sucesso!', "consultardadosprojeto?idPronac=". Seguranca::encrypt($idPronac), "CONFIRM");
        } else {
            parent::message('&Eacute; necess&aacute;rio estar de acordo com os termos para registrar a sua desist&ecirc;ncia do prazo recursal!', "solicitarrecursodecisao/recurso-desistir?idPronac=". Seguranca::encrypt($idPronac), "ERROR");
        }
    }

    public function recursoDesistenciaEnquadramentoAction()
    {
        $post = Zend_Registry::get('post');
        $idPronac = $this->_request->getParam("idPronac");
        $auth = Zend_Auth::getInstance();

        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        if ($post->deacordo) {
            $dados = array(
                'IdPRONAC'              => $post->idPronac,
                'dtSolicitacaoRecurso'  => new Zend_Db_Expr('GETDATE()'),
                'dsSolicitacaoRecurso'  => 'Desist�ncia do prazo recursal',
                'idAgenteSolicitante'   => $auth->getIdentity()->IdUsuario,
                'stAtendimento'         => 'N',
                'siFaseProjeto'         => 2,
                'siRecurso'             => 0,
                'tpSolicitacao'         => 'DR',
                'tpRecurso'             => 1,
                'stAnalise'             => null,
                'stEstado'              => 1
            );

            $tbRecurso = new tbRecurso();
            $resultadoPesquisa = $tbRecurso->buscar(array('IdPRONAC = ?'=>$_POST['idPronac']));

            if (count($resultadoPesquisa)>0) {
                $dados['tpRecurso'] = 2;
            }

            RecursoDAO::cadastrar($dados);
            parent::message('A desistência do prazo recursal foi cadastrada com sucesso!', "consultardadosprojeto?idPronac=". Seguranca::encrypt($idPronac), "CONFIRM");
        } else {
            parent::message('� necess�rio estar de acordo com os termos para registrar a sua desist�ncia do prazo recursal!', "solicitarrecursodecisao/recurso-desistir-enquadramento?idPronac=". Seguranca::encrypt($idPronac), "ERROR");
        }
    }

    /**
     * Metodo para buscar os projetos aprovados e nao aprovados
     * @access public
     * @param void
     * @return void
     */
    public function proponenteprojetoAction()
    {
        // recebe os dados do formulario via get
        $get      = Zend_Registry::get('get');
        $idpronac = $get->idpronac;
        $cpf      = $get->cpf;

        // aprovados
        $buscaprojetoaprovado = SolicitarRecursoDecisaoDAO::buscaprojetosaprovados($idpronac, $cpf);
        $this->view->projetoaprovado = $buscaprojetoaprovado;

        // nao aprovados
        $buscaprojetonaoaprovado = SolicitarRecursoDecisaoDAO::buscaprojetosnaoaprovados($idpronac, $cpf);
        $this->view->projetonaoaprovado = $buscaprojetonaoaprovado;
    } // fecha m�todo proponenteprojetoAction()

    public function recursoEnquadramentoAction()
    {
        $recurso = new tbRecurso();
        $idPronac = $this->_request->getParam("idPronac");
        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }
        $this->view->idPronac = $idPronac;

        // recebe os dados via get
        $cpf_cnpj = isset($_GET['cpf_cnpj']) ? $_GET['cpf_cnpj'] : '';

        if (!isset($idPronac) || empty($idPronac)) {
            parent::message('� necess�rio o n�mero do PRONAC para acessar essa p�gina!', "consultardadosprojeto?idPronac=".$idPronac, "ERROR");
        }
        // busca os projetos
        $tbRecursoMapper = new Recurso_Model_TbRecursoMapper();
        $this->view->projeto = $tbRecursoMapper->obterProjetoPassivelDeRecurso($idPronac, $cpf_cnpj);
        $this->view->recurso = $recurso->existeRecursoIndeferido($idPronac);
    }

    /**
     * recursoEnquadramentoSalvarAction
     *
     * @access public
     * @return void
     * @todo metodo em construcao
     */
    public function recursoEnquadramentoSalvarAction()
    {
        if ($this->getRequest()->isPost()) {
            $post = Zend_Registry::get('post');
            $idPronac = $post->idPronac;
            $tpSolicitacao = $post->tpSolicitacao;
            $StatusProjeto = $post->StatusProjeto;
            $auth = Zend_Auth::getInstance();

            try {

                # busca o usuario que fez o enquadramento para encaminhar o recurso para o mesmo
                $ModelEnquadramento = new Admissibilidade_Model_Enquadramento();
                $dadosEnquadramento = $ModelEnquadramento->buscarDados($idPronac, null, false);

                $dados = array(
                    'IdPRONAC'              => $_POST['idPronac'],
                    'dtSolicitacaoRecurso'  => new Zend_Db_Expr('GETDATE()'),
                    'dsSolicitacaoRecurso'  => $_POST['dsRecurso'],
                    'idAgenteSolicitante'   => $auth->getIdentity()->IdUsuario,
                    'stAtendimento'         => 'N',
                    'tpSolicitacao'         => 'EN',
                    'siFaseProjeto'         =>  1,
                    'siRecurso'             =>  1,
                    'stEstado'              =>  0,
                    'idAgenteAvaliador' => isset($dadosEnquadramento->Logon) ? $dadosEnquadramento->Logon : '',
                );

                $tbRecurso = new tbRecurso();
                $resultadoPesquisa = $tbRecurso->existeRecursoIndeferido($_POST['idPronac']);

                $dados['tpRecurso'] = 1;
                if (count($resultadoPesquisa)>0) {
                    $dados['tpRecurso'] = 2;
                }

                $cadastrar = $tbRecurso->insert($dados);

                if ($cadastrar) {
                    // altera a situacao do projeto
                    parent::message('Solicita&ccedil;&atilde;o enviada com sucesso!', "consultardadosprojeto/index?idPronac=".Seguranca::encrypt($idPronac), "CONFIRM");
                } else {
                    throw new Exception("Erro ao cadastrar recurso!");
                }
            } catch (Exception $e) {
                parent::message($e->getMessage(), "solicitarrecursodecisao/recurso?idPronac=".$idPronac, "ERROR");
            }
        }
    }

    public function recursoDesistenciaEnquadramentoSalvarAction()
    {
        $post = Zend_Registry::get('post');
        $idPronac = $this->_request->getParam("idPronac");
        $auth = Zend_Auth::getInstance();

        if (strlen($idPronac) > 7) {
            $idPronac = Seguranca::dencrypt($idPronac);
        }

        if ($idPronac) {
            $dados = array(
                'IdPRONAC'              => $idPronac,
                'dtSolicitacaoRecurso'  => new Zend_Db_Expr('GETDATE()'),
                'dsSolicitacaoRecurso'  => 'Desist�ncia do prazo recursal',
                'idAgenteSolicitante'   => $auth->getIdentity()->IdUsuario,
                'stAtendimento'         => 'N',
                'siFaseProjeto'         => 1,
                'siRecurso'             => TbTipoEncaminhamento::DESISTENCIA_DO_PRAZO_RECURSAL,
                'tpSolicitacao'         => 'DR',
                'tpRecurso'             => 1,
                'stAnalise'             => null,
                'stEstado'              => 1
            );

            $tbRecurso = new tbRecurso();
            $resultadoPesquisa = $tbRecurso->buscar(array('IdPRONAC = ?'=> $idPronac));

            RecursoDAO::cadastrar($dados);
            parent::message('A desist&ecirc;ncia do prazo recursal foi cadastrada com sucesso!', "consultardadosprojeto?idPronac=". Seguranca::encrypt($idPronac), "CONFIRM");
        } else {
            parent::message('N&atilde;o foi poss&iacute;vel cadastrar a desist&ecirc;ncia do prazo recursal!', "consultardadosprojeto?idPronac=". Seguranca::encrypt($idPronac), "ERROR");
        }
    }

    public function concordarDesistenciaRecursalModalAction()
    {
        $this->_helper->layout->disableLayout();
        $this->view->idPronac = $this->_request->getParam("idPronac");
    }
}
