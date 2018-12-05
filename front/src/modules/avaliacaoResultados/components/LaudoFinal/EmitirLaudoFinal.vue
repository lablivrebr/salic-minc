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
                                   :disabled="!valid || !laudoRules.enable">Salvar
                            </v-btn>
                            <v-btn dark flat
                                   @click.native="finalizarLaudoFinal()"
                                   :disabled="!valid || !laudoRules.enable"
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

                        <v-flex md12 xs12 mb-4>
                            <v-card>
                                <v-responsive>
                                    <div v-show="laudoRules.show" class="text-xs-left"><h4 :class="laudoRules.color">{{laudoRules.msg}}*</h4></div>
                                    <EditorTexto
                                        :style="laudoRules.backgroundColor"
                                        :value="parecerLaudoFinal.items.dsLaudoFinal"
                                        @editor-texto-input="inputLaudo($event)"
                                        @editor-texto-counter="validarLaudo($event)"
                                        required="required"
                                    >
                                    </EditorTexto>
                                </v-responsive>
                            </v-card>
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
import EditorTexto from '../components/EditorTexto';

export default {
    components: {
        EditorTexto,
    },
    data() {
        return {
            tipo: true,
            idPronac: this.$route.params.id,
            valid: false,
            dialog: true,
            laudoRules: {
                show: false,
                color: '',
                backgroundColor: '',
                msg: '',
                enable: false,
            },
            manifestacaoRules: [
                v => !!v || 'Tipo de manifestação é obrigatório!',
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
        validarLaudo(e) {
            if (e < 10) {
                this.laudoRules = { show: true,
                    color: 'red--text',
                    backgroundColor: { 'background-color': '#FFCDD2' },
                    msg: 'O Laudo deve conter mais que 10 characteres',
                    enable: false };
            }
            if (e < 1) {
                this.laudoRules = { show: true,
                    color: 'red--text',
                    backgroundColor: { 'background-color': '#FFCDD2' },
                    msg: 'O Laudo é obrigatório!',
                    enable: false };
            }
            if (e >= 10) {
                this.laudoRules = { show: false,
                    color: '',
                    backgroundColor: '',
                    msg: '',
                    enable: true };
            }
        },
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
            console.info(data);
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
            console.info(data);

            this.finalizar(data);
        },
        updateManifestacao(e) {
            this.laudoFinalData.siManifestacao = e;
        },
        inputLaudo(e) {
            this.laudoFinalData.dsLaudoFinal = e;
            this.validarLaudo(e);
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
    mounted() {
        this.validarLaudo();
    },
    filters: {
        cnpjFilter,
    },
};
</script>
