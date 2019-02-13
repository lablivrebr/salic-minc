<template>
    <v-container fluid>
        <v-expansion-panel>
            <v-expansion-panel-content
                v-for="(item,i) in 5"
                :key="i"
            >
                <div slot="header">Item</div>
                <v-card>
                    <v-card-text>
                        {{ idPronac }}
                    </v-card-text>
                </v-card>
            </v-expansion-panel-content>
        </v-expansion-panel>
    </v-container>
</template>

<script>
import Vue from 'vue';
import { mapActions, mapGetters } from 'vuex';
import Moeda from '../../../../filters/money';

Vue.filter('moedaMasck', Moeda);

export default {
    name: 'Comprovante',
    filters: {
        dataMasck(data) {
            const dataFormatada = data.replace(/-/g, '/');
            const date = new Date(dataFormatada);
            return date.toLocaleString(['pt-BR'], {
                month: '2-digit',
                day: '2-digit',
                year: '2-digit',
            });
        },
    },
    props: {
        idPronac: { type: String, default: '' },
        idPlanilhaItens: { type: String, default: '' },
        produto: { type: String, default: '' },
        uf: { type: String, default: '' },
        idUf: { type: String, default: '' },
        cidade: { type: String, default: '' },
        etapa: { type: String, default: '' },
        tipo: { type: String, default: '' },
    },
    data() {
        return {
            comprovanteParams: {
                idPronac: this.idPronac,
                idPlanilhaItens: this.idPlanilhaItens,
                codigoProduto: this.produto,
                uf: this.uf,
                idUf: this.idUf,
                codigoCidade: this.cidade,
                codigoEtapa: this.etapa,
                tipo: this.tipo,
            },
        };
    },
    computed: {
        ...mapGetters({
            comprovantes: 'avaliacaoResultados/listarComprovantes',
        }),
    },
    mounted() {
        this.listarComprovantes(this.comprovanteParams);
    },
    methods: {
        ...mapActions({
            listarComprovantes: 'avaliacaoResultados/listarComprovantes',
        }),
    },
};
</script>
