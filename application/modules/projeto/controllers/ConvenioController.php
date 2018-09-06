<?php

class Projeto_ConvenioController extends Projeto_GenericController
{
    private $idPronac;

    public function init()
    {
        parent::init();
        $this->validarPerfis();

        $this->idPronac = $this->_request->getParam("idPronac");
        if (strlen($this->idPronac) > 7) {
            $this->idPronac = Seguranca::dencrypt($this->idPronac);
        }

        if (empty($this->idPronac)) {
            throw new Exception("idPronac n&atilde;o informado");
        }

        $this->view->idPronac = $this->idPronac;
        $this->view->idPronacHash = Seguranca::encrypt($this->idPronac);

        $this->view->urlMenu = [
            'module' => 'projeto',
            'controller' => 'menu',
            'action' => 'obter-menu-ajax',
            'idPronac' => $this->view->idPronacHash
        ];
    }

    private function validarPerfis()
    {
        $auth = Zend_Auth::getInstance();

        $PermissoesGrupo = array();
        $PermissoesGrupo[] = 147;
        $PermissoesGrupo[] = 148;
        $PermissoesGrupo[] = 149;
        $PermissoesGrupo[] = 150;
        $PermissoesGrupo[] = 151;
        $PermissoesGrupo[] = 152;

//         isset($auth->getIdentity()->usu_codigo) ? parent::perfil(1, $PermissoesGrupo) : parent::perfil(4, $PermissoesGrupo);
    }

    public function indexAction()
    {
        $this->redirect("/projeto/index/listar");
    }

    public function obterProjetoAjaxAction()
    {
        if (empty($this->idPronac)) {
            throw new Exception('Pronac &eacute; obrigat&oacute;rio!');
        }

        $permissao = $this->verificarPermissaoAcesso(false, true, false, true);
        $vwDadosProjeto = new Projeto_Model_DbTable_VwConsultarDadosDoProjetoFNC();
        $projeto = $vwDadosProjeto->obterDadosFnc($this->idPronac);
        $data = array_map('utf8_encode', $projeto);

        if (!$permissao['status']) {
            $data['permissao'] = false;
            $httpCode = 203;
            throw new Exception('Voc&ecirc; n&atilde;o tem permiss&atilde;o para acessar este projeto');
        }
        $data['permissao'] = true;
        $data['isTipoConvenio'] = true;

        $this->view->dados = $data;
        $dbTableInabilitado = new Inabilitado();
        $proponenteInabilitado = $dbTableInabilitado->BuscarInabilitado($projeto['CNPJ_CPF'], null, null, true);
        $data['ProponenteInabilitado'] = !empty($proponenteInabilitado);
        // $this->view->ProponenteInabilitado = !empty($proponenteInabilitado);
        $this->_helper->json(
            [
                'data' => $data,
                'success' => 'true'
            ]
        );
    }

}
