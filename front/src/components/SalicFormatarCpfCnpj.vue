<template>
    <span
        v-if="cpfCnpjFormatado && cpfCnpjFormatado.length > 0"
        v-html="cpfCnpjFormatado"/>
    <span v-else>N&atilde;o informado(a)</span>
</template>

<script>
export default {
    name: 'SalicFormatarCpfCnpj',
    props: ['cpf'],
    computed: {
        cpfCnpjFormatado() {
            if (typeof this.cpf !== 'undefined') {
                return this.formatarCpfOuCnpj(this.cpf);
            }

            return '';
        },
    },
    methods: {
        formatarCpfOuCnpj(cpfOrCnpj) {
            if (cpfOrCnpj.length === 11) {
                return this.formatarCpf(cpfOrCnpj);
            }
            return this.formatarCnpj(cpfOrCnpj);
        },
        // formato: 999.999.999-99
        formatarCpf(v) {
            v = v.replace(/\D/g, '');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1-$2');
            return v;
        },
        // formato: 99.999.999/9999-99
        formatarCnpj(v) {
            v = v.replace(/\D/g, '');
            v = v.replace(/(\d{2})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1/$2');
            v = v.replace(/(\d{4})(\d)/, '$1-$2');
            return v;
        },
    },
};
</script>
