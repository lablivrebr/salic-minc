<template>
    <v-dialog
        v-model="dialog"
        scrollable
    >
        <v-btn
            slot="activator"
            color="teal"
            dark
            fixed
            bottom
            right
            fab
        >
            <v-icon>add</v-icon>
        </v-btn>
        <v-card>
            <v-toolbar
                dark
                card
                color="green darken-3">
                <v-btn
                    icon
                    dark
                    @click="dialog = false"
                >
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>Criar Comprovante</v-toolbar-title>
                <v-spacer/>
            </v-toolbar>
            <v-card-text>
                <v-form>
                    <v-container>
                        <h3 class="mb-2">IDENTIFICAÇÃO DO CONTRATADO</h3>
                        <v-layout
                            row
                            wrap
                        >
                            <v-flex
                                xs12
                            >
                                <v-radio-group
                                    v-model="cpfCnpjLabel"
                                    label="TIPO DO FORNECEDOR"
                                >
                                    <v-radio
                                        label="CPF"
                                        value="CPF"
                                        color="teal"

                                    />
                                    <v-radio
                                        label="CNPJ"
                                        value="CNPJ"
                                        color="teal"

                                    />
                                </v-radio-group>
                            </v-flex>
                            <v-flex
                                sm12
                                md5
                            >
                                <v-text-field
                                    :label="cpfCnpjLabel"
                                    v-model="cpfCnpj"
                                    placeholder="239.456.123-85"
                                    append-outer-icon="search"
                                    @click:append-outer="buscarAgente(cpfCnpjParams)"
                                />
                            </v-flex>
                            <v-flex
                                sm12
                                md6
                                offset-md1
                            >
                                <v-text-field
                                    :label="nomeRazaoSocialLabel"
                                    :value="nomeRazaoSocial"
                                    readonly
                                />
                            </v-flex>
                        </v-layout>

                        <h3 class="my-2">DADOS DO COMPROVANTE DE DESPESA</h3>
                        <v-layout
                            row
                            wrap
                        >
                            <v-flex
                                sm12
                                md6
                            >
                                <v-select
                                    :items="tipoComprovante"
                                    label="TIPO COMPROVANTE"
                                />
                            </v-flex>

                            <v-flex
                                sm12
                                md6
                            >
                                <v-text-field
                                    :hint="`*Início em: ${dataInicio} até ${dataFim}`"
                                    persistent-hint
                                    label="DATA EMISSÃO DO COMPROVANTE DE DESPESA"
                                    placeholder="DD/MM/AAAA"
                                />
                            </v-flex>
                            <v-flex
                                sm12
                                md6
                            >
                                <v-text-field
                                    label="NÚMERO"
                                    placeholder="00000000"
                                />
                            </v-flex>
                            <v-flex
                                sm12
                                md6
                            >
                                <v-text-field
                                    label="SÉRIE"
                                    placeholder="00000000"
                                />
                            </v-flex>
                            <v-flex
                                xs12
                            >
                                <v-btn
                                    class="d-inline-block"
                                    dark
                                    color="teal"
                                    @click="pickFile"
                                >
                                    COMPROVANTE<v-icon right>attachment</v-icon>
                                </v-btn>
                                <v-text-field
                                    v-model="nomeArquivo"
                                    :placeholder="nomeArquivo"
                                    class="d-inline-block"
                                    label="SELECIONAR ARQUIVO"
                                    readonly
                                    full-width
                                    @click="pickFile"
                                />
                                <input
                                    ref="inputComprovante"
                                    type="file"
                                    style="display: none;"
                                    @change="onFilePicked"
                                >
                            </v-flex>
                        </v-layout>
                        <h3 class="my-2">DADOS DO COMPROVANTE BANCÁRIO</h3>
                        <v-layout
                            row
                            wrap
                        >
                            <v-flex
                                sm12
                                md6
                                lg3
                            >
                                <v-select
                                    :items="formasPagamento"
                                    label="FORMA DE PAGAMENTO"
                                />
                            </v-flex>
                            <v-flex
                                sm12
                                md6
                                lg3
                            >
                                <v-text-field
                                    label="DATA DO PAGAMENTO"
                                    placeholder="DD/MM/AAAA"
                                />
                            </v-flex>
                            <v-flex
                                sm12
                                md6
                                lg3
                            >
                                <v-text-field
                                    label="Nº DOCUMENTO PAGAMENTO"
                                    placeholder="00000000"
                                />
                            </v-flex>
                            <v-flex
                                sm12
                                md6
                                lg3
                            >
                                <v-text-field
                                    :hint="`*Atual: R$ ${valorAtual} / Máx: R$ ${valorComprovar}`"
                                    label="VALOR"
                                    persistent-hint
                                    placeholder="00000000"
                                />
                            </v-flex>
                        </v-layout>
                        <h3 class="my-2">JUSTIFICATIVA</h3>
                        <v-layout
                            row
                            wrap
                        >
                            <v-flex
                                xs12
                            >
                                <v-textarea
                                    value=""
                                    placeholder="Digite aqui a justificativa."
                                    no-resize
                                />
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer/>
                <v-btn
                    flat
                    color="success"
                >
                    Salvar
                </v-btn>
                <v-btn
                    flat
                    color="error"
                    @click="dialog = false"
                >
                    Cancelar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    props: {
        dataInicio: { type: String, default: '' },
        dataFim: { type: String, default: '' },
        valorComprovar: { type: String, default: '0' },
        valorAtual: { type: String, default: (0).toFixed(2) },
    },
    data() {
        return {
            cpfCnpjLabel: 'CPF',
            cpfCnpj: '',
            tipoComprovante: ['Cupom Fiscal', 'Guia de Recolhimento', 'Nota Fiscal/Fatura', 'Recibo de Pagamento', 'RPA'],
            formasPagamento: ['Cheque', 'Transferência Bancária', 'Saque/Dinheiro'],
            nomeArquivo: '',
            arquivoBinario: '',
            arquivo: '',
            dialog: false,
            action: 'Criar Comprovante',
        };
    },
    computed: {
        ...mapGetters({
            agente: 'avaliacaoResultados/buscarAgente',
        }),
        nomeRazaoSocialLabel() {
            return this.cpfCnpjLabel === 'CPF' ? 'NOME' : 'RAZÃO SOCIAL';
        },
        nomeRazaoSocial() {
            return this.agente[0] ? this.agente[0].agente.nome : '';
        },
        cpfCnpjParams() {
            return { cpf: this.cpfCnpj };
        },
    },
    methods: {
        ...mapActions({
            buscarAgente: 'avaliacaoResultados/buscarAgente',
        }),
        pickFile() {
            this.$refs.inputComprovante.click();
        },
        onFilePicked(e) {
            const arquivo = e.target.files[0];
            if (arquivo) {
                this.nomeArquivo = arquivo.name;
                const fileReader = new FileReader();
                fileReader.readAsBinaryString(arquivo);
                fileReader.addEventListener('load', () => {
                    this.arquivo = arquivo;
                    this.arquivoBinario = fileReader.result;
                });
            } else {
                this.nomeArquivo = '';
                this.arquivo = '';
                this.arquivoBinario = '';
            }
        },
    },
};
</script>
