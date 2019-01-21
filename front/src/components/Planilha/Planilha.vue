<template>
    <div
        v-if="arrayPlanilha"
        class="planilha-orcamentaria card">
        <s-collapsible-recursivo :planilha="arrayPlanilha">
            <template slot-scope="slotProps">
                <slot :itens="slotProps.itens">
                    <s-planilha-itens-padrao :table="slotProps.itens"/>
                </slot>
            </template>
        </s-collapsible-recursivo>
        <div class="card-action right-align">
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
                { props: { expand: true, focusable: true, value: [1, 1, 1] }, attrs: { expand: 'expand' }, class: 'pl-2 elevation-0"' },
                Object.keys(this.planilha).map((key) => {
                    if (self.isObject(self.planilha[key])) {
                        return h('VExpansionPanelContent', [
                            h('VLayout',
                                {
                                    props: {
                                        row: true,
                                        'justify-space-between': true,
                                    },
                                    class: 'collapsible-headers activss',
                                    slot: 'header',
                                },
                                [
                                    h('i', { class: 'material-icons' }, [self.obterIconeHeader(self.contador)]),
                                    h('span', key),
                                    h('VSpacer'),
                                    h('VChip', { class: 'badgessss' }, [`R$ ${self.formatarParaReal(self.planilha[key].total)} `]),
                                ]),
                            h('div',
                                { class: 'collapsible-body no-padding' },
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
        arrayPlanilha: {},
    },
    watch: {
        arrayPlanilha() {
            this.$nextTick(() => {
                this.iniciarCollapsible();
            });
        },
    },
    mounted() {
        this.$nextTick(() => {
            this.iniciarCollapsible();
        });
    },
    methods: {
        iniciarCollapsible() {
            // eslint-disable-next-line
            //     $3(".collapsible").each(function () {
            //     // eslint-disable-next-line
            //         $3(this).collapsible();
            // });
        },
    },
};
</script>
