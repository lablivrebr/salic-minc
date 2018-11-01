<template>
    <div id="conteudo">
        <div v-if="loading">
            <Carregando :text="'Carregando Providencia Tomada'"></Carregando>
        </div>
        <div v-else-if="dados">
            <div v-if="dados.providenciaTomada">
                <table class="tabela">
                    <thead>
                    <tr class="destacar">
                        <th class="center">DT. SITUA&Ccedil;&Atilde;O</th>
                        <th class="center">SITUA&Ccedil;&Atilde;O</th>
                        <th class="center">PROVID&Ecirc;NCIA TOMADA</th>
                        <th class="center">CPF</th>
                        <th class="center">NOME</th>
                    </tr>
                    </thead>
                    <tbody v-for="(dado, index) in dados.providenciaTomada" :key="index">
                    <tr>
                        <td class="center">{{ dado.DtSituacao }}</td>
                        <td class="center">{{ dado.Situacao }}</td>
                        <td class="center">{{ dado.ProvidenciaTomada }}</td>
                        <td class="center">{{ dado.cnpjcpf }}</td>
                        <td class="center">{{ dado.usuario }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div v-else>
                <fieldset>
                    <legend>Provid&ecirc;ncia Tomada</legend>
                    <div class="center">
                        <em>Dados n&atilde;o informado.</em>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex';
    import Carregando from '@/components/Carregando';
    import IdentificacaoProjeto from './IdentificacaoProjeto';

    export default {
        name: 'ProvidenciaTomada',
        components: {
            Carregando,
            IdentificacaoProjeto,
        },
        data() {
            return {
                loading: true,
            };
        },
        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarProvidenciaTomada(this.dadosProjeto.idPronac);
            }
        },
        watch: {
            dados() {
                this.loading = false;
            },
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dados: 'projeto/providenciaTomada',
            }),
        },
        methods: {
            ...mapActions({
                buscarProvidenciaTomada: 'projeto/buscarProvidenciaTomada',
            }),
        },
    };
</script>

