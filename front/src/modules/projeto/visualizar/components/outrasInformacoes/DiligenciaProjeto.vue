<template>
    <div id="conteudo">
        <div v-if="loading">
            <Carregando :text="'Carregando Diligencias do Projeto'"></Carregando>
        </div>
        <div v-else>
            <IdentificacaoProjeto
                    :pronac="dadosProjeto.Pronac"
                    :nomeProjeto="dadosProjeto.NomeProjeto"
            >
            </IdentificacaoProjeto>
            <div v-if="dados.diligenciaProposta">
                <fieldset style="margin: 0px;">
                    <legend>Dilig&ecirc;ncia Proposta</legend>
                    <VisualizarDiligenciaProposta
                            :idPronac="idPronac"
                            :diligencias="dados.diligenciaProposta"
                    >
                    </VisualizarDiligenciaProposta>
                </fieldset>
            </div>
            <div v-if="dados.diligenciaAdequacao">
                <fieldset style="margin: 0px;">
                    <legend>Dilig&ecirc;ncias da Adequa&ccedil;&atilde;o do Projeto</legend>
                    <VisualizarDiligenciaAdequacao
                            :idPronac="idPronac"
                            :diligencias="dados.diligenciaAdequacao"
                    >
                    </VisualizarDiligenciaAdequacao>
                </fieldset>
            </div>
            <div v-if="dados.diligenciaProjeto">
                <fieldset style="margin: 0px;">
                    <legend>Dilig&ecirc;ncia Projeto</legend>
                    <VisualizarDiligenciaProjeto
                            :idPronac="idPronac"
                            :diligencias="dados.diligenciaProjeto"
                    >
                    </VisualizarDiligenciaProjeto>
                </fieldset>
            </div>
        </div>
    </div>
</template>
<script>
    import { mapGetters, mapActions } from 'vuex';
    import Carregando from '@/components/Carregando';
    import IdentificacaoProjeto from './IdentificacaoProjeto';
    import VisualizarDiligenciaProposta from './components/VisualizarDiligenciaProposta';
    import VisualizarDiligenciaAdequacao from './components/VisualizarDiligenciaAdequacao';
    import VisualizarDiligenciaProjeto from './components/VisualizarDiligenciaProjeto';

    export default {
        name: 'DiligenciaProjeto',
        components: {
            Carregando,
            IdentificacaoProjeto,
            VisualizarDiligenciaProposta,
            VisualizarDiligenciaAdequacao,
            VisualizarDiligenciaProjeto,
        },
        data() {
            return {
                loading: true,
                idPronac: {},
            };
        },
        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarDiligencia(this.dadosProjeto.idPronac);
            }
        },
        watch: {
            dados() {
                this.loading = false;
            },
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dados: 'projeto/diligencia',
            }),
        },
        methods: {
            ...mapActions({
                buscarDiligencia: 'projeto/buscarDiligencia',
            }),
        },
    };
</script>

