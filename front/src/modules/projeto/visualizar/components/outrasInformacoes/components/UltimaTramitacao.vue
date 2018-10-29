<template>
    <div v-if="dados">
        <table class="tabela" v-if="Object.keys(dados).length > 0">
            <tbody v-for="(dado, index) in dados" :key="index">
            <tr class="destacar">
                <td align="center"><b>Emissor</b></td>
                <td align="center"><b>Dt.Envio</b></td>
                <td align="center"><b>Receptor</b></td>
                <td align="center"><b>Dt.Recebimento</b></td>
                <td align="center"><b>Estado</b></td>
                <td align="center"><b>Destino</b></td>
            </tr>
            <tr>
                <td align="center">{{ dado.Emissor }}</td>
                <td align="center">{{ dado.dtTramitacaoEnvio }}</td>
                <td align="center">{{ dado.Receptor }}</td>
                <td align="center">{{ dado.dtTramitacaoRecebida }}</td>
                <td align="center">{{ dado.Estado }}</td>
                <td align="center">{{ dado.Destino}}</td>
            </tr>
            <tr class="destacar">
                <td colspan="6" align="center">
                    <b>Despacho</b>
                </td>
            </tr>
            <tr>
                <td colspan="6" align="left">{{ dado.meDespacho }}</td>
            </tr>
            </tbody>
        </table>
        <div v-else class="center">
            <em>Dados n&atilde;o informado.</em>
        </div>
    </div>
</template>

<script>
    import  { mapActions, mapGetters } from 'vuex';

    export default {
        name: 'UltimaTramitacao',
        props: ['idPronac'],
        mounted() {
            if (typeof this.idPronac !== 'undefined') {
                this.buscarUltimaTramitacao(this.idPronac);
            }
        },
        computed: {
            ...mapGetters({
                dados: 'projeto/ultimaTramitacao',
            }),
        },
        methods: {
            ...mapActions({
                buscarUltimaTramitacao: 'projeto/buscarUltimaTramitacao',
            }),
        },
    };
</script>

