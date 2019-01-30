<template>
    <carregando
        v-if="!dadosProjeto"
        :text="'Carregando ...'"/>
    <v-container
        v-else
        fluid>
        <v-toolbar>
            <!-- Verificar o destino do retorno -->
            <v-btn
                :to="{ name: 'Painel'}"
                icon
                class="hidden-xs-only"
            >
                <v-icon>arrow_back</v-icon>
            </v-btn>
            <v-toolbar-title>Prestação de Contas: Comprovação Financeira</v-toolbar-title>
        </v-toolbar>
        <v-card>
            <v-card-title primary-title>
                <h2>{{ dadosProjeto.items.pronac }} &#45; {{ dadosProjeto.items.nomeProjeto }}</h2>
            </v-card-title>
            <v-card-text>
                <div class="my-3">
                    <v-progress-circular
                        :value="porcentagemComprovada"
                        color="success"
                        rotate="270"
                        size="125"
                        width="15"
                        class="d-inline-block ml-3"
                    >
                        {{ porcentagemComprovada }}%
                    </v-progress-circular>
                    <div class="d-inline-block ml-5">
                        <h4>Valor Aprovado</h4>
                        {{ moeda(dadosProjeto.items.vlAprovado) }}
                    </div>
                    <div class="d-inline-block ml-5">
                        <v-icon color="success">label</v-icon>
                        <h4>Valor Comprovado</h4>
                        {{ moeda(dadosProjeto.items.vlComprovado) }}
                    </div>
                    <div class="d-inline-block ml-5">
                        <v-icon color="#e6e6e6">label</v-icon>
                        <h4>Valor a Comprovar</h4>
                        {{ moeda(dadosProjeto.items.vlTotalComprovar) }}
                    </div>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn
                    :href="'/consultardadosprojeto/index?idPronac=' + idPronac"
                    color="success"
                    target="_blank"
                    class="ml-1"
                >VER PROJETO
                </v-btn>
            </v-card-actions>
        </v-card>
        <template v-if="Object.keys(dadosComprovacao).length > 0">
            <v-card
                class="mt-3"
                flat>
                <!-- PRODUTO -->
                <v-expansion-panel
                    :value="expandir(getDadosComprovacao)"
                    expand
                >
                    <v-expansion-panel-content
                        v-for="(produto,i) in getDadosComprovacao"
                        :key="i"
                    >
                        <v-layout
                            slot="header"
                            class="green--text">
                            <v-icon class="mr-3 green--text">perm_media</v-icon>
                            {{ produto.produto }}
                        </v-layout>
                        <!-- ETAPA -->
                        <v-expansion-panel
                            :value="expandir(produto)"
                            class="pl-3 elevation-0"
                            expand
                        >
                            <v-expansion-panel-content
                                v-for="(etapa,i) in produto.etapa"
                                :key="i"
                            >
                                <v-layout
                                    slot="header"
                                    class="orange--text">
                                    <v-icon class="mr-3 orange--text">label</v-icon>
                                    {{ etapa.etapa }}
                                </v-layout>
                                <!-- UF -->
                                <v-expansion-panel
                                    :value="expandir(etapa)"
                                    class="pl-3 elevation-0"
                                    expand
                                >
                                    <v-expansion-panel-content
                                        v-for="(uf,i) in etapa.UF"
                                        :key="i"
                                    >
                                        <v-layout
                                            slot="header"
                                            class="blue--text">
                                            <v-icon class="mr-3 blue--text">place</v-icon>
                                            {{ uf.Uf }}
                                        </v-layout>
                                        <!-- CIDADE -->
                                        <v-expansion-panel
                                            :value="expandir(uf)"
                                            class="pl-3 elevation-0"
                                            expand
                                        >
                                            <v-expansion-panel-content
                                                v-for="(cidade,i) in uf.cidade"
                                                :key="i"
                                            >
                                                <v-layout
                                                    slot="header"
                                                    class="blue--text">
                                                    <v-icon class="mr-3 blue--text">place</v-icon>
                                                    {{ cidade.cidade }}
                                                </v-layout>
                                                <template v-if="typeof cidade.itens !== 'undefined'">
                                                    <v-data-table
                                                        :headers="headers"
                                                        :items="Object.values(cidade.itens)"
                                                        hide-actions
                                                    >
                                                        <template
                                                            slot="items"
                                                            slot-scope="props">
                                                            <td>{{ props.item.item }}</td>
                                                            <td>{{ moeda(props.item.varlorAprovado) }}</td>
                                                            <td>{{ moeda(props.item.varlorComprovado) }}</td>
                                                            <td>{{ moeda(props.item.varlorAprovado -
                                                            props.item.varlorComprovado) }}
                                                            </td>
                                                            <td>
                                                                <v-btn
                                                                    slot="activator"
                                                                    color="teal"
                                                                    dark
                                                                    @click="visualizarComprovantes(
                                                                        uf.Uf,
                                                                        cidade.cdCidade,
                                                                        produto.cdProduto,
                                                                        props.item.stItemAvaliado,
                                                                        etapa.cdEtapa,
                                                                        props.item.idPlanilhaItens,
                                                                        props.item.item,
                                                                    )"
                                                                >
                                                                    <v-icon dark>attach_money</v-icon>
                                                                </v-btn>
                                                            </td>
                                                        </template>
                                                    </v-data-table>
                                                </template>
                                            </v-expansion-panel-content>
                                        </v-expansion-panel>
                                    </v-expansion-panel-content>
                                </v-expansion-panel>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-card>
        </template>
        <carregando
            v-else
            :text="'Carregando...'"/>
    </v-container>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
