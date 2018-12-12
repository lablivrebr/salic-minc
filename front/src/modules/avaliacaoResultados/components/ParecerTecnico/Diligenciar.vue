<template>
    <v-container>
        <v-layout row wrap>
            <v-flex>
                <v-dialog
                    v-model="dialog"
                    full-width
                    scrollable
                    fullscreen
                    transition="dialog-bottom-transition"
                >
                    <v-card>
                        <v-toolbar dark color="primary">
                            <v-btn icon dark :to="{ name: 'AnalisePlanilha', params:{ id:this.idPronac }}">
                                <v-icon>close</v-icon>
                            </v-btn>
                            <v-toolbar-title>Avaliação Financeira - Diligenciar</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                        <v-card-text>
                            <v-container>
                                <v-card>
                                    <v-card-title primary-title>
                                        <div class="headline">
                                            <b>Projeto:</b>{{projeto.AnoProjeto}}{{projeto.Sequencial}} - {{projeto.NomeProjeto}}
                                        </div>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-form ref="form" v-model="valid">
                                            <label for="diligencia">Tipo de Diligencia *</label>
                                            <v-radio-group
                                                v-model="tpDiligencia"
                                                :rules="diligenciaRules"
                                                id="diligencia"
                                            >
                                                <v-radio color="success" label="Somente itens recusados" value="645"></v-radio>
                                                <v-radio color="success" label="Todos os itens orçamentários" value="174"></v-radio>
                                            </v-radio-group>
                                            <label for="solicitacao">Solicitação *</label>
                                            <!-- <v-textarea v-model="solicitacao"
                                                        id="solicitacao"
                                                        color="green"
                                                        height="100px"
                                                        :rules="solicitacaoRules"
                                                        required="required">
                                            </v-textarea> -->
                                            <EditorTexto
                                            >
                                            </EditorTexto>
                                        </v-form>
                                    </v-card-text>
                                    <v-card-actions class="justify-center">
                                        <v-btn
                                            color="primary"
                                            @click.native="enviarDiligencia()"
                                            :disabled="!valid"
                                            :to="{ name: 'AnalisePlanilha', params:{ id:this.idPronac }}"
                                        >
                                            Enviar
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import EditorTexto from '../components/EditorTexto';

    export default {
        components: {
            EditorTexto,
        },
        data() {
            return {
                tpDiligencia: '',
                solicitacao: '',
                idPronac: this.$route.params.id,
                valid: false,
                dialog: true,
                solicitacaoRules: [
                    v => !!v || 'Solicitação é obrigatório!',
                ],
                diligenciaRules: [
                    v => !!v || 'Tipo de diligencia é obrigatório!',
                ],
            };
        },
        methods:
        {
            ...mapActions({
                requestEmissaoParecer: 'avaliacaoResultados/getDadosEmissaoParecer',
                salvar: 'avaliacaoResultados/enviarDiligencia',
            }),
            getConsolidacao(id) {
                this.requestEmissaoParecer(id);
            },
            enviarDiligencia() {
                const data = {
                    idPronac: this.idPronac,
                    tpDiligencia: this.tpDiligencia,
                    solicitacao: this.solicitacao,
                };

                this.salvar(data);
            },
        },
        computed:
        {
            ...mapGetters({
                projeto: 'avaliacaoResultados/projeto',
            }),
        },
        created() {
            this.getConsolidacao(this.idPronac);
        },
    };
</script>
