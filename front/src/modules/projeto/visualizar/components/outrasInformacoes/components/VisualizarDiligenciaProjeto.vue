<template>
    <div>
        <table class="tabela" v-if="Object.keys(diligencias).length > 0">
            <thead>
            <tr class="destacar">
                <th>VISUALIZAR</th>
                <th>PRODUTO</th>
                <th>TIPO DE DILIG&Ecirc;NCIA</th>
                <th>DATA DA SOLICITA&Ccedil;&Atilde;O</th>
                <th>DATA DA RESPOSTA</th>
                <th>PRAZO DA RESPOSTA</th>
                <th>PRORROGADO</th>
            </tr>
            </thead>
            <tbody v-for="(diligencia, index) in diligencias" :key="index">
            <tr>
                <td class="center">
                    <button
                            class="waves-effect waves-darken btn white black-text"
                            @click="setAbaAtiva(diligencia, index);"
                    >
                        <i class="material-icons">visibility</i>
                    </button>
                </td>
                <td v-if="diligencia.produto">
                    {{ diligencia.produto }}
                </td>
                <td v-else class="center"> -</td>
                <td>{{ diligencia.tipoDiligencia }}</td>
                <td>{{ diligencia.dataSolicitacao }}</td>
                <td>{{ diligencia.dataResposta }}</td>
                <td>{{ diligencia.prazoResposta }}</td>
                <td>Prorrogado</td>
            </tr>
            <tr v-if="abaAtiva === index && ativo && Object.keys(dadosDiligencia).length > 2">
                <td colspan="7">
                    <table class="tabela">
                        <tbody>
                        <tr>
                            <th>PRONAC</th>
                            <th>NOME DA PROPOSTA</th>
                        </tr>
                        <tr>
                            <td>{{ dadosProjeto.Pronac }}</td>
                            <td>{{ dadosProjeto.NomeProjeto }}</td>
                        </tr>
                        <tr>
                            <th>DATA DA SOLICITA&Ccedil;&Atilde;O</th>
                            <th>DATA DA RESPOSTA</th>
                        </tr>
                        <tr>
                            <td>{{ dadosDiligencia.dataSolicitacao }}</td>
                            <td>{{ dadosDiligencia.dataResposta }}</td>
                        </tr>
                        </tbody>
                    </table>
                    <table v-if="dadosDiligencia.Solicitacao" class="tabela">
                        <tbody>
                        <tr>
                            <th>SOLICITA&Ccedil;&Atilde;O</th>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px" v-html="dadosDiligencia.Solicitacao"></td>
                        </tr>
                        </tbody>
                    </table>
                    <table v-if="dadosDiligencia.Resposta" class="tabela">
                        <tbody>
                        <tr>
                            <th>Resposta:</th>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px" v-html="dadosDiligencia.Resposta"></td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="tabela"
                           v-if="dadosDiligencia.arquivos && Object.keys(dadosDiligencia.arquivos).length > 0">
                        <tbody>
                        <tr>
                            <th colspan="3">Arquivos Anexados</th>
                        </tr>
                        <tr>
                            <td class="destacar bold" align="center">Arquivo</td>
                            <td class="destacar bold" align="center">Dt.Envio</td>
                        </tr>
                        <tr v-for="arquivo of dadosDiligencia.arquivos" :key="arquivo.idArquivo">
                            <td>
                                <a :href="`/upload/abrir?id=${arquivo.idArquivo}`" target="_blank">
                                    {{ arquivo.nmArquivo }}
                                </a>
                            </td>
                            <td align="center">
                                {{ arquivo.dtEnvio }}
                            </td>
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
    import { mapGetters, mapActions } from 'vuex';

    export default {
        name: 'VisualizarDiligenciaProjeto',
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
                dadosDiligencia: 'projeto/diligenciaProjeto',
            }),
        },
        methods: {
            setAbaAtiva(value, index) {
                if (this.abaAtiva === index) {
                    this.ativo = !this.ativo;
                } else {
                    this.abaAtiva = index;
                    this.ativo = true;

                    const valor = value.idDiligencia;
                    const idPronac = this.dadosProjeto.idPronac;

                    this.buscarDiligenciaProjeto({ idPronac, valor });
                }
            },
            ...mapActions({
                buscarDiligenciaProjeto: 'projeto/buscarDiligenciaProjeto',
            }),
        },
    };
</script>

