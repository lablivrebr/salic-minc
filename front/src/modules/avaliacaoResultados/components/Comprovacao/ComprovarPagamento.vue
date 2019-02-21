<template>
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
                        v-model="cpfCnpj"
                        label="TIPO DO FORNECEDOR"
                        disabled
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
                    md6
                >
                    <v-text-field
                        :label="cpfCnpj"
                        placeholder="239.456.123-85"
                        disabled
                    />
                </v-flex>
                <v-flex
                    sm12
                    md6
                >
                    <v-text-field
                        label="NOME"
                        placeholder="Rômulo Menhô Barbosa"
                        disabled
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
                    lg3
                >
                    <v-select
                        :items="tipoComprovante"
                        label="TIPO COMPROVANTE"
                        disabled
                    />
                </v-flex>
                <v-flex
                    sm12
                    md6
                    lg3
                >
                    <v-text-field
                        label="DATA EMISSÃO DO COMPROVANTE DE DESPESA"
                        placeholder="DD/MM/AAAA"
                        disabled
                    />
                </v-flex>
                <v-flex
                    sm12
                    md6
                    lg3
                >
                    <v-text-field
                        label="NÚMERO"
                        placeholder="00000000"
                        disabled
                    />
                </v-flex>
                <v-flex
                    sm12
                    md6
                    lg3
                >
                    <v-text-field
                        label="SÉRIE"
                        placeholder="00000000"
                        disabled
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
                        disabled
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
                        disabled
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
                        disabled
                    />
                </v-flex>
                <v-flex
                    sm12
                    md6
                    lg3
                >
                    <v-text-field
                        label="VALOR***validar valor***"
                        placeholder="00000000"
                        disabled
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
                        disabled
                    />
                </v-flex>
            </v-layout>
        </v-container>
    </v-form>
</template>

<script>
export default {
    data() {
        return {
            cpfCnpj: 'CPF',
            tipoComprovante: ['Cupom Fiscal', 'Guia de Recolhimento', 'Nota Fiscal/Fatura', 'Recibo de Pagamento', 'RPA'],
            formasPagamento: ['Cheque', 'Transferência Bancária', 'Saque/Dinheiro'],
            nomeArquivo: '',
            arquivoBinario: '',
            arquivo: '',
        };
    },
    methods: {
        pickFile() {
            this.$refs.inputComprovante.click();
        },
        onFilePicked(e) {
            console.log(e);
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
