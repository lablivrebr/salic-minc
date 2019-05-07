<template>
    <div id="app">
        <v-app >
            <v-content>
                <v-container
                    fluid>
                    <v-layout>
                        <v-fade-transition mode="out-in">
                            <router-view/>
                        </v-fade-transition>
                    </v-layout>
                </v-container>
            </v-content>
        </v-app>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import Rodape from '@/components/layout/footer';

export default {
    name: 'Index',
    components: { Rodape },
    data() {
        return {
            dark: false,
            snackbar: false,
        };
    },
    computed: {
        ...mapGetters({
            getSnackbar: 'noticias/getDados',
            isModoNoturno: 'layout/modoNoturno',
            usuario: 'autenticacao/getUsuario',
        }),
    },
    watch: {
        getSnackbar(val) {
            this.snackbar = val.ativo;
        },
    },
    created() {
    },
    mounted() {
    },
    methods: {
        ...mapActions({
            setSnackbar: 'noticias/setDados',
            setUsuario: 'autenticacao/usuarioLogado',
            obterModoNoturno: 'layout/obterModoNoturno',
            recoverAction: 'autenticacao/recoverAction',
        }),
        fecharSnackbar() {
            this.setSnackbar({ ativo: false });
        },
    },
};
</script>
