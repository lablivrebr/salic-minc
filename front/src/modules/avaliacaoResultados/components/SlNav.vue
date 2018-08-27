<template>
    <div>
        <v-toolbar
          app
          color="green darken-4 "
          dense
          dark
        >
            <v-toolbar-title>
                <img src="/public/img/logo_salic.png" alt="logo" height="30px">
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-menu
                :nudge-width="100"
                v-for="item in dadosMenu"
                :key="item"
                offset-y
            >
                <v-toolbar-title
                    slot="activator"
                >
                    <v-btn flat>
                        <span v-html="item.label"></span> 
                        <v-icon right dark>arrow_drop_down</v-icon>
                    </v-btn>
                </v-toolbar-title>
                <v-list
                    v-for="menu in item.menu"
                    :key="item.menu"
                >
                    <v-list-tile>
                        <v-list-tile-title >
                            <a
                                :href="('/' + menu.url.module + '/' + menu.url.controller + '/' + menu.url.action)"
                                v-html="menu.label">
                            </a>
                        </v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-spacer></v-spacer>
            <v-menu
                :nudge-width="100"
                offset-y
            >
                <v-toolbar-title
                    slot="activator"
                >
                    <span>Olá, Romulo</span>
                    <v-icon dark>arrow_drop_down</v-icon>
                </v-toolbar-title>
                <v-list>
                    <v-list-tile>
                        <v-list-tile-title >
                            Teste
                        </v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
        </v-toolbar>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'SlNav',
    components: {
    },
    props: ['registroAtivo'],
    data() {
        return {
            currentRegistro: {
                Codigo: '',
                DadoNr: '',
            },
            items: ['pedro', 'leo'],
        };
    },
    computed: {
        ...mapGetters({
            dadosMenu: 'avaliacaoResultados/dadosMenu',
            modalVisible: 'modal/default',
        }),
    },
    mounted() {
        this.dadosMenuAjax();
    },
    methods: {
        ...mapActions({
            atualizarRegistro: 'avaliacaoResultados/atualizarRegistro',
            dadosMenuAjax: 'avaliacaoResultados/dadosMenu',
 //           setRegistroAtivo: 'avaliacaoResultados/setRegistroAtivo',
  //          modalOpen: 'modal/modalOpen',
   //         modalClose: 'modal/modalClose',
        }),
        buildRegistro(event) {
            const DadoNr = event.target.value;
            this.currentRegistro.DadoNr = DadoNr;
            this.currentRegistro.Codigo = this.registro.Codigo;
        },
        checkChangesAndUpdate() {
            if (this.currentRegistro !== this.registro) {
                this.atualizarRegistro(this.currentRegistro);
            }
        },
        fecharModal() {
            // eslint-disable-next-line
            $3('#modalTemplate').modal('close');
            this.modalClose();
        },
        alert() {
            alert('teste');
        },
    },
};
</script>
