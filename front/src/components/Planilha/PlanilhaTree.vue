<template>
    <v-card>
        <v-toolbar
            card
            color="grey lighten-3"
        >
            <v-icon>mdi-silverware</v-icon>
            <v-toolbar-title>Local hotspots</v-toolbar-title>
        </v-toolbar>

        <!--{{ planilhaMontada }}-->

        <v-layout>
            <v-flex>
                <v-card-text>
                    <v-treeview
                        v-model="tree"
                        :items="planilhaMontada"
                        :open="open"
                        item-key="name"
                        activatable
                        open-all
                        open-on-click

                    >
                        <template v-slot:prepend="{ item, open }">
                            <v-icon v-if="true">
                                {{ open ? 'mdi-folder-open' : 'mdi-folder' }}
                            </v-icon>
                        </template>
                        <template
                            slot="append"
                            slot-scope="slotProps">
                            <v-chip
                                v-if="slotProps.item.children"
                                outline="outline"
                                label="label"
                                color="#565555"
                            >
                                R$ 100
                            </v-chip>
                            <div
                                v-if="slotProps.item.itens">
                                <s-planilha-itens-padrao :table="slotProps.item.itens"/>
                            </div>
                        </template>
                        <!--<template-->
                            <!--slot="prepend"-->
                            <!--slot-scope="slotProps">-->
                          <!---->
                        <!--</template>-->
                    </v-treeview>
                </v-card-text>
            </v-flex>
            <v-flex>
                <v-card-text>
                    <!--<v-treeview-->
                    <!--v-model="tree"-->
                    <!--:load-children="fetch"-->
                    <!--:items="items"-->
                    <!--activatable-->
                    <!--active-class="grey lighten-4 indigo&#45;&#45;text"-->
                    <!--selected-color="indigo"-->
                    <!--open-on-click-->
                    <!--selectable-->
                    <!--expand-icon="mdi-chevron-down"-->
                    <!--on-icon="mdi-bookmark"-->
                    <!--off-icon="mdi-bookmark-outline"-->
                    <!--indeterminate-icon="mdi-bookmark-minus"-->
                    <!--&gt;<div>teste</div></v-treeview>-->
                </v-card-text>
            </v-flex>

            <v-divider vertical/>

            <v-flex
                xs12
                md6
            >
                <v-card-text>
                    <div
                        v-if="selections.length === 0"
                        key="title"
                        class="title font-weight-light grey--text pa-3 text-xs-center"
                    >
                        Select your favorite breweries
                    </div>

                    <v-scroll-x-transition
                        group
                        hide-on-leave
                    >
                        <v-chip
                            v-for="(selection, i) in selections"
                            :key="i"
                            color="grey"
                            dark
                            small
                        >
                            <v-icon
                                left
                                small>mdi-beer</v-icon>
                            {{ selection.name }}
                        </v-chip>
                    </v-scroll-x-transition>
                </v-card-text>
            </v-flex>
        </v-layout>

        <v-divider/>

        <v-card-actions>
            <v-btn
                flat
                @click="tree = []"
            >
                Reset
            </v-btn>

            <v-spacer/>

            <v-btn
                class="white--text"
                color="green darken-1"
                depressed
            >
                Save
                <v-icon right>mdi-content-save</v-icon>
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import PlanoDivulgacao from '@/modules/projeto/visualizar/components/prestacaoContas/components/PlanoDivulgacao';
import SPlanilhaItensPadrao from '@/components/Planilha/PlanilhaItensPadrao';

