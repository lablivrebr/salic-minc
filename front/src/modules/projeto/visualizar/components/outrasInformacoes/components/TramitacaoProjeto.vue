<template>
    <div v-if="dados">
        <table class="tabela" v-if="Object.keys(dados).length > 0">
            <thead>
            <tr class="destacar">
                <th align="center">
                    <b>Origem</b>
                </th>
                <th align="center">
                    <b>Dt.Envio</b>
                </th>
                <th align="center">
                    <b>Destino</b>
                </th>
                <th align="center">
                    <b>Dt.Recebimento</b>
                </th>
                <th align="center">
                    <b>Estado</b>
                </th>
                <th align="center">
                    <b>Despacho</b>
                </th>
            </tr>
            </thead>
            <tbody v-for="(dado, index) in dados" :key="index">

            <tr>
                <td align="center">{{ dado.Origem }}</td>
                <td align="center">{{ dado.dtTramitacaoEnvio }}</td>
                <td align="center">{{ dado.Destino }}</td>
                <td align="center">{{ dado.dtTramitacaoRecebida }}</td>
                <td align="center">{{ dado.Situacao }}</td>
                <td align="center">{{ dado.meDespacho}}</td>
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
        name: 'TramitacaoProjeto',
        props: ['idPronac'],
        mounted() {
            if (typeof this.idPronac !== 'undefined') {
                this.buscarTramitacaoProjeto(this.idPronac);
            }
        },
        computed: {
            ...mapGetters({
                dados: 'projeto/tramitacaoProjeto',
            }),
        },
        methods: {
            ...mapActions({
                buscarTramitacaoProjeto: 'projeto/buscarTramitacaoProjeto',
            }),
        },
    };
</script>

