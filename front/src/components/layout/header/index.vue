<template>
    <div v-if="dados">
        <v-toolbar
            app
            dense
            dark
            color="primary"
            :clipped-left="$vuetify.breakpoint.mdAndUp"
            fixed
            height="50px"
        >
            <!--<v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>-->
            <Logo></Logo>
            <v-toolbar-title class="ma-0 hidden-sm-and-down">Salic</v-toolbar-title>
            <v-spacer></v-spacer>
            <MenuPrincipal :dadosMenu="dadosMenu"></MenuPrincipal>
            <Solicitacoes></Solicitacoes>
            <InformacoesDaConta></InformacoesDaConta>
        </v-toolbar>
    </div>
</template>

<script>
    import { mapActions } from 'vuex';
    import MenuPrincipal from './HeaderMenuPrincipal';
    import InformacoesDaConta from './HeaderInformacoesDaConta';
    import Solicitacoes from './HeaderSolicitacoes';
    import Logo from './HeaderLogo';

    export default {
        name: 'Header',
        components: { InformacoesDaConta, MenuPrincipal, Solicitacoes, Logo },
        props: {
            dadosMenu: {},
        },
        data() {
            return {
                dados: this.dadosMenu,
            };
        },
        created() {
            this.buscarDadosLayout();
        },
        methods: {
            ...mapActions({
                buscarDadosLayout: 'layout/buscarDadosLayout',
            }),
        },
    };
</script>