export default {
    components: { SPlanilhaItensPadrao, PlanoDivulgacao },
    props: {
        arrayPlanilha: {
            type: [Array],
            default: () => [],
        },
        headers: {
            type: Array,
            default: () => [
                {
                    id: 1,
                    icon: 'beenhere',
                    color: '#F44336',
                },
                {
                    id: 2,
                    icon: 'perm_media',
                    color: '#4CAF50',
                },
                {
                    id: 3,
                    icon: 'label',
                    color: '#ff9800',
                },
                {
                    id: 4,
                    icon: 'place',
                    color: '#2196F3',
                },
                {
                    id: 5,
                    icon: 'location_city',
                    color: '#2196F3',
                },
            ],
        },
        agrupamentos: {
            type: Array,
            default: () => ['FonteRecurso', 'Produto', 'Etapa', 'UF', 'Cidade'],
        },
        totais: {
            type: Array,
            default: () => [
                {
                    label: 'Vl. Solicitado',
                    column: 'vlSolicitado',
                },
            ],
        },
        expandAll: {
            type: Boolean,
            default: true,
        },
        listItems: {
            type: Boolean,
            default: false,
        },
    },
    data: () => ({
        breweries: [],
        isLoading: false,
        tree: [],
        types: [],
        oqueVai: [],
        open: [1, 2],
    }),
    computed: {
        items() {
            const children = this.types.map(type => ({
                id: type,
                name: this.getName(type),
                children: this.getChildren(type),
            }));

            return [{
                id: 1,
                name: 'All Breweries',
                children,
            }];
        },
        selections() {
            const selections = [];

            for (const leaf of this.tree) {
                const brewery = this.breweries.find(brewery => brewery.id === leaf);

                if (!brewery) continue;

                selections.push(brewery);
            }

            return selections;
        },
        shouldShowTree() {
            return this.breweries.length > 0 && !this.isLoading;
        },
        planilhaMontada() {
            if (this.arrayPlanilha.length === 0) {
                return [];
            }

            // const a = {
            //     id: 1,
            //     name: 'All Breweries',
            //     children: [
            //         { id: 12, name: 'v-btn : ts' },
            //         { id: 13, name: 'v-card : ts' },
            //         { id: 14, name: 'v-window : ts' },
            //     ],
            // };

            // for (const agrupa of this.agrupamentos) {
            //     this.oqueVai = this.arrayPlanilha.reduce((acc, cur) => {
            //         const type = cur[agrupa];
            //         console.log('type', type);
            //         if (!acc.includes(type)) {
            //             acc.push(this.getElement(type));
            //             console.log('qntas', this.getElement(type));
            //         }
            //         console.log('acc', acc);
            //         return acc;
            //     }, []).sort();
            // }

            /**
             * {
              "FonteRecurso": " Incentivo Fiscal Federal",
              "Cidade": "São Paulo",
              "UF": "SP",
              "Produto": "Bem Imóvel - Reforma / Ampliação / Construção / Aquisição",
            }, */
            const self = this;
            /* eslint-disable no-param-reassign */
            const groupBy = (planilha, agrupamentos) => planilha.reduce((prev, item) => {
                let i = 0;
                function agruparItens(colunas) {
                    const key = agrupamentos[i]; // key = FonteRecurso
                    const id = item[key]; // id = Incentivo
                    i += 1;
                    // (colunas[item[key]] = colunas[item[key]] || Object.assign({}, colunas[item[key]]) || {});

                    // coluna = [incentivo fiscal]
                    let currentIndex = colunas.findIndex(element => element.id === id);
                    if (currentIndex < 0) {
                        currentIndex = colunas.push(self.construirElemento(id)) - 1;
                    }
                    // console.log('coluns', colunas);
                    // const isItemExcluido = item.tpAcao && item.tpAcao === 'E';
                    // calculando os totais
                    // const qtdTotais = self.totais.length;
                    // if (!isItemExcluido) {
                    //     for (let y = 0; y < qtdTotais; y += 1) {
                    //         const b = self.totais[y].column;
                    //         colunas[item[key]] = Object.assign(colunas[item[key]], { [b]: (colunas[item[key]][b] + item[b]) || item[b] });
                    //     }
                    // }

                    /**
                     *  se for o ultimo elemento do agrupamento(fonte, produto, etapa, uf)
                     *  insere os itens, se nao, vai para o proximo agrupamento.
                      */
                    if (agrupamentos[agrupamentos.length - 1] === key) {
                        if (!colunas[currentIndex].itens) {
                            (colunas[currentIndex] = Object.assign(colunas[currentIndex], { itens: [] }));
                        }
                        colunas[currentIndex].itens.push(item);
                    } else {
                        agruparItens(colunas[currentIndex].children);
                    }
                    return colunas;
                }
                return agruparItens(prev);
            }, []);
            return groupBy(this.arrayPlanilha, this.agrupamentos);
        },
    },

    watch: {
        breweries(val) {
            this.types = val.reduce((acc, cur) => {
                const type = cur.brewery_type;

                if (!acc.includes(type)) acc.push(type);

                return acc;
            }, []).sort();
        },
    },
    methods: {
        fetch() {
            if (this.breweries.length) return;

            return;
            return fetch('https://api.openbrewerydb.org/breweries')
                .then(res => res.json())
                .then(data => (this.breweries = data))
                .catch(err => console.log(err));
        },
        isBiggerThan10(element, index, array) {
            return element > 10;
        },
        construirElemento(type) {
            return {
                id: type,
                name: this.getName(type),
                children: [],
            };
        },
        obterElemento(type) {
            return {
                id: type,
                name: this.getName(type),
                children: this.getChildren(type),
            };
        },
        getChildren(type) {
            const breweries = [];

            for (const brewery of this.breweries) {
                if (brewery.brewery_type !== type) continue;

                breweries.push({
                    ...brewery,
                    name: this.getName(brewery.name),
                });
            }

            return breweries.sort((a, b) => (a.name > b.name ? 1 : -1));
        },
        getName(name) {
            if (!name) return;
            return `${name.charAt(0).toUpperCase()}${name.slice(1)}`;
        },
    },
};
</script>

<style>
    .v-treeview-node__root {
        height: 100% !important;
    }
</style>
