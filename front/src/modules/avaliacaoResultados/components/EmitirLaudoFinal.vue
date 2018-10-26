<template>
    <v-container grid-list-xl>
        <v-form ref="form" v-model="valid">
            <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                <v-card>
                    <v-toolbar dark color="green">
                        <v-btn icon dark :to="{ name: 'Laudo' }">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Avaliação de Resultados - Emitir Laudo Final</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn dark flat
                                   @click.native="salvarLaudoFinal()"
                                   :disabled="!valid">Salvar
                            </v-btn>
                            <v-btn dark flat
                                   @click.native="finalizarLaudoFinal()"
                                   :disabled="!valid"
                                   :to="{ name: 'Laudo'}">Finalizar
                            </v-btn>
                        </v-toolbar-items>
                    </v-toolbar>
                    <v-container grid-list-sm>
                        <v-layout row wrap>
                            <v-flex xs12 sm12 md12>
                                <p><b>Projeto:</b> {{projeto.AnoProjeto}}{{projeto.Sequencial}} - {{projeto.NomeProjeto}}</p>
                            </v-flex>
                            <v-flex xs12 sm12 md12 v-if="proponente.CgcCpf || proponente.Nome">
                                <p><b>Proponente:</b> {{proponente.CgcCpf | cnpjFilter}} - {{proponente.Nome}}</p>
                            </v-flex>
                        </v-layout>
                        <v-divider></v-divider>
                    </v-container>
                    <v-container grid-list v-if="parecerLaudoFinal.items">
                        <v-layout wrap align-center>
                            <v-flex>
                                <label for="manifestacao">Manifestação *</label>
                                <v-radio-group :value="parecerLaudoFinal.items.siManifestacao"
                                               @change="updateManifestacao"
                                               id="manifestacao"
                                               :rules="manifestacaoRules"
                                               row>
                                    <v-radio color="success" label="Aprovado" value="A"></v-radio>
                                    <v-radio color="success" label="Aprovado com ressalvas" value="P"></v-radio>
                                    <v-radio color="success" label="Reprovado" value="R"></v-radio>
                                </v-radio-group>
                            </v-flex>
                        </v-layout>
                        <v-flex>
                            <label for="parecer">Parecer *</label>
                            <v-textarea :value="parecerLaudoFinal.items.dsLaudoFinal"
                                        v-if="parecerLaudoFinal.items.dsLaudoFinal? parecerLaudoFinal.items.dsLaudoFinal : ''"
                                        @input="updateParecer"
                                        :rules="parecerRules"
                                        color="deep-purple"
                                        id="parecer"
                                        height="200px"
                                        required="required">
                            </v-textarea>
                        </v-flex>
                    </v-container>
                </v-card>
            </v-dialog>
        </v-form>
    </v-container>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import cnpjFilter from '@/filters/cnpj';

    export default {
        data() {
            return {
                tipo: true,
                idPronac: this.$route.params.id,
                valid: false,
                dialog: true,
                manifestacaoRules: [
                    v => !!v || 'Tipo de manifestação é obrigatório!',
                ],
                parecerRules: [
                    v => !!v || 'Parecer é obrigatório!',
                    v => Object(v).length >= 10 || 'Parecer deve conter mais que 10 caracteres',
                ],
                laudoFinalData: { },
            };
        },
        methods: {
            ...mapActions({
                modalOpen: 'modal/modalOpen',
                modalClose: 'modal/modalClose',
                salvar: 'avaliacaoResultados/salvarLaudoFinal',
                finalizar: 'avaliacaoResultados/finalizarLaudoFinal',
            }),
            salvarLaudoFinal() {
                const data = {
                    idPronac: this.idPronac,
                    siManifestacao: this.parecerLaudoFinal.items.siManifestacao,
                    dsLaudoFinal: this.parecerLaudoFinal.items.dsLaudoFinal,
                };

                if (this.parecerLaudoFinal.items.idLaudoFinal) {
                    data.idLaudoFinal = this.parecerLaudoFinal.items.idLaudoFinal;
                }

                if (this.laudoFinalData.siManifestacao) {
                    data.siManifestacao = this.laudoFinalData.siManifestacao;
                }

                if (this.laudoFinalData.dsLaudoFinal) {
                    data.dsLaudoFinal = this.laudoFinalData.dsLaudoFinal;
                }

                this.salvar(data);
            },
            finalizarLaudoFinal() {
                const data = {
                    idpronac: this.idPronac,
                    idtipodoatoadministrativo: 623,
                    siManifestacao: this.parecerLaudoFinal.items.siManifestacao,
                    dsLaudoFinal: this.parecerLaudoFinal.items.dsLaudoFinal,
                    atual: 10,
                    proximo: 14,
                };

                if (this.parecerLaudoFinal.items.idLaudoFinal) {
                    data.idLaudoFinal = this.parecerLaudoFinal.items.idLaudoFinal;
                }

                if (this.laudoFinalData.siManifestacao) {
                    data.siManifestacao = this.laudoFinalData.siManifestacao;
                }

                if (this.laudoFinalData.dsLaudoFinal) {
                    data.dsLaudoFinal = this.laudoFinalData.dsLaudoFinal;
                }

                this.finalizar(data);
            },
            updateManifestacao(e) {
                this.laudoFinalData.siManifestacao = e;
            },
            updateParecer(e) {
                this.laudoFinalData.dsLaudoFinal = e;
            },
        },
        computed: {
            ...mapGetters({
                modalVisible: 'modal/default',
                proponente: 'avaliacaoResultados/proponente',
                projeto: 'avaliacaoResultados/projeto',
                parecerLaudoFinal: 'avaliacaoResultados/getParecerLaudoFinal',
            }),
        },
        filters: {
            cnpjFilter,
        },
    };
</script>
