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
                        label="Tipo do Fornecedor"
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
                    xs12
                    sm6
                    md6
                >
                    <v-text-field
                        :label="cpfCnpj"
                        placeholder="239.456.123-85"
                        disabled
                    />
                </v-flex>
                <v-flex
                    xs12
                    sm6
                    md6
                >
                    <v-text-field
                        label="Nome"
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
                    xs12
                    sm6
                    d-flex
                >
                    <v-select
                        :items="tipoComprovante"
                        label="TIPO COMPROVANTE"
                        disabled
                    />
                </v-flex>
                <v-flex
                    xs12
                    sm6
                    md3
                >
                    <v-text-field
                        label="DT. EMISSÃO DO COMPROVANTE DE DESPESA"
                        placeholder="DD/MM/AAAA"
                        disabled
                    />
                </v-flex>
                <v-flex
                    xs12
                    sm6
                    md4
                >
                    <v-text-field
                        label="NÚMERO"
                        placeholder="00000000"
                        disabled
                    />
                </v-flex>
                <v-flex
                    xs12
                    sm6
                    md4
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
                        class="d-inline-block"
                        label="Selecionar arquivo"
                        @click="pickFile"
                    />
                    <input
                        ref="inputComprovante"
                        type="file"
                        style="display: none;"
                        @change=""
                    >
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
            nomeArquivo: '',
        };
    },
    methods: {
        pickFile() {
            this.$refs.inputComprovante.click();
        },
    },
};
</script>
