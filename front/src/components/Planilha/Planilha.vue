<template>
    <div
        v-if="arrayPlanilha"
        class="planilha-orcamentaria">
        <s-collapsible-recursivo :planilha="arrayPlanilha">
            <template slot-scope="slotProps">
                <slot :itens="slotProps.itens">
                    <s-planilha-itens-padrao :table="slotProps.itens"/>
                </slot>
            </template>
        </s-collapsible-recursivo>
        <div class="right-align">
            <span><b>Valor total do projeto:</b> R$ {{ arrayPlanilha.total | filtroFormatarParaReal }}</span>
        </div>
    </div>
    <div v-else>Nenhuma planilha encontrada</div>
</template>

<script>
import SPlanilhaItensPadrao from '@/components/Planilha/PlanilhaItensPadrao';
import SPlanilhaConsolidacao from '@/components/Planilha/PlanilhaConsolidacao';
import MixinsPlanilhas from '@/mixins/planilhas';

const SCollapsibleRecursivo = {
    name: 'SCollapsibleRecursivo',
    props: {
        planilha: {},
        contador: {
            default: 1,
            type: Number,
        },
    },
    mixins: [MixinsPlanilhas],
    render(h) {
        const self = this;
        if (this.isObject(self.planilha) && typeof self.planilha.itens === 'undefined') {
            return h('VExpansionPanel',
                { props: { expand: true, value: [1, 1, 1] }, attrs: { expand: 'expand' }, class: '' },
                Object.keys(this.planilha).map((key) => {
                    if (self.isObject(self.planilha[key])) {
                        return h('VExpansionPanelContent',
                            [
                                h('VLayout',
                                    {
                                        props: {
                                            row: true,
                                            'justify-space-between': true,
                                        },
                                        slot: 'header',
                                        style: { color: self.obterCorHeader(self.contador) },
                                    },
                                    [
                                        h('i', { class: `material-icons mt-2 pl-${self.contador * 1 + 1}` }, [self.obterIconeHeader(self.contador)]),
                                        h('span', { class: 'ml-2 mt-2' }, key),
                                        h('VSpacer'),
                                        h('VChip',
                                            {
                                                class: '',
                                                attrs: {
                                                    outline: 'outline',
                                                    label: 'label',
                                                    color: '#565555',
                                                },
                                            },
                                            [`R$ ${self.formatarParaReal(self.planilha[key].total)} `]),
                                    ]),
                                h('div',
                                    { class: '' },
                                    [
                                        h(SCollapsibleRecursivo, {
                                            props: {
                                                planilha: self.planilha[key],
                                                contador: self.contador + 1,
                                            },
                                            scopedSlots: { default: self.$scopedSlots.default },
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
            return h('div', { class: 'margin20 scroll-x' }, [
                self.$scopedSlots.default({ itens: self.planilha.itens }),
            ]);
        }
        return true;
    },
    methods: {
        obterIconeHeader(tipo) {
            let icone = '';
            switch (tipo) {
            case 1:
                icone = 'beenhere';
                break;
            case 2:
                icone = 'perm_media';
                break;
            case 3:
                icone = 'label';
                break;
            case 4:
                icone = 'place';
                break;
            default:
                icone = '';
            }
            return icone;
        },
        obterCorHeader(tipo) {
            let cor = '';
            switch (tipo) {
            case 1:
                cor = '#F44336';
                break;
            case 2:
                cor = '#4CAF50';
                break;
            case 3:
                cor = '#ff9800';
                break;
            case 4:
                cor = '#2196F3';
                break;
            default:
                cor = '';
            }
            return cor;
        },
    },
};

export default {
    name: 'Planilha',
    components: {
        SCollapsibleRecursivo,
        SPlanilhaItensPadrao,
    },
    mixins: [MixinsPlanilhas],
    props: {
        arrayPlanilha: {
            type: Object,
            default: () => {},
        },
    },
};
</script>

<style>
    .v-expansion-panel__header {
        padding: 5px 10px !important;
        border-top: 1px solid #ddd;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }
</style>
