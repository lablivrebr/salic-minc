<template>
    <div id="conteudo">
        <div v-if="loading">
            <Carregando :text="'Carregando Documentos Anexados'"></Carregando>
        </div>
        <div v-else-if="documentosAnexados">
            <IdentificacaoProjeto
                    :pronac="dadosProjeto.Pronac"
                    :nomeProjeto="dadosProjeto.NomeProjeto">
            </IdentificacaoProjeto>
            <table v-if="Object.keys(documentosAnexados.documentos).length > 0">
                <thead>
                <tr class="destacar">
                    <th class="center">N&ordm;</th>
                    <th class="center">CLASSIFICA&ccedil;&atilde;O</th>
                    <th class="center">DATA</th>
                    <th class="center">TIPO DE DOCUMENTO</th>
                    <th class="center">DOCUMENTO</th>
                </tr>
                </thead>
                <tbody v-for="(dado, index) in documentosAnexados.documentos" :key="index">
                <tr>
                    <td class="center">{{ index + 1 }}</td>
                    <td class="center">{{ dado.Anexado }}</td>
                    <td class="center">{{ dado.Data }}</td>
                    <td class="center">{{ dado.Descricao }}</td>
                    <td class="center">
                        <a class=""
                        :href="`/consultardadosprojeto/abrir-documentos-anexados?id=${dado.idArquivo}&tipo=${dado.AgenteDoc}&idPronac=${dadosProjeto.idPronac}`"
                        target="_blank"
                        data-position="top" data-delay="50" data-tooltip="Visualizar">
                            {{ dado.NoArquivo }}
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
            <div v-else>
                <fieldset>
                    <legend>Certid&otilde;es Negativas</legend>
                    <div class="center">
                        <em>Dados n&atilde;o informado.</em>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import Carregando from '@/components/Carregando';
    import IdentificacaoProjeto from './IdentificacaoProjeto';

    export default {
        name: 'DocumentosAnexados',
        props: ['idPronac'],
        data() {
            return {
                loading: true,
            };
        },
        components: {
            Carregando,
            IdentificacaoProjeto,
        },
        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarDocumentosAnexados(this.dadosProjeto.idPronac);
            }
        },
        watch: {
            dados(value) {
                this.informacoes = value.informacoes;
            },
            documentosAnexados() {
                this.loading = false;
            },
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                documentosAnexados: 'projeto/documentosAnexados',
            }),
        },
        methods: {
            ...mapActions({
                buscarDocumentosAnexados: 'projeto/buscarDocumentosAnexados',
            }),
        },
    };
</script>
