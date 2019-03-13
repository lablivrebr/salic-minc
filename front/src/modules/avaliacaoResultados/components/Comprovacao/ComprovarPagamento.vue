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
                <v-form v-model="valid">
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
                                md3
                            >
                                <v-text-field
                                    :label="cpfCnpjLabel"
                                    :rules="cpfCnpjRules"
                                    v-model="cpfCnpj"
                                    validate-on-blur
                                    mask="###.###.###-##"
                                    placeholder="000.000.000-00"
                                    outline
                                    append-icon="search"
                                    @click:append="buscarAgente(cpfCnpjParams)"
                                    @keyup.enter="buscarAgente(cpfCnpjParams)"
                                    @blur="buscarAgente(cpfCnpjParams)"
                                />
                            </v-flex>
                            <v-flex
                                sm12
                                md6
                            >
                                <v-text-field
                                    :label="nomeRazaoSocialLabel"
                                    :value="nomeRazaoSocial"
                                    outline
                                    readonly
                                />
                            </v-flex>
                            <v-flex
                                sm12
                            >
                                <div v-if="agente.length === 0">
                                    <span class="error--text caption d-block ml-2 md-1">Fornecedor não cadastrado!</span>
                                    <v-btn
                                        :href="`/prestacao-contas/fornecedor/index/cpfcnpj/${cpfCnpj}`"
                                        color="teal"
                                        dark
                                    >
                                        CADASTRAR FORNECEDOR
                                    </v-btn>
                                </div>
                            </v-flex>
                        </v-layout>

                        <h3 class="my-2">DADOS DO COMPROVANTE DE DESPESA</h3>
                        <v-layout
                            row
                            wrap
                        >
                            <v-flex
                                sm12
                                md3
                            >
                                <v-select
                                    :items="tipoComprovante"
                                    value="Cupom Fiscal"
                                    label="TIPO COMPROVANTE"
                                    outline
                                />
                            </v-flex>

                            <v-flex
                                sm12
                                md3
                            >
                                <v-menu
                                    ref="menu"
                                    v-model="datePicker"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    lazy
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <v-text-field
                                        slot="activator"
                                        v-model="dataEmissaoFormatada"
                                        :hint="`*Início em: ${dataInicioFormatada} até ${dataFimFormatada}`"
                                        persistent-hint
                                        label="DATA DA EMISSÃO"
                                        placeholder="DD/MM/AAAA"
                                        append-icon="event"
                                        outline
                                        readonly
                                    />
                                    <v-date-picker
                                        ref="picker"
                                        v-model="dataEmissao"
                                        :min="dataInicio"
                                        :max="dataFim"
                                        no-title
                                        locale="pt-br"
                                        @change="save"
                                    />
                                </v-menu>
                            </v-flex>
                            <v-flex
                                sm12
                                md3
                            >
                                <v-text-field
                                    label="NÚMERO"
                                    placeholder="00000000"
                                    outline
                                />
                            </v-flex>
                            <v-flex
                                sm12
                                md3
                            >
                                <v-text-field
                                    label="SÉRIE"
                                    placeholder="00000000"
                                    outline
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
                                    label="Selecionar arquivo..."
                                    readonly
                                    solo
                                    flat
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
                                    value="Cheque"
                                    label="FORMA DE PAGAMENTO"
                                    outline
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
                                    outline
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
                                    outline
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
                                    placeholder="00000000"
                                    persistent-hint
                                    outline
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
                                    outline
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
        dataInicioFormatada: { type: String, default: '' },
        dataFimFormatada: { type: String, default: '' },
        dataInicio: { type: String, default: '' },
        dataFim: { type: String, default: '' },
        valorComprovar: { type: String, default: '0' },
        valorAtual: { type: String, default: (0).toFixed(2) },
    },
    data() {
        return {
            valid: false,
            cpfCnpjLabel: 'CPF',
            cpfCnpj: '',
            cpfCnpjRules: [
                cpfCnpj => !!cpfCnpj || `O campo ${this.cpfCnpjLabel} é obrigatório!`,
                cpfCnpj => cpfCnpj.length === 11 || `O ${this.cpfCnpjLabel} informado não é válido!`,
            ],
            dataEmissao: '',
            datePicker: false,
            tipoComprovante: ['Cupom Fiscal', 'Guia de Recolhimento', 'Nota Fiscal/Fatura', 'Recibo de Pagamento', 'RPA'],
            formasPagamento: ['Cheque', 'Transferência Bancária', 'Saque/Dinheiro'],
            nomeArquivo: '',
            arquivoBinario: '',
            arquivo: '',
            dialog: false,
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
        dataEmissaoFormatada() {
            if (!this.dataEmissao) return null;

            const [ano, mes, dia] = this.dataEmissao.split('-');
            return `${dia}/${mes}/${ano}`;
        },
    },
    watch: {
        datePicker(val) {
            if (val) {
                setTimeout(() => {
                    this.$refs.picker.activePicker = 'YEAR';
                });
            }
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
        save(date) {
            this.$refs.menu.save(date);
        },
    },
};
</script>
