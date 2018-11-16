<template>
    <div>
        <salic-header-menu-principal-sidebar :dadosMenu="dadosMenu"></salic-header-menu-principal-sidebar>
        <v-toolbar
            app
            dark
            fixed
            clipped-left
            color="primary"
        >
            <v-toolbar-side-icon v-if="Object.keys(dadosSidebar).length > 0"
                                 @click.native="drawerLeft = !drawerLeft"></v-toolbar-side-icon>
            <salic-header-logo></salic-header-logo>
            <v-toolbar-title class="ml-1">{{tituloHeader}}</v-toolbar-title>
            <v-spacer></v-spacer>
            <salic-header-menu-principal-toolbar :dadosMenu="dadosMenu"></salic-header-menu-principal-toolbar>
            <salic-header-solicitacoes></salic-header-solicitacoes>
            <salic-header-informacoes-da-conta></salic-header-informacoes-da-conta>
            <v-divider vertical class="hidden-md-and-up"></v-divider>
            <v-toolbar-side-icon
                class="hidden-md-and-up"
                v-if="$vuetify.breakpoint.smAndDown"
                @click.stop="drawerRight = !drawerRight"
            ></v-toolbar-side-icon>
        </v-toolbar>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import SalicHeaderMenuPrincipalToolbar from './HeaderMenuPrincipalToolbar';
    import SalicHeaderMenuPrincipalSidebar from './HeaderMenuPrincipalSidebar';
    import SalicHeaderInformacoesDaConta from './HeaderInformacoesDaConta';
    import SalicHeaderSolicitacoes from './HeaderSolicitacoes';
    import SalicHeaderLogo from './HeaderLogo';

    export default {
        name: 'Header',
        components: {
            SalicHeaderInformacoesDaConta,
            SalicHeaderMenuPrincipalToolbar,
            SalicHeaderMenuPrincipalSidebar,
            SalicHeaderSolicitacoes,
            SalicHeaderLogo,
        },
        data() {
            return {
                drawerRight: false,
                drawerLeft: false,
            };
        },
        created() {
            this.buscarDadosMenu();
            this.buscarDadosLayout();
        },
        watch: {
            statusSidebarDireita(value) {
                this.drawerRight = value;
            },
            statusSidebarEsquerda(value) {
                this.drawerLeft = value;
            },
            drawerRight(value) {
                this.atualizarStatusSidebarDireita(value);
            },
            drawerLeft(value) {
                this.atualizarStatusSidebarEsquerda(value);
            },
        },
        computed: {
            ...mapGetters({
                dadosMenu: 'layout/getDadosMenu',
                dadosSidebar: 'layout/getDadosSidebar',
                statusSidebarDireita: 'layout/getStatusSidebarDireita',
                statusSidebarEsquerda: 'layout/getStatusSidebarEsquerda',
            }),
            tituloHeader() {
                if (this.$route.meta.title) {
                    return this.$route.meta.title;
                }
                return 'Salic';
            },
        },
        methods: {
            ...mapActions({
                buscarDadosMenu: 'layout/buscarDadosMenu',
                buscarDadosLayout: 'layout/buscarDadosLayout',
                atualizarStatusSidebarDireita: 'layout/atualizarStatusSidebarDireita',
                atualizarStatusSidebarEsquerda: 'layout/atualizarStatusSidebarEsquerda',
            }),
        },
    };
</script>
