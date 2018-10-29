<template>
    <div id="conteudo">
        <div v-if="loading">
            <Carregando :text="'Carregando Certidoes Negativas'"></Carregando>
        </div>
        <div v-else-if="dados">
            <IdentificacaoProjeto :pronac="dadosProjeto.Pronac"
                                  :nomeProjeto="dadosProjeto.NomeProjeto">
            </IdentificacaoProjeto>
            <div v-if="Object.keys(dados.certidoes).length > 0">
                <table class="tabela">
                    <thead>
                    <tr class="destacar">
                        <th class="center">CERTID&otilde;es</th>
                        <th class="center">DATA DE EMISS&Atilde;O</th>
                        <th class="center">DATA DE VALIDADE</th>
                        <th class="center">PRONAC</th>
                        <th class="center">SITUA&Ccedil;&Atilde;O</th>
                    </tr>
                    </thead>
                    <tbody v-for="(dado, index) in dados.certidoes" :key="index">
                    <tr>
                        <td class="center">{{ dado.dsCertidao }}</td>
                        <td class="center">{{ dado.DtEmissao }}</td>
                        <td class="center">{{ dado.DtValidade }}</td>
                        <td class="center">{{ dado.Pronac }}</td>
                        <td class="center" v-if="dado.Situacao">
                            {{ dado.Situacao }}
                        </td>
                        <td class="center" v-else>
                            Vencida
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div v-else>
                <fieldset>
                    <legend>Certid&otilde;es Negativas</legend>
                    <div class="center">
                        <em>Dados n&atilde;o  informado.</em>
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
        name: 'CertidoesNegativas',
        data() {
            return {
                loading: true,
            };
        },
        components: {
            IdentificacaoProjeto,
            Carregando,
        },
        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarCertidoesNegativas(this.dadosProjeto.idPronac);
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
                dados: 'projeto/certidoesNegativas',
            }),
        },
        methods: {
            ...mapActions({
                buscarCertidoesNegativas: 'projeto/buscarCertidoesNegativas',
            }),
        },
    };
</script>

