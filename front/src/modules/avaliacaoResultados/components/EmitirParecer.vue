<template>
    <v-container row justify-center>
        <v-form v-model="valid">
            <v-dialog 
                v-model="dialog" 
                full-width
                scrollable
                fullscreen
                transition="dialog-bottom-transition">
                <v-btn slot="activator" color="green" dark>Emitir Parecer</v-btn>
                <v-card>
                    <v-toolbar dark color="green">
                        <v-btn icon dark :href="redirectLink">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Avaliação Financeira - Emissão de Parecer</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn dark flat 
                                @click.native="salvarParecer(), confirmarSalvar = true" 
                                :disabled="!valid"
                                
                            >
                                Salvar
                            </v-btn>
                            <v-dialog
                                v-model="confirmarSalvar"
                                max-width="290"
                                width="200"
                                height="200"
                                >
                                <v-card>
                                    <v-container fluid>
                                        <v-layout align-center justify-center column>
                                            <v-flex xs12>
                                                    <v-card-text
                                                    class="subheading"
                                                    primary-title
                                                    >
                                                        <span class="black--text">Parecer salvo!</span>
                                                    </v-card-text>
                                            </v-flex>
                                            <v-divider></v-divider>
                                            <v-flex xs12>
                                                <v-btn
                                                    class="white--text"
                                                    color="green lighten-2"
                                                    @click="confirmarSalvar = false"
                                                    :href="redirectLink"
                                                >
                                                    OK
                                                </v-btn>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                            </v-dialog>
                            <v-btn dark flat
                                @click.native="finalizarParecer()"
                                :disabled="!valid"
                                :href="redirectLink"
                            >
                                Finalizar
                            </v-btn>
                        </v-toolbar-items>
                    </v-toolbar>

                    <v-card-text>
                        <v-container>
                            <v-card-text>
                                <v-card>
                                    <v-card-title primary-title>
                                        <v-container pa-0 ma-0>
                                        <div>
                                            <div class="headline"><b>Projeto:</b> {{projeto.AnoProjeto}}{{projeto.Sequencial}} - {{projeto.NomeProjeto}}</div>
                                            <span class="black--text"><b>Proponente:</b> {{proponente.CgcCpf | cnpjFilter}} - {{proponente.Nome}}</span>
                                        </div>
                                        </v-container>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-container grid-list-xs text-xs-center ma-0 pa-0>
                                                <v-layout row wrap>
                                                <v-flex xs12 md6 mb-2>
                                                        <v-data-table
                                                            :items="[]"
                                                            class="elevation-2"
                                                            hide-headers
                                                            hide-actions
                                                        >
                                                            <template slot="no-data">
                                                                <tr>
                                                                    <th colspan="6">Quantidade de Comprovantes</th>
                                                                </tr>
                                                                <tr>
                                                                    <td left><b>Total:</b></td>
                                                                    <td >{{consolidacaoComprovantes.qtTotalComprovante | currency}}</td>
                                                                    <td left><b>Validados:</b></td>
                                                                    <td><font color="#006400">{{consolidacaoComprovantes.qtComprovantesValidadosProjeto | currency}} </font></td>
                                                                </tr>
                                                                <tr>
                                                                    <td left><b>Não Avaliados:</b></td>
                                                                    <td left>{{consolidacaoComprovantes.qtComprovantesNaoAvaliados | currency}}</td>
                                                                    <td left><b>Recusados:</b></td>
                                                                    <td left><font color="red">{{consolidacaoComprovantes.qtComprovantesRecusadosProjeto | currency}} </font></td>
                                                                </tr>
                                                            </template>
                                                        </v-data-table>
                                                </v-flex>

                                                <v-flex xs12 md6 mb-4>
                                                    <v-data-table
                                                        :items="[]"
                                                        class="elevation-1"
                                                        hide-headers
                                                        hide-actions
                                                    >
                                                        <template slot="no-data">
                                                            <tr>
                                                                <th colspan="6">Valores Comprovados</th>
                                                            </tr>
                                                            <tr>
                                                                <td left><b>Total:</b></td>
                                                                <td >{{consolidacaoComprovantes.vlComprovadoProjeto | currency}}</td>
                                                                <td left><b>Recusados:</b></td>
                                                                <td left><font color="red">{{consolidacaoComprovantes.vlComprovadoRecusado | currency}}</font></td>
                                                            </tr>
                                                            <tr>
                                                                <td left><b>Validados:</b></td>
                                                                <td><font color="#006400">{{consolidacaoComprovantes.vlComprovadoValidado | currency}}</font></td>
                                                            </tr>
                                                        </template>
                                                    </v-data-table>
                                                </v-flex>

                                                <v-flex xs4 d-flex>
                                                        <v-select 
                                                            height="20px"
                                                            :value="getParecer.siManifestacao"
                                                            @input="inputManifestacao($event)"
                                                            :rules="itemRules"
                                                            :items="items"
                                                            item-text="text"
                                                            item-value="id"
                                                            label="Manifestação *"
                                                            required="required"
                                                            solo
                                                            append-icon="keyboard_arrow_down"
                                                            full-width
                                                        ></v-select>
                                                </v-flex> 

                                                <v-flex md12 xs12 mb-4>
                                                    <v-card>
                                                        <v-textarea
                                                            :value="getParecer.dsParecer"
                                                            @input="inputParecer($event)"
                                                            :rules="parecerRules"
                                                            color="deep-purple"
                                                            label="Texto do Parecer *"
                                                            height="200px"
                                                            required="required"
                                                            outline
                                                        ></v-textarea>
                                                    </v-card>
                                                </v-flex>  

                                            </v-layout>
                                        </v-container>
                                    </v-card-text>
                                </v-card>
                            </v-card-text>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </v-form>
    </v-container>
