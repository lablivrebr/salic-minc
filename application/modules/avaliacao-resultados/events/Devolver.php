<?php

use Application\Modules\AvaliacaoResultados\Service\Encaminhar\AvaliacaoFinanceira as EncaminharAvaliacaoService;

class AvaliacaoResultados_Events_Devolver
{
    protected $events;

    public function __construct(Zend_EventManager_EventCollection $events = null)
    {
        if (null !== $events) {
            $this->events = $events;
        } elseif (null === $this->events) {
            $this->events = new Zend_EventManager_EventManager(__CLASS__);
        }

        $this->attach();
    }

    public function attach() {
        $this->events->attach('run', $this->alterarEstado());
        $this->events->attach('run', $this->invalidarDocumento());
    }

    public function run($params) {
        $this->events->trigger(__FUNCTION__, $this, $params);
    }

    public function salvarEncaminhamento() {
        return function($t) {
            $params = $t->getParams();
            $EncaminharAvaliacaoService = new EncaminharAvaliacaoService();
            $EncaminharAvaliacaoService->salvar($params);
        };
    }

    public function alterarSituacaoProjeto() {
        return function($t) {
            $params = $t->getParams();
            $projeto = new Projetos();
            $projeto->alterarSituacao($params['idPronac'], '', 'E27', 'Comprova&ccedil;&atilde;o Financeira do Projeto em Análise');
        };
    }

    public function alterarEstado() {
        return function($t) {
            $params = $t->getParams();

            $model = new AvaliacaoResultados_Model_FluxosProjeto();
            $mapper = new AvaliacaoResultados_Model_FluxosProjetoMapper();

            $row = $mapper->find(['idPronac = ?' => $params['idPronac']]);

            if (!empty($row)) {
                $model->setId($row['id']);
            }

            $model->setIdPronac($params['idPronac']);
            $model->setEstadoId($params['proximo']);

            $mapper->save($model);
        };
    }

    public function invalidarDocumento() {
        return function($t) {
            $params = $t->getParams();

            $objDbTableDocumentoAssinatura = new \Assinatura_Model_DbTable_TbDocumentoAssinatura();
            $idDocumento = $objDbTableDocumentoAssinatura->getIdDocumentoAssinatura($params['idPronac'], $params['idTipoDoAtoAdministrativo']);
            $GrupoAtivo = new \Zend_Session_Namespace('GrupoAtivo');
            $codGrupo = $GrupoAtivo->codGrupo;
            $dados = [
                'Despacho' => 'devolvido para o técnico',
                'idTipoDoAto' => $params['idTipoDoAtoAdministrativo'],
                'idPronac' => $params['idPronac'],
                'idPerfilDoAssinante' => $codGrupo,
                'idDocumentoAssinatura' => $idDocumento
            ];

            $assinaturaService = new \MinC\Assinatura\Servico\Assinatura($dados);

            $assinaturaService->devolver();
        };
    }
}