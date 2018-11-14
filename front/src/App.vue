<template>
    <div id="app">
        <v-app :dark="isModoNoturno">
            <salic-sidebar></salic-sidebar>
            <salic-header></salic-header>
            <v-content>
                <router-view></router-view>
            </v-content>

            <v-snackbar
                v-model="snackbar"
                :color="getSnackbar.color"
                :top="true"
                :left="true"
                :timeout="2000"
                @input="fecharSnackbar"
            >
                {{ this.getSnackbar.text }}
            </v-snackbar>
            <salic-footer></salic-footer>
        </v-app>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import SalicSidebar from '@/components/layout/sidebar';
    import SalicHeader from '@/components/layout/header';
    import SalicFooter from '@/components/layout/footer';

    export default {
        name: 'Index',
        components: { SalicSidebar, SalicHeader, SalicFooter },
        methods: {
            ...mapActions({
                setSnackbar: 'noticias/setDados',
                setUsuario: 'autenticacao/usuarioLogado',
                obterModoNoturno: 'layout/obterModoNoturno',
            }),
            fecharSnackbar() {
                this.setSnackbar({ ativo: false });
            },
        },
        computed: {
            ...mapGetters({
                getSnackbar: 'noticias/getDados',
                isModoNoturno: 'layout/modoNoturno',
            }),
        },
        mounted() {
            this.setSnackbar({ ativo: false, color: 'success' });
            this.setUsuario();
            this.obterModoNoturno();
        },
        data() {
            return {
                dark: false,
                snackbar: false,
            };
        },
        watch: {
            getSnackbar(val) {
                this.snackbar = val.ativo;
            },
        },
    };
</script>
