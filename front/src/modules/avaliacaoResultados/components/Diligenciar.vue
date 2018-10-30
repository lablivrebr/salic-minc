<template>
    <v-container grid-list-xl>
        <v-form ref="form">
            <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                <v-toolbar dark color="green">
                        <v-toolbar-title>Diligenciar</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn v-if="tpDiligencia && solicitacao" dark flat @click.native="enviarDiligencia()">Enviar</v-btn>
                            <v-btn v-else dark flat disabled>Enviar</v-btn>
                            <v-btn dark flat :to="{ name: 'AnalisePlanilha', params:{ id:this.$route.params.id }}">Cancelar</v-btn>
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
                    <v-layout row wrap>
                        <v-flex>
                            <h3>Tipo de Diligencia *</h3>
                        </v-flex>
                    </v-layout>
                    <v-divider></v-divider>
                    <v-layout wrap align-center>
                        <v-flex>
                            <v-radio-group v-model="tpDiligencia">
                                <v-radio color="success" label="Somente itens recusados" value="645"></v-radio>
                                <v-radio color="success" label="Todos os itens orçamentários" value="174"></v-radio>
                            </v-radio-group>
                        </v-flex>
                    </v-layout>
                        <v-flex>
                            <v-textarea v-model="solicitacao"
                                        label="Solicitação *"
                                        color="green"
                                        height="50px"
                                        :rules="solicitacaoRules">
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
                dialog: true,
                solicitacaoRules: [
                    v => !!v || 'Solicitação é obrigatório!',
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
                this.dialog = false;
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
