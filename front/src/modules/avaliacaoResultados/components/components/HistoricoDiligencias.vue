<template>

    <v-layout row justify-center>
        <v-dialog v-model="dialog"
                  full-width
                  scrollable
                  fullscreen
        >
            <v-tooltip slot="activator" bottom>
                <v-btn slot="activator" flat icon @click.native="obterDiligencias(idPronac);">
                    <v-icon :color="status.color" class="material-icons">assignment_late</v-icon>
                </v-btn>
                <span>{{status.desc}} </span>
            </v-tooltip>

            <v-card>

                <v-toolbar dark color="green">
                    <v-btn icon dark @click.native="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Diligências Projeto: {{info.pronac}} - {{info.nomeProjeto}} </v-toolbar-title>
                </v-toolbar>

                <v-divider></v-divider>
                <v-card-text>
                    <v-timeline >
                        <v-timeline-item
                            v-for="(item, i) in sortByDate(diligencias.items)"
                            :key="i"
                            small
                        >
                             <span
                                 slot="opposite"
                                 :class="`headline font-weight-bold green--text`"
                             >
                                 Solicitado: {{item.dataSolicitacao | date}} </br>
                                 <span v-if="item.dataResposta">  Respondido: {{item.dataResposta | date}}</span>
                             </span>

                            <v-card color="green">
                                <v-card-title dark class="title white--text">{{item.tipoDiligencia}} <span v-if="item.stProrrogacao"> - {{item.stProrrogacao}}</span></v-card-title>
                                <v-card-text class="white text--primary">
                                     <v-expansion-panel>
                                        <v-expansion-panel-content v-if="item.Solicitacao">
                                            <div slot="header">Solicitação</div>
                                            <v-card>
                                                <v-card-text v-html="item.Solicitacao"></v-card-text>
                                            </v-card>
                                        </v-expansion-panel-content>
                                        <v-expansion-panel-content v-if="item.Resposta">
                                            <div slot="header" class="font-weight-regular">Resposta</div>
                                            <v-card>
                                                <v-card-text v-html="item.Resposta"></v-card-text>
                                            </v-card>
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
                                </v-card-text>
                            </v-card>

                        </v-timeline-item>
                    </v-timeline>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-layout>

</template>

<script>
    import Vue from 'vue';
    import { mapActions, mapGetters } from 'vuex';
    import Data from '../../../../filters/date';

    Vue.filter( 'date' , Data);

export default {
    name: 'HistoricoDiligencias',
    props: { idPronac: String , status: Object},
    data() {
        return {
            dialog: false,
            show: {
                solicitacao: false,
                resposta: false,
                index: '',
            },
            info: {
                nomeProjeto: '',
                pronac: '',
            },
        };
    },
    methods: {
        ...mapActions({
            obterDiligencias: 'avaliacaoResultados/obetDadosDiligencias',
        }),
        mostrarSolicitacao(index) {
            this.show.solicitacao = !this.show.solicitacao;
            this.show.index = index;
        },
        mostrarResposta(index) {
            this.show.resposta = !this.show.resposta;
            this.show.index = index;
        },
        sortByDate(list) {
            return _.orderBy(list, 'dataSolicitacao', 'desc');
        },
    },
    computed: {
        ...mapGetters({
            diligencias: 'avaliacaoResultados/diligenciasHistorico',
        }),
        setInfo(){
            if(Object.keys(this.diligencias).length > 0 ){
                this.info.nomeProjeto = this.diligencias.items[0].nomeProjeto;
                this.info.pronac = this.diligencias.items[0].pronac;
                return this.diligencias;
            }
            return 0;
        },
    },
    updated(){
      this.setInfo;
    }
};
</script>

