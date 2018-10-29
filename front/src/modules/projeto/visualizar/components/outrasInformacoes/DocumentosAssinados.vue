<template>
    <div id="conteudo">
        <div v-if="loading">
            <Carregando :text="'Carregando Documentos Assinados'"></Carregando>
        </div>
        <table v-else-if="Object.keys(dados).length > 0">
            <thead>
            <tr class="destacar">
                <th class="center">PRONAC</th>
                <th class="center">NOME DO PROJETO</th>
                <th class="center">ATO ADMINISTRATIVO</th>
                <th class="center">DATA</th>
                <th class="center">VER</th>
            </tr>
            </thead>
            <tbody v-for="(dado, index) in dados" :key="index">
            <tr>
                <td class="center">
                    <router-link :to="{ name: 'dadosprojeto', params: { idPronac: dado.IdPRONAC }}"
                                 class='waves-effect waves-dark btn white black-text'>
                        <u>{{ dado.pronac }}</u>
                    </router-link>
                </td>
                <td class="center">{{ dado.nomeProjeto }}</td>
                <td class="center">{{ dado.dsAtoAdministrativo }}</td>
                <td class="center">{{ dado.dt_criacao }}</td>
                <td class="center">
                    <a class="btn waves-effect waves-light tooltipped small white-text"
                       :href="`/assinatura/index/visualizar-documento-assinado/idPronac/${dado.IdPRONAC}?idDocumentoAssinatura=${dado.idDocumentoAssinatura}`"
                       target="_blank"
                       data-position="top" data-delay="50" data-tooltip="Visualizar">
                        <i class="material-icons">search</i>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
        <div v-else>
            <fieldset>
                <legend>Documentos assinados</legend>
                <div class="center">
                    <em>Sem documentos assinados para este projeto.</em>
                </div>
            </fieldset>
        </div>
    </div>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex';
    import Carregando from '@/components/Carregando';

    export default {
        name: 'DocumentosAssinados',
        props: ['idPronac'],
        data() {
            return {
                loading: true,
            };
        },
        components: {
            Carregando,
        },
        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarDocumentosAssinados(this.dadosProjeto.idPronac);
            }
        },
        watch: {
            dados() {
                this.loading = false;
            }
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dados: 'projeto/documentosAssinados',
            }),
        },
        methods: {
            ...mapActions({
                buscarDocumentosAssinados: 'projeto/buscarDocumentosAssinados',
            }),
        },
    };
</script>
