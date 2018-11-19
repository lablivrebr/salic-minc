<template>
    <div v-if="dados">
        <table class="tabela" v-if="Object.keys(dados).length > 0">
            <thead>
            <tr class="destacar">
                <th align="center"><b>TIPO</b></th>
                <th align="center"><b>DATA</b></th>
                <th align="center"><b>Dt. Anexa&ccedil;&atilde;o</b></th>
                <th align="center"><b>Documento</b></th>
                <th align="center"><b>Anexado por</b></th>
                <th align="center"><b>Lote</b></th>
                <th align="center"><b>Estado</b></th>
            </tr>
            </thead>
            <tbody v-for="(dado, index) in dados" :key="index">
            <tr>
                <td align="center">{{ dado.dsTipoDocumento }}</td>
                <td align="center">{{ dado.dtDocumento }}</td>
                <td align="center">{{ dado.dtAnexacao }}</td>
                <td align="center">
                    <a :href="`/consultardadosprojeto/abrir-documento-tramitacao?id=${dado.idDocumento}&idPronac=${idPronac}`">
                        {{ dado.noArquivo }}
                    </a>
                </td>
                <td align="center">{{ dado.Usuario }}</td>
                <td align="center">{{ dado.idLote}}</td>
                <td align="center">{{ dado.Situacao}}</td>
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
        name: 'TramitacaoDocumento',
        props: ['idPronac'],
        mounted() {
            if (typeof this.idPronac !== 'undefined') {
                this.buscarTramitacaoDocumento(this.idPronac);
            }
        },
        computed: {
            ...mapGetters({
                dados: 'projeto/tramitacaoDocumento',
            }),
        },
        methods: {
            ...mapActions({
                buscarTramitacaoDocumento: 'projeto/buscarTramitacaoDocumento',
            }),
        },
    };
</script>

