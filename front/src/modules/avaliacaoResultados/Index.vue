<template>
    <div id="app">
        <v-app>
            <SlNav></SlNav>
            <v-content>
                <v-container fluid>
                  <v-layout>
                    <router-view></router-view>
                  </v-layout>
                </v-container>
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
import Rodape from '@/components/layout/footer';
import SlNav from './components/SlNav';

export default {
    name: 'Index',
    components: { SlNav, Rodape },
    methods: {
        ...mapActions({
            setSnackbar: 'noticias/setDados',
            setUsuario: 'autenticacao/usuarioLogado',
        }),
        fecharSnackbar() {
            this.setSnackbar({ ativo: false });
        },
    },
    computed: {
        ...mapGetters({
            getSnackbar: 'noticias/getDados',
        }),
    },
    mounted() {
        this.setSnackbar({ ativo: false, color: 'success' });
        this.setUsuario();
    },
    data() {
        return {
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
