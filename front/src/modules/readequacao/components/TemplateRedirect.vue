<template>
    <div/>
</template>
<script>
export default {
    name: 'TemplateRedirect',
    props: {
        campo: { type: Object, default: () => {} },
        dadosReadequacao: { type: Object, default: () => {} },
        redirecionar: { type: Boolean, default: () => false },
    },
    data() {
        return {
            tiposReadequacoesRedirect: {
                local_realizacao: '/readequacao/local-realizacao/index/?idPronac=',
                planilha: '/readequacao/readequacoes/planilha-orcamentaria/?idPronac=',
                saldo_aplicacao: '#/readequacao/saldo-aplicacao/',
                plano_distribuicao: '/readequacao/plano-distribuicao/index/?idPronac=',
                remanejamento_50: '/readequacao/remanejamento-menor/index/?idPronac=',
            },
            urlRedirect: '',
        };
    },
    watch: {
        campo() {
            const chave = `key_${this.dadosReadequacao.idTipoReadequacao}`;
            if (typeof this.campo !== 'undefined') {
                if (Object.prototype.hasOwnProperty.call(this.campo, chave)) {
                    if (typeof this.campo[chave].tpCampo !== 'undefined') {
                        this.urlRedirect = this.tiposReadequacoesRedirect[this.campo[chave].tpCampo];
                        if (this.redirecionar) {
                            this.executaRedirecionamento();
                        }
                    }
                }
            }
        },
        redirecionar() {
            if (this.redirecionar) {
                this.executaRedirecionamento();
            }
        },
    },
    methods: {
        executaRedirecionamento() {
            if (this.urlRedirect !== 'undefined') {
                const routePath = this.urlRedirect + String(this.dadosReadequacao.idPronac);
                if (routePath.match(/#/)) {
                    this.$router.push({ path: routePath });
                } else {
                    window.location.href = routePath;
                }
            }
        },
    },
};
</script>
