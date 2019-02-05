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
                    <div class="d-inline-block mr-5">
                        <h4>Data Início da Execução</h4>
                        <p class="text-xs-left">{{ dadosProjeto.dtInicioExecucao | dataMasc }}</p>
                    </div>
                    <div class="d-inline-block mr-5">
                        <h4>Data Final da Execução</h4>
                        <p class="text-xs-left">{{ dadosProjeto.dtFimExecucao | dataMasc }}</p>
                    </div>
                    <div class="d-inline-block mr-5">
                        <h4>Valor Aprovado</h4>
                        <p class="text-xs-left">R$ {{ dadosProjeto.vlAprovado | moedaMasc }}</p>
                    </div>
                    <div class="d-inline-block mr-5">
                        <h4>Valor Comprovado</h4>
                        <p class="text-xs-left">R$ {{ dadosProjeto.vlComprovado | moedaMasc }}</p>
                    </div>
                    <div class="d-inline-block">
                        <h4>Valor a Comprovar</h4>
                        <p class="text-xs-left">R$ {{ dadosProjeto.vlComprovar | moedaMasc }}</p>
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

        <v-card class="mt-3">
            <v-card-title primary-title>
                <h2>Item: {{ dadosItem.Item }}</h2>
            </v-card-title>
            <v-card-text>
                <div class="my-3">
                    <div class="d-inline-block mr-5">
                        <h4>Produto</h4>
                        <p class="text-xs-left">{{ dadosItem.Produto }}</p>
                    </div>
                    <div class="d-inline-block mr-5">
                        <h4>Etapa</h4>
                        <p class="text-xs-left">{{ dadosItem.Etapa }}</p>
                    </div>
                    <div class="d-inline-block mr-5">
                        <h4>UF</h4>
                        <p class="text-xs-left">{{ dadosItem.uf }}</p>
                    </div>
                    <div class="d-inline-block mr-5">
                        <h4>Cidade</h4>
                        <p class="text-xs-left">{{ dadosItem.cidade }}</p>
                    </div>
                    <div class="d-inline-block mr-5">
                        <h4>Itens de Custo</h4>
                        <p class="text-xs-left">{{ dadosItem.Item }}</p>
                    </div>
                    <div class="d-inline-block mr-5">
                        <h4>Aprovado</h4>
                        <p class="text-xs-left">R$ {{ dadosItem.vlAprovado | moedaMasc }}</p>
                    </div>
                    <div class="d-inline-block mr-5">
                        <h4>Total Comprovado</h4>
                        <p class="text-xs-left">R$ {{ dadosItem.vlComprovado | moedaMasc }}</p>
                    </div>
                    <div class="d-inline-block">
                        <h4>Faltando Comprovar</h4>
                        <p class="text-xs-left">R$ {{ dadosItem.vlAprovado - dadosItem.vlComprovado | moedaMasc }}</p>
                    </div>
                </div>
            </v-card-text>
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
            // idUf: this.$route.params.idUf,
            uf: this.$route.params.uf,
            produto: this.$route.params.produto,
            cidade: this.$route.params.cidade,
            etapa: this.$route.params.etapa,
            // idPlanilhaAprovacao: this.$route.params.idPlanilha,
            idPlanilhaItens: this.$route.params.idItens,
        };
    },
    computed: {
        ...mapGetters({
            getDadosProjeto: 'avaliacaoResultados/getDadosProjeto',
            getDadosItem: 'avaliacaoResultados/getDadosItem',
        }),
        dadosProjeto() {
            return this.getDadosProjeto;
        },
        dadosItemParams() {
            return {
                idPronac: this.idPronac,
                uf: this.uf,
                etapa: this.etapa,
                cidade: this.cidade,
                produto: this.produto,
                idPlanilhaItens: this.idPlanilhaItens,
            };
        },
        dadosItem() {
            return this.getDadosItem;
        },
    },
    mounted() {
        this.setDadosProjeto(this.idPronac);
        this.setDadosItem(this.dadosItemParams);
    },
    methods: {
        ...mapActions({
            setDadosProjeto: 'avaliacaoResultados/getDadosProjeto',
            setDadosItem: 'avaliacaoResultados/getDadosItem',
        }),
    },
};
</script>