import Carregando from '@/components/CarregandoVuetify';

export default {
    name: 'Painel',
    components: {
        Carregando,
    },
    data() {
        return {
            headers: [
                { text: 'Item de Custo', value: 'item', sortable: false },
                { text: 'Valor Aprovado', value: 'varlorAprovado', sortable: false },
                { text: 'Valor Comprovado', value: 'varlorComprovado', sortable: false },
                { text: 'Valor a Comprovar', value: 'valorAComprovar', sortable: false },
                { text: '', value: 'comprovarItem', sortable: false },
            ],
            fab: false,
            idPronac: this.$route.params.id,
            itemEmVisualizacao: {},
        };
    },
    computed: {
        ...mapGetters({
            getDadosComprovacao: 'avaliacaoResultados/getDadosComprovacao',
            getProjetoAnalise: 'avaliacaoResultados/projetoAnalise',
        }),
        dadosProjeto() {
            return this.getProjetoAnalise.data;
        },
        getPronac() {
            return parseInt(this.idPronac, 10);
        },
        dadosComprovacao() {
            const dadosComprovacao = this.getDadosComprovacao || {};
            return dadosComprovacao;
        },
        porcentagemComprovada() {
            const valorComprovado = parseFloat(this.dadosProjeto.items.vlComprovado);
            const valorAComprovar = parseFloat(this.dadosProjeto.items.vlTotalComprovar);
            const porcentagem = valorComprovado * 100 / valorAComprovar;
            return Math.round(porcentagem);
        },
    },
    mounted() {
        this.setDadosComprovacao(this.idPronac);
        this.setProjetoAnalise(this.idPronac);
    },
    methods: {
        ...mapActions({
            setDadosComprovacao: 'avaliacaoResultados/getDadosComprovacao',
            setProjetoAnalise: 'avaliacaoResultados/projetoAnalise',
            modalOpen: 'modal/modalOpen',
            modalClose: 'modal/modalClose',
        }),
        moeda: (moedaString) => {
            const moeda = Number(moedaString);
            return moeda.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
        },
        visualizarComprovantes(
            Uf,
            cdCidade,
            cdProduto,
            stItemAvaliado,
            cdEtapa,
            idPlanilhaItens,
            item,
        ) {
            this.itemEmVisualizacao = {
                Uf,
                cdCidade,
                cdProduto,
                stItemAvaliado,
                cdEtapa,
                idPlanilhaItens,
                item,
            };

            this.modalOpen('visualizar-comprovantes');
        },
        expandir(obj) {
            const arr = [];
            const items = Object.keys(obj).length;
            for (let i = 0; i < items; i += 1) {
                arr.push(true);
            }
            return arr;
        },
    },
};
</script>
