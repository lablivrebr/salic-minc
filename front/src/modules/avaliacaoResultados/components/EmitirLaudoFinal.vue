<template>
    <v-container grid-list-xl>
        <v-form ref="form" v-model="valid">
            <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                <v-toolbar dark color="green">
                        <v-btn icon dark :to="{ name: 'Laudo' }">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Avaliação Financeira - Emissão de Laudo Final</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn dark flat @click.native="salvarLaudoFinal()" :disabled="!valid">Salvar</v-btn>
                            <v-btn dark flat @click.native="finalizarLaudoFinal()" :disabled="!valid">Gerar Documento</v-btn>
                        </v-toolbar-items>
                </v-toolbar>
                <v-container grid-list-sm>
                    <v-layout row wrap>
                        <v-flex xs12 sm12 md12>
                            <p><b>Projeto:</b> {{projeto.AnoProjeto}}{{projeto.Sequencial}} - {{projeto.NomeProjeto}}</p>
                        </v-flex>
                        <v-flex xs12 sm12 md12>
                            <p><b>Proponente:</b> {{proponente.CgcCpf}} - {{proponente.Nome}}</p>
                        </v-flex>
                    </v-layout>
                    <v-divider></v-divider>
                </v-container>
                <v-container grid-list>
                    <v-layout wrap align-center>
                        <v-flex>
                            <v-select height="20px"
                                      :value="parecerLaudoFinal.manifestacao"
                                      @change="updateManifestacao"
                                      :rules="itemRules"
                                      :items="items"
                                      item-text="text"
                                      item-value="id"
                                      box
                                      label="Manifestação *"
                                      required="required">
                            </v-select>
                        </v-flex>
                    </v-layout>
                    <v-flex>
                        <v-textarea :value="parecerLaudoFinal.laudoTecnico"
                                    @input="updateParecer"
                                    :rules="parecerRules"
                                    color="deep-purple"
                                    label="Parecer *"
                                    height="200px"
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
                tipo: true,
                idPronac: this.$route.params.id,
                valid: false,
                dialog: true,
                itemRules: [
                    v => !!v || 'Tipo de manifestação é obrigatório!',
                ],
                parecerRules: [
                    v => !!v || 'Parecer é obrigatório!',
                    v => Object(v).length >= 10 || 'Parecer deve conter mais que 10 caracteres',
                ],
                items: [
                    {
                        id: 'A',
                        text: 'Aprovação',
                    },
                    {
                        id: 'R',
                        text: 'Reprovação',
                    },
                    {
                        id: 'P',
                        text: 'Aprovação com Ressalva',
                    },
                ],
            };
        },
        methods:
        {
            ...mapActions({
                modalOpen: 'modal/modalOpen',
                modalClose: 'modal/modalClose',
                requestEmissaoParecer: 'avaliacaoResultados/getDadosEmissaoParecer',
                salvar: 'avaliacaoResultados/salvarLaudoFinal',
                finalizar: 'avaliacaoResultados/finalizarLaudoFinal',
                getLaudoFinal: 'avaliacaoResultados/getLaudoFinal',
                atualizarManifestacao: 'avaliacaoResultados/atualizarManifestacao',
                atualizarParecer: 'avaliacaoResultados/atualizarParecer',
            }),
            getConsolidacao(id) {
                this.requestEmissaoParecer(id);
            },
            salvarLaudoFinal() {
                const data = {
                    idPronac: this.idPronac,
                    siManifestacao: this.characterManifestacao,
                    dsParecer: this.characterParecer,
                };

                this.salvar(data);
                /** Descomentar linha após migração da lista para o VUEJS */
                // this.dialog = false;
            },
            finalizarLaudoFinal() {
                const data = {
                    idPronac: this.idPronac,
                    siManifestacao: this.characterManifestacao,
                    dsParecer: this.characterParecer,
                    atual: 5,
                    proximo: 6,
                };

                this.finalizar(data);
                /** Descomentar linha após migração da lista para o VUEJS */
                // this.dialog = false;
            },
            updateManifestacao(characterManifestacao) {
                this.atualizarManifestacao(characterManifestacao);
            },
            updateParecer(characterParecer) {
                this.atualizarParecer(characterParecer);
            },
        },
        computed:
        {
            ...mapGetters({
                modalVisible: 'modal/default',
                proponente: 'avaliacaoResultados/proponente',
                projeto: 'avaliacaoResultados/projeto',
                parecerLaudoFinal: 'avaliacaoResultados/parecerLaudoFinal',
                characterManifestacao: 'avaliacaoResultados/characterManifestacao',
                characterParecer: 'avaliacaoResultados/characterParecer',
            }),
        },
        created() {
            this.getConsolidacao(this.idPronac);
            this.getLaudoFinal();
            this.atualizarManifestacao(this.parecerLaudoFinal.manifestacao);
            this.atualizarParecer(this.parecerLaudoFinal.laudoTecnico);
            // this.$refs.form.validate();
        },
    };
</script>
