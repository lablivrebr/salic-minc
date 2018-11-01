<template>
    <v-container grid-list-xl>
        <v-form ref="form" v-model="valid">
            <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                <v-toolbar dark color="green">
                        <v-toolbar-title>Diligenciar</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn dark flat
                                   @click.native="enviarDiligencia()"
                                   :disabled="!valid"
                                   :to="{ name: 'AnalisePlanilha', params:{ id:this.idPronac }}">Enviar</v-btn>
                            <v-btn dark flat :to="{ name: 'AnalisePlanilha', params:{ id:this.idPronac }}">Cancelar</v-btn>
                        </v-toolbar-items>
                </v-toolbar>
                <v-container grid-list-sm>
                    <v-layout row wrap>
                        <v-flex xs12 sm12 md12>
                            <h2>{{projeto.AnoProjeto}}{{projeto.Sequencial}} - {{projeto.NomeProjeto}}</h2>
                        </v-flex>
                    </v-layout>
                    <v-divider></v-divider>
                </v-container>
                <v-container grid-list>
                    <v-layout wrap align-center>
                        <v-flex>
                            <label for="diligencia">Tipo de Diligencia *</label>
                            <v-radio-group v-model="tpDiligencia"
                                           :rules="diligenciaRules"
                                           id="diligencia">
                                <v-radio color="success" label="Somente itens recusados" value="645"></v-radio>
                                <v-radio color="success" label="Todos os itens orçamentários" value="174"></v-radio>
                            </v-radio-group>
                        </v-flex>
                    </v-layout>
                    <v-flex>
                        <label for="solicitacao">Solicitação *</label>
                        <v-textarea v-model="solicitacao"
                                    id="solicitacao"
                                    color="green"
                                    height="100px"
                                    :rules="solicitacaoRules"
                                    required="required">
                        </v-textarea>
                    </v-flex>
                </v-container>
            </v-dialog>
        </v-form>
    </v-container>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';

    export default {
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
