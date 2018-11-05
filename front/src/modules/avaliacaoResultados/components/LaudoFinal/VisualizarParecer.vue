<template>
    <v-container grid-list-xl>
        <v-form>
            <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                <v-card>
                    <v-toolbar dark color="green">
                        <v-btn icon dark :to="{ name: 'Laudo'}">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Avaliação de Resultados - Visualizar Parecer</v-toolbar-title>
                    </v-toolbar>
                    <v-container grid-list-sm>
                        <v-layout row wrap>
                            <v-flex xs12 sm12 md12>
                                <p><b>Projeto:</b> {{projeto.AnoProjeto}}{{projeto.Sequencial}} -- {{projeto.NomeProjeto}}</p>
                            </v-flex>
                            <v-flex xs12 sm12 md12 v-if="proponente.CgcCpf || proponente.Nome">
                                <p><b>Proponente:</b> {{proponente.CgcCpf | cnpjFilter}} -- {{proponente.Nome}}</p>
                            </v-flex>
                        </v-layout>
                        <v-divider></v-divider>
                    </v-container>
                    <h2 class="text-sm-center">Parecer de avaliação do cumprimento do objeto</h2>
                    <v-container grid-list-sm>
                        <v-layout wrap align-center>
                            <v-flex xs12 sm12 md12>
                                <div>
                                    <p><b>Manifestação: </b>{{parecerObjeto.dsManifestacaoObjeto}}</p>
                                </div>
                            </v-flex>
                            <v-flex xs12 sm12 md12>
                                <div>
                                    <h4>Parecer: </h4>
                                    <p v-html="parecerObjeto.dsParecerDeCumprimentoDoObjeto"></p>
                                </div>
                            </v-flex>
                        </v-layout>
                        <v-divider></v-divider>
                    </v-container>
                    <h2 class="text-sm-center">Parecer técnico de avaliação financeira</h2>
                    <v-container grid-list-sm>
                        <v-layout wrap align-center>
                            <v-flex xs12 sm12 md12>
                                <div>
                                    <p v-if="parecerTecnico.siManifestacao == 'A'"><b>Manifestação: </b> Aprovado</p>
                                    <p v-else-if="parecerTecnico.siManifestacao == 'P'"><b>Manifestação: </b> Aprovado com ressalva</p>
                                    <p v-else-if="parecerTecnico.siManifestacao == 'R'"><b>Manifestação: </b> Reprovado</p>
                                </div>
                            </v-flex>
                            <v-flex xs12 sm12 md12>
                                <div>
                                    <h4>Parecer: </h4>
                                    <p v-html="parecerTecnico.dsParecer"></p>
                                </div>
                            </v-flex>
                        </v-layout>
                        <v-divider></v-divider>
                    </v-container>
                </v-card>
            </v-dialog>
        </v-form>
    </v-container>
</template>

<script>
    import { mapGetters } from 'vuex';
    import cnpjFilter from '@/filters/cnpj';
    
    export default {
        name: 'VisualizarParecer',
        data() {
            return {
                dialog: true,
            };
        },
        computed: {
            ...mapGetters({
                proponente: 'avaliacaoResultados/proponente',
                parecerTecnico: 'avaliacaoResultados/parecer',
                parecerObjeto: 'avaliacaoResultados/objetoParecer',
                projeto: 'avaliacaoResultados/projeto',
            }),
        },
        filters: {
            cnpjFilter,
        },
    };
</script>
