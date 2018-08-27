<template>
    <div v-if="cepFormatado && cepFormatado.length > 0" v-html="cepFormatado"></div>
    <div v-else>N&atilde;o informado(a)</div>
</template>

<script>
    export default {
        name: 'SalicFormatarCep',
        props: ['cep'],
        computed: {
            cepFormatado() {
                if (typeof this.cep !== 'undefined') {
                    return this.formatarCep(this.cep);
                }
                return '';
            },
        },
        methods: {
            formatarCep(cep) {
                if (cep.length === 8) {
                    return this.adicionarMascaraCep(cep);
                }

                return '';
            },
            // formato: 99.999.999
            adicionarMascaraCep(v) {
                v = v.replace(/\D/g, '');
                v = v.replace(/(\d{2})(\d)/, '$1.$2');
                v = v.replace(/(\d{3})(\d)/, '$1-$2');
                return v;
            },
        },
    };
</script>
