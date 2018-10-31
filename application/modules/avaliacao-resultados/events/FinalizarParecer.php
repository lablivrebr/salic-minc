<?php

use Application\Modules\AvaliacaoResultados\Service\Assinatura\Parecer\DocumentoAssinatura as DocumentoAssinaturaService;
use Application\Modules\AvaliacaoResultados\Service\ParecerTecnico\AvaliacaoFinanceira as AvaliacaoFinanceiraService;

class AvaliacaoResultados_Events_FinalizarParecer
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
        $this->events->attach('run', $this->iniciarAssinatura());
        $this->events->attach('run', $this->alterarEstado());
        $this->events->attach('run', $this->salvarParecer());
    }

    public function run($params) {
        $this->events->trigger(__FUNCTION__, $this, $params);
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

    public function iniciarAssinatura() {
        return function($t) {
            $params = $t->getParams();

            $assinatura = new DocumentoAssinaturaService($params['idPronac'], 622);
            $idDocumentoAssinatura = $assinatura->iniciarFluxo();
        };
    }

    public function salvarParecer() {
        return function($t) {
            $params = $t->getParams();
            $avaliacaoFinanceiraService = new AvaliacaoFinanceiraService();
            $response = $avaliacaoFinanceiraService->salvarParecer($params);
        };
    }
}
