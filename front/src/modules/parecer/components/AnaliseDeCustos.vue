<template>
    <div>
        <Planilha/>
        <div id="planilha-homologada">
            <Planilha
                v-if="Object.keys(planilha).length > 0"
                :array-planilha="planilha">
                <template slot-scope="slotProps">
                    <PlanilhaItensAnaliseTecnica :table="slotProps.itens"/>
                </template>
            </Planilha>
        </div>

        Análise de Custos
    </div>
</template>

<script>

import { mapActions, mapGetters } from 'vuex';
import Planilha from '@/components/Planilha/Planilha';
import PlanilhaItensAnaliseTecnica from './PlanilhaItensAnaliseTecnica';

export default {
    name: 'AnaliseDeCustos',
    components: {
        Planilha,
        PlanilhaItensAnaliseTecnica,
    },
    props: {
        produto: {
            type: Object,
            default: () => {},
        },
        active: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        ...mapGetters({
            planilha: 'projeto/planilhaAdequada',
        }),
    },
    watch: {
        produto(value) {
            if (Object.keys(value).length > 0) {
                this.buscaPlanilhaAdequada(value.IdPRONAC);
            }
        },
    },
    mounted() {
        if (Object.keys(this.produto).length > 0) {
            this.buscaPlanilhaAdequada(this.produto.IdPRONAC);
        }
    },
    methods: {
        ...mapActions({
            buscaPlanilhaAdequada: 'projeto/buscaPlanilhaAdequada',
        }),
    },
};
</script>
