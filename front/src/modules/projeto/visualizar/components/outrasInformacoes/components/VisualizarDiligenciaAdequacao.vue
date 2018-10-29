<template>
    <div>
        <table class="tabela" v-if="Object.keys(diligencias).length > 0">
            <thead>
            <tr class="destacar">
                <th>VISUALIZAR</th>
                <th>DATA DA AVALIA&Ccedil;&Atilde;O</th>
                <th>TIPO DE DILIG&Ecirc;NCIA</th>
            </tr>
            </thead>
            <tbody v-for="(diligencia, index) in diligencias" :key="index">
            <tr>
                <td class="center">
                    <button
                            class="waves-effect waves-darken btn white black-text"
                            @click="setAbaAtiva(diligencia, index)"
                    >
                        <i class="material-icons">visibility</i>
                    </button>
                </td>
                <td>{{ diligencia.dtAvaliacao }}</td>
                <td>{{ diligencia.tipoDiligencia }}</td>
            </tr>
            <tr v-if="abaAtiva === index && ativo && Object.keys(dadosDiligencia).length > 2">
                <td colspan="3">
                    <table v-if="dadosDiligencia.dsAvaliacao" class="tabela">
                        <tbody>
                        <tr>
                            <th>SOLICITA&Ccedil;&Atilde;O</th>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px" v-html="dadosDiligencia.dsAvaliacao"></td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
        <div v-else class="center">
            <em>Dados n&atilde;o informado.</em>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';

    export default {
        name: 'VisualizarDiligenciaAdequacao',
        props: ['idPronac', 'diligencias'],
        data() {
            return {
                abaAtiva: -1,
                ativo: false,
            };
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dadosDiligencia: 'projeto/diligenciaAdequacao',
            }),
        },
        methods: {
            setAbaAtiva(value, index) {
                if (this.abaAtiva === index) {
                    this.ativo = !this.ativo;
                } else {
                    this.abaAtiva = index;
                    this.ativo = true;

                    const valor = value.idAvaliarAdequacaoProjeto;
                    const idPronac = this.dadosProjeto.idPronac;

                    this.buscarDiligenciaAdequacao({ idPronac, valor });
                }
            },
            ...mapActions({
                buscarDiligenciaAdequacao: 'projeto/buscarDiligenciaAdequacao',
            }),
        },
    };
</script>

