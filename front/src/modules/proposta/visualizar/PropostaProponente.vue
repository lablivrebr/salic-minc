<template>
    <div>
        <PropostaFluxo :id-pre-projeto="idPreProjeto"></PropostaFluxo>
        <Proposta :idpreprojeto="idPreProjeto"></Proposta>

        <SalicMenuSuspenso v-if="Object.keys(dadosProposta).length > 0">
            <li v-if="dadosProposta.idMovimentacao !== PROPOSTA_ARQUIVADA">
                <a class="btn-floating red tooltipped"
                   :href="`/solicitacao/mensagem/index/idPreProjeto/${idPreProjeto}`"
                   data-tooltip="Solicitações"
                ><i class="material-icons">message</i></a>
            </li>
            <li v-if="dadosProposta.idMovimentacao === PROPOSTA_COM_PROPONENTE">
                <a class="btn-floating green tooltipped"
                   :href="`/proposta/manterpropostaincentivofiscal/identificacaodaproposta/idPreProjeto/${idPreProjeto}`"
                   data-tooltip="Editar proposta"
                ><i class="material-icons">edit</i></a>
            </li>
        </SalicMenuSuspenso>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import SalicMenuSuspenso from "@/components/SalicMenuSuspenso";
import PropostaFluxo from './components/PropostaFluxo';
import Proposta from './Proposta';

export default {
    name: 'PropostaProponente',
    props: ['idPreProjeto'],
    data() {
        return {
            PROPOSTA_COM_PROPONENTE: 95,
            PROPOSTA_ARQUIVADA: 128,
            dados: {
                type: Object,
                default() {
                    return {};
                },
            },
            loading: true,
        };
    },
    components: {
        SalicMenuSuspenso,
        PropostaFluxo,
        Proposta,
    },
    computed: {
        ...mapGetters({
            dadosProposta: 'proposta/proposta',
        }),
    },
};
</script>
