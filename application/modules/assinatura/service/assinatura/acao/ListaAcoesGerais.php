<?php

namespace Application\Modules\Assinatura\Service\Assinatura\Acao;

use MinC\Assinatura\Acao\IListaAcoesGerais;
use \Assinatura_Model_DbTable_TbAssinatura as TbAssinaturaDbTable,
    \Application\Modules\Readequacao\Service\Assinatura\Acao\ListaAcoesModulo as ListaAcoesReadequacao,
    \Application\Modules\PrestacaoContas\Service\Assinatura\Laudo\Acao\ListaAcoesModulo as ListaAcoesLaudoPrestacaoContas,
    \Application\Modules\Projeto\Service\Assinatura\Acao\ListaAcoesModulo as ListaAcoesHomologacaoProjeto,
    \Application\Modules\Parecer\Service\Assinatura\AnaliseCNIC\Acao\ListaAcoesModulo as ListaAcoesParecerAnaliseCNIC,
    \Application\Modules\Parecer\Service\Assinatura\AnaliseInicial\Acao\ListaAcoesModulo as ListaAcoesParecerAnaliseInicial,
    \Application\Modules\Admissibilidade\Service\Assinatura\Acao\ListaAcoesModulo as ListaAcoesEnquadramento,
    \Application\Modules\AvaliacaoResultados\Service\Assinatura\Parecer\Acao\ListaAcoesModulo as ListaAcoesParecerAvaliacaoResultados,
    \Application\Modules\AvaliacaoResultados\Service\Assinatura\Laudo\Acao\ListaAcoesModulo as ListaAcoesLaudoAvaliacaoResultados;

class ListaAcoesGerais implements IListaAcoesGerais
{

    public function obterLista(): array
    {
        return [
            TbAssinaturaDbTable::TIPO_ATO_PARECER_TECNICO_READEQUACAO_VINCULADAS => new ListaAcoesReadequacao(),
            TbAssinaturaDbTable::TIPO_ATO_PARECER_TECNICO_AJUSTE_DE_PROJETO => new ListaAcoesReadequacao(),
            TbAssinaturaDbTable::TIPO_ATO_PARECER_TECNICO_READEQUACAO_PROJETOS_MINC => new ListaAcoesReadequacao(),
            TbAssinaturaDbTable::TIPO_ATO_ENQUADRAMENTO => new ListaAcoesEnquadramento(),
            TbAssinaturaDbTable::TIPO_ATO_LAUDO_PRESTACAO_CONTAS => new ListaAcoesLaudoPrestacaoContas(),
            TbAssinaturaDbTable::TIPO_ATO_HOMOLOGAR_PROJETO => new ListaAcoesHomologacaoProjeto(),

            TbAssinaturaDbTable::TIPO_ATO_ANALISE_CNIC => new ListaAcoesParecerAnaliseCNIC(),
            TbAssinaturaDbTable::TIPO_ATO_ANALISE_INICIAL => new ListaAcoesParecerAnaliseInicial(),

            TbAssinaturaDbTable::TIPO_ATO_LAUDO_PRESTACAO_CONTAS=> new ListaAcoesParecerAvaliacaoResultados(),
            TbAssinaturaDbTable::TIPO_ATO_LAUDO_FINAL_PRESTACAO_CONTAS=> new ListaAcoesLaudoAvaliacaoResultados(),
        ];
    }
}
