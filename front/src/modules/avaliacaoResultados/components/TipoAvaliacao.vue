<template>
    <v-container grid-list-md >
        <v-layout  column>
            <v-flex xs12>
                <v-layout row xs12  wrap align-space-between  >
                    <v-flex  xs12 >
                        <v-card>
                            <v-card-text>
                                <table class="v-datatable v-table">
                                    <tr>
                                        <th class="text-xs-left">
                                            Dt.Envio Prestacao de Contas
                                        </th>
                                        <th class="text-xs-left">
                                            Resultado da Avaliacao do Objeto
                                        </th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-xs-left">Cumpriu objeto e objetivos</td>
                                    </tr>
                                </table>
                            </v-card-text>
                        </v-card>
                    </v-flex>

                    <v-flex xs12 >
                        <v-card>
                            <v-card-text>
                                <table class="v-datatable v-table">
                                    <tr>
                                        <th colspan="3" class="text-xs-center">
                                            Valores
                                        </th>
                                        <th colspan="4" class="text-xs-center">
                                            Quantidade de Comprovantes por nivel de confianca
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-xs-center">Aprovado</th>
                                        <th class="text-xs-center">Captado</th>
                                        <th class="text-xs-center">Comprovado</th>
                                        <th>Todos</th>
                                        <th>90%</th>
                                        <th>95%</th>
                                        <th>99%</th>
                                    </tr>
                                    <tr>
                                        <td class="text-xs-center">{{aprovado}}</td>
                                        <td class="text-xs-center">{{captado}}</td>
                                        <td class="text-xs-center">{{comprovado}}</td>
                                        <td class="text-xs-center">{{todos}}</td>
                                        <td class="text-xs-center">{{valor1}}</td>
                                        <td class="text-xs-center">{{valor2}}</td>
                                        <td class="text-xs-center">{{valor3}}</td>
                                    </tr>
                                </table>
                            </v-card-text>
                        </v-card>
                    </v-flex>

                    <v-flex xs12>
                        <v-card>
                            <v-card-text>
                                <fieldset>
                                    <legend>SELECIONAR O TIPO DE AVALIACAO FINANCERIA</legend>
                                    <p>Avaliar comprovantes por nivel de confianca:</p>
                                    <v-flex xs12 sm6 md6>
                                        <v-radio-group  column v-model="tipoAvaliacao">

                                            <v-radio
                                                    label="Todos Comprovantes"
                                                    color="cyan darken-2"
                                                    value="todos comprovantes"
                                            ></v-radio>
                                            <v-radio
                                                    label="90%"
                                                    color="teal darken-1"
                                                    value="90%"
                                            ></v-radio>
                                            <v-radio
                                                    label="95%"
                                                    color="teal darken-1"
                                                    value="95%"
                                            ></v-radio>
                                            <v-radio
                                                    label="99%"
                                                    color="teal darken-1"
                                                    value="99%"
                                            ></v-radio>
                                        </v-radio-group>
                                        <v-btn dark large color="teal darken-1" to="/planilha">AVALIAR</v-btn>
                                    </v-flex>
                                </fieldset>
                            </v-card-text>
                        </v-card>
                    </v-flex>

                </v-layout>

            </v-flex>

        </v-layout>
    </v-container>


</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import ModalTemplate from '@/components/modal';

export default {
    name: 'Painel',
    data() {
        return {
            aprovado:300,
            captado:200,
            comprovado:100,
            todos:75,
            valor1:80,
            valor2:10,
            valor3:20,
            tipoAvaliacao:""




        };
    },
    components: {
        ModalTemplate,
    },
    methods: {
        ...mapActions({
            criarRegistro: 'foo/criarRegistro',
            modalOpen: 'modal/modalOpen',
            modalClose: 'modal/modalClose',
        }),
        fecharModal() {
            // eslint-disable-next-line
            $3('#modalTemplate').modal('close');
            this.modalClose();
        },
    },
    computed: mapGetters({
        modalVisible: 'modal/default',
    }),
};
</script>