</template>

<script>
import Vue from 'vue';
import { mapActions, mapGetters } from 'vuex';
import ModalTemplate from '@/components/modal';
import cnpjFilter from '@/filters/cnpj';
import VueCurrencyFilter from 'vue-currency-filter';

Vue.use(VueCurrencyFilter, {
    symbol: 'R$',
    thousandsSeparator: '.',
    fractionCount: 2,
});

export default {
    name: 'EmitirParecer',
    data() {
        return {
            tipo: true,
            idPronac: this.$route.params.id,
            redirectLink: '#/planilha/',
            confirmarSalvar: false,
            valid: false,
            dialog: true,
            itemRules: [v => !!v || 'Tipo de manifestação e obrigatório!'],
            parecerRules: [
                v => !!v || 'Parecer e obrigatório!',
                v => v.length >= 10 || 'Parecer deve conter mais que 10 characteres',
            ],
            items: [
                {
                    id: 'R',
                    text: 'Reprovação',
                },
                {
                    id: 'A',
                    text: 'Aprovação',
                },
                {
                    id: 'P',
                    text: 'Aprovação com Ressalva',
                },
            ],
            parecerData: {},
        };
    },
    components: {
        ModalTemplate,
    },
    methods: {
        ...mapActions({
            requestEmissaoParecer: 'avaliacaoResultados/getDadosEmissaoParecer',
            salvar: 'avaliacaoResultados/salvarParecer',
            finalizar: 'avaliacaoResultados/finalizarParecer',
            alterarParecer: 'avaliacaoResultados/alterarParecer',
        }),
        fecharModal() {
            this.modalClose();
        },
        getConsolidacao(id) {
            this.requestEmissaoParecer(id);
        },
        salvarParecer() {
            const data = {
                idPronac: this.idPronac,
                tpAvaliacaoFinanceira: this.tipo,
                siManifestacao: this.getParecer.siManifestacao,
                dsParecer: this.getParecer.dsParecer,
            };

            if (this.parecer.idAvaliacaoFinanceira) {
                data.idAvaliacaoFinanceira = this.parecer.idAvaliacaoFinanceira;
            }

            if (this.parecerData.siManifestacao) {
                data.siManifestacao = this.parecerData.siManifestacao;
            }

            if (this.parecerData.dsParecer) {
                data.dsParecer = this.parecerData.dsParecer;
            }

            this.salvar(data);
            /** Descomentar linha após migração da lista para o VUEJS */
            // this.dialog = false;
        },
        finalizarParecer() {
            const data = {
                idPronac: this.idPronac,
                tpAvaliacaoFinanceira: this.tipo,
                siManifestacao: this.parecer.siManifestacao,
                dsParecer: this.parecer.dsParecer,
                atual: 5,
                proximo: 6,
            };

            this.finalizar(data);
        },
        inputParecer(e) {
            this.parecerData.dsParecer = e;
        },
        inputManifestacao(e) {
            this.parecerData.siManifestacao = e;
        },
    },
    computed: {
        ...mapGetters({
            modalVisible: 'modal/default',
            consolidacaoComprovantes: 'avaliacaoResultados/consolidacaoComprovantes',
            proponente: 'avaliacaoResultados/proponente',
            parecer: 'avaliacaoResultados/parecer',
            projeto: 'avaliacaoResultados/projeto',
            getParecer: 'avaliacaoResultados/parecer',
        }),
    },
    mounted() {
        this.redirectLink = this.redirectLink + this.idPronac;
        this.getConsolidacao(this.idPronac);
    },
    filters: {
        cnpjFilter,
    },
};
</script>
