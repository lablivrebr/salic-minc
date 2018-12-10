<?php

namespace Application\Modules\AvaliacaoResultados\Service\Assinatura\Laudo\Acao;

use MinC\Assinatura\Acao\IAcaoFinalizar;

class Finalizar implements IAcaoFinalizar
{
    public function executar(\MinC\Assinatura\Model\Assinatura $assinatura)
    {
        $modeloTbAssinatura = $assinatura->modeloTbAssinatura;

        $laudo = new \AvaliacaoResultados_Model_DbTable_LaudoFinal();
        $where = [
            'idPronac' => $modeloTbAssinatura->getIdPronac()
        ];
        $laudoFinal = $laudo->findBy($where);

        $situacao = \Projeto_Model_Situacao::PC_DESAPROVADA_COM_INDICATIVO_PARA_TCE;
        $providenciaTomada = 'Presta&ccedil;&atilde;o de contas desaprovada com indicativo para tomada de contas especial';
        if ($laudoFinal['siManifestacao'] == \AvaliacaoResultados_Model_LaudoFinal::SI_MANIFESTACAO_PARCIALMENTE) {
            $situacao = \Projeto_Model_Situacao::PC_APROVADA_COM_RESSALVA_FORMAL_E_SEM_PREJUIZO;
            $providenciaTomada = 'Presta&ccedil;&atilde;o de contas aprovada com ressalva formal e sem preju&iacute;zo';
        } else if ($laudoFinal['siManifestacao'] == \AvaliacaoResultados_Model_LaudoFinal::SI_MANIFESTACAO_APROVADO) {
            $situacao = \Projeto_Model_Situacao::PRESTACAO_DE_CONTAS_APROVADA;
            $providenciaTomada = 'Presta&ccedil;&atilde;o de Contas Aprovada';
        }

        $tbProjetos = new \Projetos();
        $tbProjetos->alterarSituacao($modeloTbAssinatura->getIdPronac(), '', $situacao, $providenciaTomada);

        $dadosProjeto = $tbProjetos->findBy(array(
            'IdPRONAC' => $modeloTbAssinatura->getIdPronac()
        ));

        $objOrgaos = new \Orgaos();
        $dadosOrgaoSuperior = $objOrgaos->obterOrgaoSuperior($dadosProjeto['Orgao']);
        if ((int)$dadosOrgaoSuperior['Codigo'] == (int)\Orgaos::ORGAO_SUPERIOR_SEFIC) {
            $orgaoDestino = (int)\Orgaos::ORGAO_GEAR_SACAV;
        } elseif ((int)$dadosOrgaoSuperior['Codigo'] == (int)\Orgaos::ORGAO_SUPERIOR_SAV) {
            $orgaoDestino = (int)\Orgaos::ORGAO_SAV_CEP;
        }
        $objTbProjetos = new \Projeto_Model_DbTable_Projetos();
        $objTbProjetos->alterarOrgao($orgaoDestino, $modeloTbAssinatura->getIdPronac());
    }
}
