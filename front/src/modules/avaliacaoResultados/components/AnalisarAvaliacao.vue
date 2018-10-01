<template>
    <v-layout row justify-center>
        <v-dialog v-model="dialog"
                  scrollable
                  fullscreen
                  transition="dialog-bottom-transition"
                  hide-overlay
        >
            <v-btn slot="activator" color="red" dark>
                <v-icon>gavel</v-icon>
            </v-btn>
            <v-card>
                <v-toolbar dark color="green darken-3">
                    <v-btn icon dark @click.native="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Avaliar itens</v-toolbar-title>
                </v-toolbar>

                <v-card-text>
                    <v-subheader >Dados da Comprovação</v-subheader>
                    <v-data-table
                            class="elevation-2"
                            hide-headers
                            :items="[]"
                            hide-actions
                    >
                        <template slot="no-data">
                            <tr>
                                <th colspan="6">Comprova&ccedil;&atilde;o de Pagamento do Item</th>
                            </tr>
                            <tr>
                                <td left><b>Produto:</b></td>
                                <td>asdasdasd</td>
                                <td left><b>Etapa:</b></td>
                                <td>asccccccccsd</td>
                                <td left><b>Item de Custo:</b></td>
                                <td>abbbbbda</td>
                            </tr>
                            <tr>
                                <td left><b>Valor Aprovado:</b></td>
                                <td>R$ 1212121212121212</td>
                                <td left><b>Valor Comprovado:</b></td>
                                <td>R$ 34343434343434343434</td>
                                <td left><b>Comprovação Validada:</b></td>
                                <td>R$ 454545</td>
                            </tr>
                        </template>
                    </v-data-table>

                    <v-subheader >Itens</v-subheader>

                    <v-expansion-panel>
                        <v-expansion-panel-content
                                v-for="(item,i) in 5"
                                :key="i"
                        >
                            <v-layout slot="header" class="blue--text">
                                <v-icon class="mr-3 blue--text" >local_shipping</v-icon>
                                Fornecedor: Emerson Teste da Silva - R$ 7.500,00
                                <v-spacer></v-spacer>
                                <v-chip small color="green" text-color="white">
                                    <v-avatar>
                                        <v-icon>check_circle</v-icon>
                                    </v-avatar>
                                    Aprovado
                                </v-chip>
                            </v-layout>

                            <v-card
                                    color="green lighten-4"
                                    flat
                                    tile
                            >
                                <v-flex >

                                    <v-toolbar dense>
                                        <v-toolbar-title>Avaliar Comprovante - comprovante.pdf</v-toolbar-title>


                                        <v-btn icon>
                                            <v-icon>get_app</v-icon>
                                        </v-btn>
                                        <v-spacer></v-spacer>
                                        <div>
                                            <v-btn depressed small color="primary">
                                                Salvar
                                                <v-icon right small>save</v-icon>
                                            </v-btn>
                                        </div>
                                    </v-toolbar>

                                    <v-card-text>
                                        <v-card>

                                            <v-card-text class="elevation-2">
                                                <v-data-table

                                                        class="elevation-2"
                                                        hide-headers

                                                        :items="[]"
                                                        hide-actions
                                                >
                                                    <template slot="no-data">
                                                        <tr>
                                                            <td left><b>Fornecedor:</b></td>
                                                            <td>Anbasadasd</td>
                                                            <td left><b>CNPJ/CPF:</b></td>
                                                            <td colspan="5">0475276912-12</td>
                                                        </tr>
                                                        <tr>
                                                            <td left><b>Comprovante:</b></td>
                                                            <td >123123123</td>
                                                            <td left><b>Número:</b></td>
                                                            <td>1231231231</td>
                                                            <td left><b>S&eacute;rie:</b></td>
                                                            <td colspan="3">12123123123123</td>
                                                        </tr>
                                                        <tr>
                                                            <td left><b>Dt. Emiss&atilde;o do comprovante de despesa:</b></td>
                                                            <td>15/15/15</td>
                                                            <td left><b>Forma de Pagamento:</b></td>
                                                            <td>testetsete</td>
                                                            <td left><b>Data do Pagamento:</b></td>
                                                            <td>12/12/12</td>
                                                            <td left style="width: 155px;"><b>N&ordm; Documento Pagamento:</b></td>
                                                            <td>asdasdaaaaaasdasd</td>
                                                        </tr>
                                                        <tr>
                                                            <td left><b>Valor:</b></td>
                                                            <td>R$ 111111111111111111</td>
                                                            <td left><b>Justificativa do Proponente:</b></td>
                                                            <td colspan="5">sgsdfgdfgsdfgsdfgsdfg</td>
                                                        </tr>
                                                        <tr>
                                                            <td left><b>Avaliação:</b></td>
                                                            <td colspan="7">
                                                                <v-radio-group row>
                                                                    <v-radio label="Aprovado" value="radio-1"></v-radio>
                                                                    <v-radio label="Reprovado" value="radio-2" color="red"></v-radio>
                                                                </v-radio-group>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </v-data-table>

                                                <v-textarea
                                                        solo
                                                        no-resize
                                                        label="Parecer da avaliação"
                                                        value=""
                                                        hint="Digite o parecer da sua avaliação"
                                                        height="180px"
                                                ></v-textarea>
                                            </v-card-text>
                                        </v-card>
                                    </v-card-text>
                                </v-flex>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';

    export default {
        name: 'AnalisarAvaliacao',
        // props: [
        //     'idPronac',
        //     'pronac',
        //     'nomeProjeto',
        // ],
        watch: {
            dialog(val) {
                if (val) {
                }
            },
        },
        data() {
            return {
                props: {
                    idPronac: 123456,
                    pronac: 12345,
                    nomeProjeto: 'Teste',
                },
                projetoHeaders: [
                    {
                        text: 'PRONAC',
                        align: 'center',
                        sortable: false,
                        value: 'pronac',
                    },
                    {
                        text: 'Nome do Projeto',
                        align: 'center',
                        sortable: false,
                        value: 'nomeProjeto',
                    },
                ],
                comprovacaoHeaders: [
                    {
                        text: 'Produto',
                        align: 'center',
                        sortable: false,
                    },
                    {
                        text: 'Etapa',
                        align: 'center',
                        sortable: false,
                    },
                    {
                        text: 'Item de Custo',
                        align: 'center',
                        sortable: false,
                    },
                ],
                produtoHeaders: [
                    {
                        text: 'Valor Aprovado',
                        align: 'center',
                        sortable: false,
                    },
                    {
                        text: 'Valor Comprovado',
                        align: 'center',
                        sortable: false,
                    },
                    {
                        text: 'Comprovação Validada',
                        align: 'center',
                        sortable: false,
                    },
                ],
                itemHeaders: [
                    {
                        text: 'Comprovante',
                        align: 'center',
                        sortable: false,
                    },
                    {
                        text: 'Dt. Emissão do comprovante',
                        align: 'center',
                        sortable: false,
                    },
                    {
                        text: 'c',
                        align: 'center',
                        sortable: false,
                    },
                ],
                dialog: false,
            };
        },
        components: {
        },
        methods: {
            ...mapActions({
                obterHistoricoEncaminhamento: 'avaliacaoResultados/obterHistoricoEncaminhamento',
            }),
        },
        computed: {
            ...mapGetters({
                dadosHistoricoEncaminhamento: 'avaliacaoResultados/dadosHistoricoEncaminhamento',
            }),
        },
    };
</script>