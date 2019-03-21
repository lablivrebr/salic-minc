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
                <v-form
                    ref="form"
                    v-model="valid"
                    lazy-validation
                >
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
                                        label="CNPJ"
                                        value="CNPJ"
                                        color="teal"
                                    />
                                    <v-radio
                                        label="CPF"
                                        value="CPF"
                                        color="teal"

                                    />
                                </v-radio-group>
                            </v-flex>
                            <v-flex
                                sm12
                                md3
                            >
                                <v-text-field
                                    :label="`${cpfCnpjLabel} *`"
                                    :rules="cpfCnpjRules"
                                    v-model="cpfCnpj"
                                    :mask="cpfCnpjMask"
                                    :placeholder="cpfCnpjPlaceholder"
                                    validate-on-blur
                                    outline
                                    append-icon="search"
                                    @click:append="buscarAgente(cpfCnpjParams)"
                                    @keyup.enter="buscarAgente(cpfCnpjParams)"
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
                                    :value="tipoComprovante[0]"
                                    label="TIPO COMPROVANTE *"
                                    outline
                                />
                            </v-flex>

                            <v-flex
                                sm12
                                md3
                            >
                                <v-menu
                                    ref="dataEmissaoMenu"
                                    v-model="dataEmissaoPicker"
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
                                        :rules="dataEmissaoRules"
                                        :hint="`*Início em: ${dataInicioFormatada} até ${dataFimFormatada}`"
                                        persistent-hint
                                        label="DATA DA EMISSÃO *"
                                        placeholder="DD/MM/AAAA"
                                        append-icon="event"
                                        outline
                                        readonly
                                    />
                                    <v-date-picker
                                        ref="dataEmissaoPicker"
                                        v-model="dataEmissao"
                                        :min="dataInicio"
                                        :max="dataFim"
                                        no-title
                                        locale="pt-br"
                                        @change="save('dataEmissaoMenu')"
                                    />
                                </v-menu>
                            </v-flex>
                            <v-flex
                                sm12
                                md3
                            >
                                <v-text-field
                                    :rules="numeroRules"
                                    label="NÚMERO *"
                                    placeholder="00000000"
                                    outline
                                    @keypress="apenasNumeros"
                                />
                            </v-flex>
                            <v-flex
                                sm12
                                md3
                            >
                                <v-text-field
                                    label="SÉRIE"
                                    placeholder="00000000000"
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
                                    COMPROVANTE *<v-icon right>attachment</v-icon>
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
                                    :value="formasPagamento[0]"
                                    label="FORMA DE PAGAMENTO *"
                                    outline
                                />
                            </v-flex>
                            <v-flex
                                sm12
                                md6
                                lg3
                            >
                                <v-menu
                                    ref="dataPagamentoMenu"
                                    v-model="dataPagamentoPicker"
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
                                        v-model="dataPagamentoFormatada"
                                        :rules="dataPagamentoRules"
                                        label="DATA PAGAMENTO *"
                                        placeholder="DD/MM/AAAA"
                                        append-icon="event"
                                        outline
                                        readonly
                                    />
                                    <v-date-picker
                                        ref="dataPagamentoPicker"
                                        v-model="dataPagamento"
                                        :max="new Date().toISOString()"
                                        no-title
                                        locale="pt-br"
                                        @change="save('dataPagamentoMenu')"
                                    />
                                </v-menu>
                            </v-flex>
                            <v-flex
                                sm12
                                md6
                                lg3
                            >
                                <v-text-field
                                    :rules="numeroRules"
                                    label="Nº DOCUMENTO PAGAMENTO *"
                                    placeholder="00000000000"
                                    outline
                                    @keypress="apenasNumeros"
                                />
                            </v-flex>
                            <v-flex
                                sm12
                                md6
                                lg3
                            >
                                <v-text-field
                                    v-money="money"
                                    :hint="`*Atual: R$ ${valorAtual} / Máx: R$ ${valorComprovar}`"
                                    :rules="valorRules"
                                    v-model="valor"
                                    label="VALOR *"
                                    placeholder="00,00"
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
                    :disabled="!valid"
                    flat
                    color="success"
                    @click="submit"
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
import { VMoney } from 'v-money';

