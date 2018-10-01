<template>
    <div class="content">
        <div class="row">
            <div class="col s12">
                <div class="row" style="text-align: center;">
                    <div v-for="(fase, key) of fases" :key="fase.id" style="display:inline-block">
                        <i v-if="key > 0" class="tiny material-icons">forward</i>
                        <a
                            class="margin10 btn small btn-primary tooltipped"
                            :class="{ disabled : dadosProposta.fase_proposta !== fase.id }"
                            :data-tooltip="fase.descricao"
                            :href="(fase.link) ? fase.link : 'javascript:void(0)'"
                            :style="(fase.link) ? '' : 'cursor: default'">
                            <i class="tiny material-icons left">{{fase.icon}}</i>
                            <span v-html="fase.label"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';

    export default {
        props: {
            idPreProjeto: null,
        },
        data() {
            return {
                fases: [
                    {
                        id: 'proposta_com_proponente',
                        label: 'Em edição',
                        descricao: 'A proposta está disponível para edição.',
                        link: `/proposta/manterpropostaincentivofiscal/identificacaodaproposta/idPreProjeto/${this.idPreProjeto}`,
                        icon: 'create',
                    },
                    {
                        id: 'proposta_analise_inicial',
                        label: 'Em Avalia&ccedil;&atilde;o',
                        descricao: 'A proposta está em avaliação pelo Ministério da Cultura, acompanhe na aba histórico de avaliações.',
                        icon: 'how_to_reg',
                    },
                    {
                        id: 'recurso_enquadramento',
                        label: 'Recurso Enquadramento',
                        descricao: 'Seu projeto foi enquadrado, caso discorde, você pode interpor recurso.',
                        link: `/recurso/recurso-proposta/visao-proponente/idPreProjeto/${this.idPreProjeto}`,
                        icon: 'build',
                    },
                    {
                        id: 'proposta_arquivada',
                        label: 'Arquivada',
                        descricao: 'Proposta arquivada, acompanhe na aba histórico de avaliações para saber mais.',
                        link: '/proposta/manterpropostaincentivofiscal/listar-propostas-arquivadas',
                        icon: 'archive',
                    },
                    {
                        id: 'projeto_cultural',
                        label: 'Projeto',
                        descricao: 'A proposta já é um projeto cultural.',
                        icon: 'beenhere',
                    },
                ],
            }
        },
        computed: {
            ...mapGetters({
                dadosProposta: 'proposta/proposta',
            }),
        },
    };
</script>
