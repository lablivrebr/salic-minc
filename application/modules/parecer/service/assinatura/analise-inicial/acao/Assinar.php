<?php

namespace Application\Modules\Parecer\Service\Assinatura\AnaliseInicial\Acao;

use MinC\Assinatura\Acao\IAcaoAssinar;

class Assinar implements IAcaoAssinar
{
    public function executar(\MinC\Assinatura\Model\Assinatura $assinatura)
    {
        $modeloTbAssinatura = $assinatura->modeloTbAssinatura;
        $numeroDeAssinaturas = $assinatura->dbTableTbAssinatura->obterQuantidadeAssinaturasRealizadas();
        if ($numeroDeAssinaturas == 1) {
            $dados['DtDevolucao'] = \MinC_Db_Expr::date();
            $dados['FecharAnalise'] = \Parecer_Model_TbDistribuirParecer::FECHAR_ANALISE_FECHADA;

            $where = [
                'idPronac = ?' => $modeloTbAssinatura->getIdPronac(),
                'FecharAnalise = ?' => \Parecer_Model_TbDistribuirParecer::FECHAR_ANALISE_ASSINATURA,
                'stEstado = ?' => \Parecer_Model_TbDistribuirParecer::ST_ESTADO_ATIVO,
                'stPrincipal = ?' => 1,
            ];

            $tbDistribuirParecer = new \Parecer_Model_DbTable_TbDistribuirParecer();
            $tbDistribuirParecer->alterar($dados, $where);
        }
    }
}
