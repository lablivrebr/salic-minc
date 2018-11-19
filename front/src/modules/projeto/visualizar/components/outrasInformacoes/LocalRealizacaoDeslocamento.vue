<template>
    <div id="conteudo">
        <div v-if="loading">
            <Carregando :text="'Carregando Local de Realizacao e Deslocamento'"></Carregando>
        </div>
        <div v-else-if="Object.keys(dados).length > 0">
            <IdentificacaoProjeto :pronac="dadosProjeto.Pronac"
                                  :nomeProjeto="dadosProjeto.NomeProjeto">
            </IdentificacaoProjeto>
            <fieldset>
                <legend>Local de Realiza&ccedil;&atilde;o</legend>
                <table class="tabela" v-if="Object.keys(dados.localRealizacoes).length > 0">
                    <thead>
                    <tr class="destacar">
                        <th class="center">Pa&iacute;s</th>
                        <th class="center">UF</th>
                        <th class="center">Cidade</th>
                    </tr>
                    </thead>
                    <tbody v-for="(dado, index) in dados.localRealizacoes" :key="index">
                    <tr>
                        <td class="center">{{ dado.Descricao }}</td>
                        <td class="center">{{ dado.UF }}</td>
                        <td class="center">{{ dado.Cidade }}</td>
                    </tr>
                    </tbody>
                </table>
                <div v-else class="center"><em>Dados N&atilde;o Informados.</em></div>
            </fieldset>
            <fieldset>
                <legend>Deslocamento</legend>
                <div v-if="dados.Deslocamento.length > 0">
                    <table class="tabela">
                        <thead>
                        <tr class="destacar">
                            <th class="center">Pa&iacute;s de Origem</th>
                            <th class="center">UF de Origem</th>
                            <th class="center">Cidade de Origem</th>
                            <th class="center">Pa&iacute;s de Destino</th>
                            <th class="center">UF de Destino</th>
                            <th class="center">Cidade de Destino</th>
                            <th class="center">Quantidade</th>
                        </tr>
                        </thead>
                        <tbody v-for="(dado, index) in dados.Deslocamento" :key="index">
                        <tr>
                            <td class="center">{{ dado.PaisOrigem }}</td>
                            <td class="center">{{ dado.UFOrigem }}</td>
                            <td class="center">{{ dado.MunicipioOrigem }}</td>
                            <td class="center">{{ dado.PaisDestino }}</td>
                            <td class="center">{{ dado.UFDestino }}</td>
                            <td class="center">{{ dado.MunicipioDestino }}</td>
                            <td class="center">{{ dado.Qtde }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="center"><em>Dados N&atilde;o Informados.</em></div>
            </fieldset>
        </div>
    </div>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex';
    import Carregando from '@/components/Carregando';
    import IdentificacaoProjeto from './IdentificacaoProjeto';

    export default {
        name: 'LocalRealizacaoDeslocamento',
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
                this.buscarLocalRealizacaoDeslocamento(this.dadosProjeto.idPronac);
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
                dados: 'projeto/localRealizacaoDeslocamento',
            }),
        },
        methods: {
            ...mapActions({
                buscarLocalRealizacaoDeslocamento: 'projeto/buscarLocalRealizacaoDeslocamento',
            }),
        },
    };
</script>

