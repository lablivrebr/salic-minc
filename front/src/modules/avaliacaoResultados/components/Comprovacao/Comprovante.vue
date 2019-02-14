<template>
    <v-container fluid>
        <v-expansion-panel>
            <v-expansion-panel-content
                v-for="(comprovante,i) in comprovantes"
                :key="i"
            >
                <div slot="header">Fornecedor: {{ comprovante.fornecedor.nome }} {{ comprovante.valor | moedaMasck }}</div>
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
import { mapActions } from 'vuex';
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
            getter: `avaliacaoResultados/${this.tipo === 'nacional' ? 'listarComprovantesNacionais' : 'listarComprovantesInternacionais'}`,
        };
    },
    computed: {
        comprovantes() {
            return this.$store.getters[this.getter];
        },
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
