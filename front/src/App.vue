<template>
    <div id="app">
        <v-app :dark="isModoNoturno">
            <Cabecalho></Cabecalho>
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
            <Rodape></Rodape>
        </v-app>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import Cabecalho from '@/components/layout/header';
    import Rodape from '@/components/layout/footer';

    export default {
        name: 'Index',
        components: { Cabecalho, Rodape },
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
