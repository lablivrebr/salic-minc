<template>
    <div
        v-if="arrayPlanilha"
        class="planilha-orcamentaria">
        {{ planilhaMontada }}
        <s-collapsible-recursivo
            :planilha="planilhaMontada"
            :headers="headers"
            :expand-all="expandAll"
        >
            <template
                slot="badge"
                slot-scope="slotProps">
                <slot
                    :planilha="slotProps.planilha"
                    name="badge">
                    <VChip
                        v-if="slotProps.planilha.total"
                        outline="outline"
                        label="label"
                        color="#565555">
                        R$ {{ formatarParaReal(slotProps.planilha.total) }}
                    </VChip>
                </slot>
            </template>
            <template slot-scope="slotProps">
                <slot :itens="slotProps.itens">
                    <s-planilha-itens-padrao :table="slotProps.itens"/>
                </slot>
            </template>
        </s-collapsible-recursivo>
        <div
            v-if="arrayPlanilha.total"
            class="text-xs-right pa-3">
            <span><b>Valor total do projeto:</b>
                R$ {{ arrayPlanilha.total | filtroFormatarParaReal }}
            </span>
        </div>
    </div>
    <div v-else>Nenhuma planilha encontrada</div>
</template>

<script>
import SPlanilhaItensPadrao from '@/components/Planilha/PlanilhaItensPadrao';
import SPlanilhaConsolidacao from '@/components/Planilha/PlanilhaConsolidacao';
import MxPlanilhas from '@/mixins/planilhas';
import _ from 'lodash';

const SCollapsibleRecursivo = {
    name: 'SCollapsibleRecursivo',
    props: {
        planilha: {},
        contador: {
            default: 1,
            type: Number,
        },
        headers: {
            type: Array,
            required: true,
        },
        expandAll: {
            type: Boolean,
            required: true,
        },
    },
    mixins: [MxPlanilhas],
    render(h) {
        const self = this;
        if (this.isObject(self.planilha) && typeof self.planilha.itens === 'undefined') {
            return h('VExpansionPanel',
                { props: { value: self.toggleExpand(this.planilha, self.contador) }, attrs: { expand: 'expand' } },
                Object.keys(this.planilha).map((key) => {
                    if (self.isObject(self.planilha[key])) {
                        const badgeHeader = (self.$scopedSlots.badge)
                            ? self.$scopedSlots.badge({ planilha: self.planilha[key] }) : '';
                        return h('VExpansionPanelContent',
                            [
                                h('VLayout',
                                    {
                                        props: {
                                            row: true,
                                            'justify-space-between': true,
                                        },
                                        slot: 'header',
                                        style: { color: self.getHeader(self.contador).color },
                                    },
                                    [
                                        h('i',
                                            { class: `material-icons mt-2 pl-${self.contador * 1}` },
                                            [self.getHeader(self.contador).icon]),
                                        h('span', { class: 'ml-2 mt-2' }, key),
                                        h('VSpacer'),
                                        badgeHeader,
                                    ]),
                                h('div',
                                    [
                                        h(SCollapsibleRecursivo, {
                                            props: {
                                                planilha: self.planilha[key],
                                                contador: self.contador + 1,
                                                headers: self.headers,
                                                expandAll: self.expandAll,
                                            },
                                            scopedSlots: {
                                                badge: self.$scopedSlots.badge,
                                                default: self.$scopedSlots.default,
                                            },
                                        }),
                                        h(SPlanilhaConsolidacao, {
                                            props: {
                                                planilha: self.planilha[key],
                                            },
                                        }),
                                    ]),
                            ]);
                    }
                    return true;
                }));
        } if (self.$scopedSlots.default !== 'undefined') {
            return h('div', { class: 'scroll-x pa-2 elevation-1', style: { border: '1px solid #ddd' } }, [
                self.$scopedSlots.default({ itens: self.planilha.itens }),
            ]);
        }
        return true;
    },
    methods: {
        getHeader(id) {
            return this.headers.find(element => element.id === id);
        },
        toggleExpand(table, contador) {
            const lastItem = this.headers.slice(-1)[0];
            if (this.expandAll !== true && lastItem.id === contador) {
                return [];
            }

            return [...Object.keys(table)].map(() => true);
        },
    },
};

export default {
    name: 'Planilha',
    components: {
        SCollapsibleRecursivo,
        SPlanilhaItensPadrao,
    },
    mixins: [MxPlanilhas],
    props: {
        arrayPlanilha: {
            type: Array,
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
        expandAll: {
            type: Boolean,
            default: true,
        },
    },
    computed: {
        planilhaMontada() {
            const planilha = _.cloneDeep(this.arrayPlanilha);
            const groupBy = function (xs, chaves) {
                return xs.reduce((rv, x) => {
                    let i = 0;
                    function recursivo(p, x, keys) {
                        const key = keys[i];
                        i += 1;
                        (p[x[key]] = p[x[key]] || Object.assign({}, p[x[key]]) || {});
                        if (keys[keys.length - 1] !== key) {
                            recursivo(p[x[key]], x, keys);
                        } else {
                            (p[x[key]] = p[x[key]].itens || Object.assign({}, { itens: [] }));
                            console.log('p12321', p[x[key]], p);
                        }
                        console.info('plani', p);
                        return p;
                    }
                    return recursivo(rv, x, chaves);
                }, {});
            };
            const pl1 = groupBy(planilha, ['FonteRecurso', 'Produto', 'Etapa', 'UF', 'Cidade']);
            console.info('teste', pl1);
            return pl1;
        },
    },
    watch: {
        planilhaMontada(val) {
            console.log('planilha', val);
        },
    },
};
</script>

<style>
    .planilha-orcamentaria > ul > li > .v-expansion-panel__header {
        border-top: 1px solid #ddd;
    }
    .v-expansion-panel__header {
        padding: 10px !important;
        border-bottom: 1px solid #ddd;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }
</style>
