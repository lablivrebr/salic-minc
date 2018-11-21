<template>
        <v-dialog v-model="dialog"
                  scrollable
                  fullscreen
        >
            <v-tooltip slot="activator" bottom>
                <v-btn slot="activator" flat icon @click.native="obterDiligencias(obj.idPronac);">
                    <v-icon :color="statusDiligencia(obj).color" :change="statusDiligencia(obj).color" class="material-icons">assignment_late</v-icon>
                </v-btn>
                <span>{{statusDiligencia(obj).desc}} </span>
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
                            v-for="(item, i) in sortByDate"
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

</template>

<script>
import Vue from 'vue';
import _ from 'lodash';
import { mapActions, mapGetters } from 'vuex';
import Data from '../../../../filters/date';

Vue.filter('date', Data);

export default {
    name: 'HistoricoDiligencias',
    props: { obj: Object },
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
        statusDiligencia(obj) {
            const prazo = this.prazoResposta(obj);
            let status = {
                color: 'grey',
                desc: 'Histórico Diligências',
            };
            const prazoPadrao = 40;
            // diligenciado
            if (obj.DtSolicitacao && obj.DtResposta === '' &&
                prazo <= prazoPadrao && obj.stEnviado === 'S') {
                status = { color: 'yellow', desc: 'Diligenciado' };
                return status;
                // diligencia não respondida
            } else if (obj.DtSolicitacao && obj.DtResposta === '' && prazo > prazoPadrao) {
                status = { color: 'red', desc: 'Diligencia não respondida' };
                return status;
                // diligencia respondida com ressalvas
            } else if (obj.DtSolicitacao && obj.DtResposta !== '') {
                if (obj.stEnviado === 'N' && prazo > prazoPadrao) {
                    status = { color: 'red', desc: 'Diligencia não respondida' };
                    return status;
                }
                if (obj.stEnviado === 'N' && prazo < prazoPadrao) {
                    status = { color: 'yellow', desc: 'Diligenciado' };
                    return status;
                }

                status = { color: 'blue', desc: 'Diligencia respondida' };
                return status;
            }
            status = { color: 'green', desc: 'A Diligenciar' };
            return status;
        },
        prazoResposta(obj) {
            /**
             If (notempty dtSolicitação){
             Calculo do Prazo

             prazo = date.now() - datainicial(dtSolicitacao);

              converter.dias(prazo)

             -> Para casos de de ser contagem regressiva.
             if (key boolean (bln_descrescente) ){
              prazo = prazoPadrao - prazo(do calculo acima);
             }

             if(prazo > 0) { prazo positivo
              return prazo
             } else if( prazo <= 0) { prazo negativo
                return 0
             } else {        para prazo de resposta igual ao padrão
              return -1
             }
             }else {
             return 0
             }
             */

            let now;
            let timeDiff;
            let prazo;
            if (typeof obj.DtSolicitacao !== 'undefined') {
                now = Date.now();
                timeDiff = Math.abs(now - new Date(obj.DtSolicitacao));
                prazo = Math.ceil(timeDiff / (1000 * 3600 * 24));
                // console.info(new Date().toLocaleDateString(undefined, {
                //     day: '2-digit',
                //     month: '2-digit',
                //     year: 'numeric'
                // }) + " - "+ new Date(obj.DtSolicitacao).toLocaleDateString(undefined, {
                //     day: '2-digit',
                //     month: '2-digit',
                //     year: 'numeric'
                // }) + " = "+ prazo);

                if (prazo > 0) {
                    // prazo positivo
                    return prazo;
                }
                if (prazo <= 0) {
                    // prazo negativo
                    return 0;
                }
                if (prazo === 40) {
                    // para prazo de resposta igual ao padrão
                    return -1;
                }
            }
            return null;
        },
        setInfo() {
            if (Object.keys(this.diligencias).length > 0) {
                this.info.nomeProjeto = this.diligencias.items[0].nomeProjeto;
                this.info.pronac = this.diligencias.items[0].pronac;
                return this.info;
            }
            return this.info;
        },
    },
    computed: {
        ...mapGetters({
            diligencias: 'avaliacaoResultados/diligenciasHistorico',
        }),

        sortByDate() {
            return _.orderBy(this.diligencias.items, 'dataSolicitacao', 'desc');
        },
    },
    updated() {
        this.setInfo();
    },
};
</script>

