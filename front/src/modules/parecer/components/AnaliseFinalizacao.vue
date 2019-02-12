<template>
    <s-carregando
        v-if="loading"
        text="Carregando consolidacao"
    />
    <div v-else>
        <v-form
            ref="form"
            v-model="valid"
            lazy-validation
        >
            <v-layout wrap>
                <div
                    v-if="editorParecerRules.show"
                    class="text-xs-left"
                >
                    <p
                        :class="editorParecerRules.color"
                    >
                        {{ editorParecerRules.msg }}
                    </p>
                </div>
                <v-flex
                    xs12
                    sm12
                    md12
                >
                    Verificando se o parecer do produto está disponível para finalização.
                </v-flex>
                <v-flex
                    xs12
                    sm12
                    md12
                    class="mb-2"
                >
                </v-flex>
                <v-flex
                    xs12
                    sm12
                    md12
                >
                    Verificando se o parecer do produto está disponível para finalização.
                </v-flex>
            </v-layout>
            <v-subheader class="pa-0">
                Todos os campos s&atilde;o obrigat&oacute;rios
            </v-subheader>

            <v-layout
                row
                justify-center
            >
                <v-btn
                    :loading="loadingButton"
                    @click="submit"
                >
                    <v-icon>save</v-icon>
                    Salvar
                </v-btn>
            </v-layout>
        </v-form>
    </div>
</template>

<script>
import SEditorTexto from '@/components/SalicEditorTexto';
import SCarregando from '@/components/CarregandoVuetify';

import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'AnaliseFinalizacao',
    components: { SEditorTexto, SCarregando },
    props: {
        active: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            loadingButton: false,
            loading: true,
            valid: false,
            editorParecerRules: {
                show: false,
                color: '',
                backgroundColor: '',
                msg: '',
                enable: false,
            },
            consolidacaoEmEdicao: {
                IdPRONAC: this.$route.params.idPronac,
                idProduto: this.$route.params.id,
                ParecerFavoravel: '2',
                ResumoParecer: '',
            },
            rules: {
                parecer: v => (!!v || this.$refs.stItemAvaliado.value !== '3') || 'Parecer é obrigatório',
            },
        };
    },
    computed: {
        ...mapGetters({
            produto: 'parecer/getProduto',
            consolidacao: 'parecer/getConsolidacao',
        }),
    },
    watch: {
        consolidacao(val) {
            if (Object.keys(val).length > 0) {
                this.consolidacaoEmEdicao = Object.assign({}, val);
            }
            this.loading = false;
        },
    },
    created() {
        this.obterConsolidacao({
            id: this.$route.params.id,
            idPronac: this.$route.params.idPronac,
        });
    },
    methods: {
        ...mapActions({
            obterConsolidacao: 'parecer/obterConsolidacao',
            // salvarAnaliseConsolidacao: 'parecer/salvarAnaliseConsolidacao',
        }),
        labelSimOuNao(val) {
            return val === '2' ? 'Sim' : 'Não';
        },
        submit() {
            // if (!this.$refs.form.validate()) {
            //     return false;
            // }

            // const analise = Object.assign({}, this.consolidacaoEmEdicao);

            // this.loadingButton = true;
            // this.salvarAnaliseConsolidacao(analise).then(() => {
            //     this.loadingButton = false;
            // }).catch(() => {
            //     this.loadingButton = false;
            // });

            return true;
        },
    },
};
</script>
