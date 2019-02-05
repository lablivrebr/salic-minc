<template>
    <v-container fluid>
        <v-toolbar>
            <!-- Verificar o caminho de volta -->
            <v-btn
                :to="{ name: 'Painel'}"
                icon
                class="hidden-xs-only"
            >
                <v-icon>arrow_back</v-icon>
            </v-btn>
            <v-toolbar-title>
                Prestação de Contas: Comprovantes
            </v-toolbar-title>
        </v-toolbar>

        <v-card>
            <v-card-title primary-title>
                <h2>{{ dadosProjeto.Pronac }} &#45; {{ dadosProjeto.NomeProjeto }}</h2>
            </v-card-title>
            <v-card-text>
                <div class="my-3">
                    <div class="d-inline-block text-xs-right">
                        <h4>Data Início da Execução</h4>
                        {{ dadosProjeto.dtInicioExecucao | dataMasc }}
                    </div>
                    <div class="d-inline-block ml-5 text-xs-right">
                        <h4>Data Final da Execução</h4>
                        {{ dadosProjeto.dtFimExecucao | dataMasc }}
                    </div>
                    <div class="d-inline-block ml-5 text-xs-right">
                        <h4>Valor Aprovado</h4>
                        R$ {{ dadosProjeto.vlAprovado | moedaMasc }}
                    </div>
                    <div class="d-inline-block ml-5 text-xs-right">
                        <h4>Valor Comprovado</h4>
                        R$ {{ dadosProjeto.vlComprovado | moedaMasc }}
                    </div>
                    <div class="d-inline-block ml-5 text-xs-right">
                        <h4>Valor a Comprovar</h4>
                        R$ {{ dadosProjeto.vlComprovar | moedaMasc }}
                    </div>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn
                    :href="'/consultardadosprojeto/index?idPronac=' + idPronac"
                    color="success"
                    target="_blank"
                    class="mr-2"
                    dark
                >VER PROJETO</v-btn>
            </v-card-actions>
        </v-card>

    </v-container>
</template>

<script>
import Vue from 'vue';
import { mapActions, mapGetters } from 'vuex';
import Moeda from '../../../../filters/money';

Vue.filter('moedaMasc', Moeda);

export default {
    name: 'ComprovarPagamento',
    filters: {
        dataMasc(data) {
            const dataFormatada = data.replace(/-/g, '/');
            const date = new Date(dataFormatada);
            return date.toLocaleString(['pt-BR'], {
                month: '2-digit',
                day: '2-digit',
                year: '2-digit',
            });
        },
    },
    data() {
        return {
            idPronac: this.$route.params.id,
        };
    },
    computed: {
        ...mapGetters({
            getDadosProjeto: 'avaliacaoResultados/getDadosProjeto',
        }),
        dadosProjeto() {
            return this.getDadosProjeto;
        },
    },
    mounted() {
        this.setDadosProjeto(this.idPronac);
    },
    methods: {
        ...mapActions({
            setDadosProjeto: 'avaliacaoResultados/getDadosProjeto',
        }),
    },
};
</script>