export default {
    directives: { money: VMoney },
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
            dialog: false,
            valid: true,

            cpfCnpjLabel: 'CNPJ',
            cpfCnpj: '',
            cpfRules: [
                cpf => !!cpf || 'O campo CPF é obrigatório!',
                cpf => cpf.length === 11 || 'O CPF informado não é válido!',
            ],
            cnpjRules: [
                cnpj => !!cnpj || 'O campo CNPJ é obrigatório!',
                cnpj => cnpj.length === 14 || 'O CNPJ informado não é válido!',
            ],

            tipoComprovante: ['Cupom Fiscal', 'Guia de Recolhimento', 'Nota Fiscal/Fatura', 'Recibo de Pagamento', 'RPA'],
            dataEmissao: '',
            dataEmissaoPicker: false,
            dataEmissaoRules: [
                data => !!data || 'O campo data de emissão é obrigatório!',
            ],
            numeroRules: [
                numero => !!numero || 'O campo Número é obrigatório!',
            ],
            nomeArquivo: '',
            arquivoBinario: '',
            arquivo: '',

            formasPagamento: ['Cheque', 'Transferência Bancária', 'Saque/Dinheiro'],
            dataPagamento: '',
            dataPagamentoPicker: false,
            dataPagamentoRules: [
                data => !!data || 'O campo data de pagamento é obrigatório!',
            ],
            valor: 'R$ 0,00',
            valorRules: [
                valor => (this.valorNumber(valor) > 0) || 'O campo valor é obrigatório e deve ser maior que 0(zero)!',
                valor => (this.valorNumber(valor) <= this.valorNumber(this.valorComprovar)) || 'O valor informado é maior que o valor a comprovar!',
            ],
            money: {
                decimal: ',',
                thousands: '.',
                prefix: 'R$ ',
                precision: 2,
            },
        };
    },
    computed: {
        ...mapGetters({
            agente: 'avaliacaoResultados/buscarAgente',
        }),
        cpfCnpjMask() {
            const cpfMask = '###.###.###-##';
            const cnpjMask = '##.###.###/####-##';
            return this.cpfCnpjLabel === 'CNPJ' ? cnpjMask : cpfMask;
        },
        cpfCnpjPlaceholder() {
            const cpfPlaceholder = '000.000.000-00';
            const cnpjPlaceholder = '00.000.000/0000-00';
            return this.cpfCnpjLabel === 'CNPJ' ? cnpjPlaceholder : cpfPlaceholder;
        },
        cpfCnpjRules() {
            return this.cpfCnpjLabel === 'CNPJ' ? this.cnpjRules : this.cpfRules;
        },
        nomeRazaoSocialLabel() {
            return this.cpfCnpjLabel === 'CNPJ' ? 'RAZÃO SOCIAL' : 'NOME';
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
        dataPagamentoFormatada() {
            if (!this.dataPagamento) return null;

            const [ano, mes, dia] = this.dataPagamento.split('-');
            return `${dia}/${mes}/${ano}`;
        },
    },
    watch: {
        dataEmissaoPicker(val) {
            if (val) {
                setTimeout(() => {
                    this.$refs.dataEmissaoPicker.activePicker = 'YEAR';
                });
            }
        },
        dataPagamentoPicker(val) {
            if (val) {
                setTimeout(() => {
                    this.$refs.dataPagamentoPicker.activePicker = 'YEAR';
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
        save(picker, date) {
            this.$refs[picker].save(date);
        },
        apenasNumeros(e) {
            if (e.charCode < 48 || e.charCode > 57) {
                e.preventDefault();
            }
        },
        // Converte uma string de preço para um número flutuante
        valorNumber(number) {
            let string = number.replace(/R\$/g, ''); // Retira prefixo R$
            string = string.replace(/\./g, ''); // Retira pontos
            string = string.replace(/,/g, '.'); // Transforma vírgulas em pontos
            return parseFloat(string);
        },
        submit() {
            this.$refs.form.validate();
        },
    },
};
</script>
