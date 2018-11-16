<template>
    <v-container fluid>
        <v-layout row wrap>
            <v-flex xs12 md12 class="mb-4">
                <Carregando v-if="carregando" :text="'Validando acesso ao projeto'"></Carregando>
                <div v-else-if="Object.keys(dadosProjeto).length > 0 && permissao">
                    <div class="container-fluid">
                        <Dashboard/>
                    </div>
                </div>
                <div v-if="!permissao">
                    <SalicMensagemErro :texto="'Sem permiss&atilde;o de acesso para este projeto'"/>
                </div>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex';
    import { utils } from '@/mixins/utils';
    import SidebarMenu from '@/components/SidebarMenu';
    import Carregando from '@/components/Carregando';
    import TituloPagina from '@/components/TituloPagina';
    import SalicMensagemErro from '@/components/SalicMensagemErro';
    import MenuSuspenso from '../components/MenuSuspenso';
    import Dashboard from './components/incentivo/Dashboard';

    export default {
        name: 'Index',
        components: {
            SidebarMenu,
            TituloPagina,
            MenuSuspenso,
            Carregando,
            SalicMensagemErro,
            Dashboard,
        },
        mixins: [utils],
        data() {
            return {
                carregando: true,
                permissao: true,
            };
        },
        watch: {
            $route(to, from) {
                /**
                 * se alterar apenas o parametro na url, o vue nao recarrega o componente.
                 * aqui esta recarregando os dados do novo projeto se o idPronac for diferente
                 * */
                if (
                    typeof to.params.idPronac !== 'undefined' &&
                    to.params.idPronac !== from.params.idPronac
                ) {
                    this.buscaProjeto(to.params.idPronac);
                    this.obterDadosSidebar(to.params.idPronac);
                }
            },
            dadosProjeto(value) {
                if (Object.keys(value).length > 0) {
                    this.carregando = false;
                    this.permissao = value.permissao;
                }
            },
        },
        created() {
            if (Object.keys(this.dadosProjeto).length === 0
            && typeof this.$route.params.idPronac !== 'undefined') {
                this.buscaProjeto(this.$route.params.idPronac);
                this.obterDadosSidebar(this.$route.params.idPronac);
            }
        },
        methods: {
            ...mapActions({
                buscaProjeto: 'projeto/buscarProjetoCompleto',
                obterDadosSidebar: 'layout/obterDadosMenuProjeto',
            }),
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
            }),
        },
    };
</script>
