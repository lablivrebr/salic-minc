<?php

namespace Application\Modules\AvaliacaoResultados\Service\LaudoFinal;

class Laudo 
{
    public function obterProjetos($estadoId){
        $model = new \AvaliacaoResultados_Model_DbTable_LaudoFinal();
        
        return $model->projetosLaudoFinal($estadoId)->toArray();
    }

    public function obterLaudo($idPronac){
        $model = new \AvaliacaoResultados_Model_DbTable_LaudoFinal();
        return $model->laudoFinal($idPronac);
    }

    public function salvarLaudo($idLaudoFinal, $idPronac, $siManifestacao, $dsLaudoFinal){
        $auth = \Zend_Auth::getInstance();
        $tbTable = new \AvaliacaoResultados_Model_DbTable_LaudoFinal;
        if(empty($idLaudoFinal)) {
            return $tbTable->insert(['idPronac'=>$idPronac,
                              'idUsuario'=>$auth->getIdentity()->usu_codigo,
                              'dtLaudoFinal'=>(new \DateTime())->format('Y-m-d'),
                              'siManifestacao'=>$siManifestacao,
                              'dsLaudoFinal'=>$dsLaudoFinal]);
        } else {
            return $tbTable->alterar(['idUsuario'=>$auth->getIdentity()->usu_codigo,
                               'siManifestacao'=>$siManifestacao,
                               'dsLaudoFinal'=>$dsLaudoFinal],
                              ['idLaudoFinal = ?' => $idLaudoFinal]);
        }
    }
}