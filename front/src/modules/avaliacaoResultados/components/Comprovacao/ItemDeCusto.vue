<template>
    <v-container fluid>
        <!-- Criar Comprovante -->
        <comprovar-pagamento
            v-if="dadosProjeto.dtInicioExecucao"
            :data-inicio-formatada="dadosProjeto.dtInicioExecucao | dataFilter"
            :data-fim-formatada="dadosProjeto.dtFimExecucao | dataFilter"
            :data-inicio="dadosProjeto.dtInicioExecucao"
            :data-fim="dadosProjeto.dtFimExecucao"
            :valor-comprovar="valorComprovar | moedaFilter"
        />
        <v-toolbar>
            <!-- Verificar o caminho de volta -->
            <v-btn
                :to="{ name: 'PlanilhaComprovacao', params: { id: idPronac }}"
                icon
                class="hidden-xs-only"
            >
                <v-icon>arrow_back</v-icon>
            </v-btn>
            <v-toolbar-title>
                Prestação de Contas: Comprovantes
            </v-toolbar-title>
        </v-toolbar>

        <v-card v-if="dadosProjeto.NomeProjeto">
            <v-card-title primary-title>
                <h2>{{ dadosProjeto.Pronac }} &#45; {{ dadosProjeto.NomeProjeto }}</h2>
            </v-card-title>
            <v-card-text>
                <div class="my-3">
                    <div class="d-inline-block mr-5">
                        <h4>Data Início da Execução</h4>
                        <p class="text-xs-left">{{ dadosProjeto.dtInicioExecucao | dataFilter }}</p>
                    </div>
                    <div class="d-inline-block mr-5">
                        <h4>Data Final da Execução</h4>
                        <p class="text-xs-left">{{ dadosProjeto.dtFimExecucao | dataFilter }}</p>
                    </div>
                    <div class="d-inline-block mr-5">
                        <h4>Valor Aprovado</h4>
                        <p class="text-xs-left">R$ {{ dadosProjeto.vlAprovado | moedaFilter }}</p>
                    </div>
                    <div class="d-inline-block mr-5">
                        <h4>Valor Comprovado</h4>
                        <p class="text-xs-left">R$ {{ dadosProjeto.vlComprovado | moedaFilter }}</p>
                    </div>
                    <div class="d-inline-block">
                        <h4>Valor a Comprovar</h4>
                        <p class="text-xs-left">R$ {{ dadosProjeto.vlComprovar | moedaFilter }}</p>
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

        <v-card
            v-if="dadosItem.Item"
            class="mt-3"
        >
            <v-card-title primary-title>
                <h2>Item: {{ dadosItem.Item }}</h2>
            </v-card-title>
            <v-card-text>
                <div class="my-3">
                    <div class="d-inline-block mr-5">
                        <h4>Produto</h4>
                        <p
                            class="text-xs-left"
                            v-html="dadosItem.Produto"/>
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
                        <p class="text-xs-left">R$ {{ dadosItem.vlAprovado | moedaFilter }}</p>
                    </div>
                    <div class="d-inline-block mr-5">
                        <h4>Total Comprovado</h4>
                        <p class="text-xs-left">R$ {{ dadosItem.vlComprovado | moedaFilter }}</p>
                    </div>
                    <div class="d-inline-block">
                        <h4>Faltando Comprovar</h4>
                        <p class="text-xs-left">R$ {{ valorComprovar | moedaFilter }}</p>
                    </div>
                </div>
            </v-card-text>
        </v-card>

        <v-card class="mt-3">
            <v-card-title primary-title>
                <h2>Comprovantes Nacionais</h2>
            </v-card-title>
            <v-card-text>
                <comprovante
                    :id-pronac="String(idPronac)"
                    :id-planilha-itens="String(idPlanilhaItens)"
                    :produto="String(produto)"
                    :uf="String(uf)"
                    :id-uf="String(idUf)"
                    :cidade="String(cidade)"
                    :etapa="String(etapa)"
                    tipo="nacional"
                />
            </v-card-text>
        </v-card>

        <v-card class="mt-3">
            <v-card-title primary-title>
                <h2>Comprovantes Internacionais</h2>
            </v-card-title>
            <v-card-text>
                <comprovante
                    :id-pronac="String(idPronac)"
                    :id-planilha-itens="String(idPlanilhaItens)"
                    :produto="String(produto)"
                    :uf="String(uf)"
                    :id-uf="String(idUf)"
                    :cidade="String(cidade)"
                    :etapa="String(etapa)"
                    tipo="internacional"
                />
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
import Vue from 'vue';
import { mapActions, mapGetters } from 'vuex';
import Moeda from '../../../../filters/money';
import Comprovante from './Comprovante';
import ComprovarPagamento from './ComprovarPagamento';

Vue.filter('moedaFilter', Moeda);

export default {
    name: 'ItemDeCusto',
    components: {
        Comprovante,
        ComprovarPagamento,
    },
    filters: {
        dataFilter(data) {
            const dataFormatada = data.replace(/-/g, '/');
            const date = new Date(dataFormatada);
            return date.toLocaleString(['pt-BR'], {
                month: '2-digit',
                day: '2-digit',
                year: 'numeric',
            });
        },
    },
    data() {
        return {
            idPronac: this.$route.params.id,
            idUf: this.$route.params.idUf,
            uf: this.$route.params.uf,
            produto: this.$route.params.produto,
            cidade: this.$route.params.cidade,
            etapa: this.$route.params.etapa,
            idPlanilhaAprovacao: this.$route.params.idPlanilhaAprovacao,
            idPlanilhaItens: this.$route.params.idPlanilhaItens,
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
        valorComprovar() {
            return this.dadosItem.vlAprovado - this.dadosItem.vlComprovado;
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
