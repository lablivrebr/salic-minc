<template>
    <v-container grid-list-md >
        <v-layout
            row
            justify-center>
            <v-dialog
                v-model="dialog"
                width="600"
                lazy>
                <v-btn
                    slot="activator"
                    flat
                    icon
                    color="green darken-4">
                    <v-icon>compare_arrows</v-icon>
                </v-btn>
                <v-flex>
                    <v-card >
                        <v-card-title primary-title>
                            Tipo Avaliação
                        </v-card-title>
                        <v-card-text>
                            <v-list
                                two-line
                                subheader>
                                <v-subheader>Valores</v-subheader>
                                <v-list-tile @click="">
                                    <v-list-tile-action>
                                        <v-icon color="indigo">attach_money</v-icon>
                                    </v-list-tile-action>

                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ tipoAvaliacao.vlAprovado }}</v-list-tile-title>
                                        <v-list-tile-sub-title>Aprovado</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile @click="">
                                    <v-list-tile-action>
                                        <v-icon color="indigo">attach_money</v-icon>
                                    </v-list-tile-action>
                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ tipoAvaliacao.vlCaptado }}</v-list-tile-title>
                                        <v-list-tile-sub-title>Captado</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile @click="">
                                    <v-list-tile-action>
                                        <v-icon color="indigo">attach_money</v-icon>
                                    </v-list-tile-action>
                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ tipoAvaliacao.vlComprovado }}</v-list-tile-title>
                                        <v-list-tile-sub-title>Comprovado</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile @click="">
                                    <v-list-tile-action>
                                        <v-icon color="indigo">attach_money</v-icon>
                                    </v-list-tile-action>
                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ tipoAvaliacao.qtComprovacao }}</v-list-tile-title>
                                        <v-list-tile-sub-title>Todos</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-divider inset/>
                                <v-subheader>Quantidade de Comprovantes por nivel de confiança</v-subheader>
                                <v-list-tile @click="">
                                    <v-list-tile-action>
                                        <v-icon color="indigo">bar_chart</v-icon>
                                    </v-list-tile-action>
                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ tipoAvaliacao.qtNC_90 }}</v-list-tile-title>
                                        <v-list-tile-sub-title>90%</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile @click="">
                                    <v-list-tile-action>
                                        <v-icon color="indigo">bar_chart</v-icon>
                                    </v-list-tile-action>

                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ tipoAvaliacao.qtNC_95 }}</v-list-tile-title>
                                        <v-list-tile-sub-title>95%</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile @click="">
                                    <v-list-tile-action>
                                        <v-icon color="indigo">bar_chart</v-icon>
                                    </v-list-tile-action>

                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ tipoAvaliacao.qtNC_99 }}</v-list-tile-title>
                                        <v-list-tile-sub-title>99%</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-divider inset/>
                                <v-subheader>Tipo de avaliação</v-subheader>
                            </v-list>
                            <v-radio-group
                                v-model="percentual"
                                :click="redirecionarEncaminhar()"
                                row
                                column>
                                <v-radio
                                    :value="100"
                                    color="green darken-4"
                                    label="Todos Comprovantes"/>
                                <v-radio
                                    :value="90"
                                    color="green darken-4"
                                    label="90%"/>
                                <v-radio
                                    :value="95"
                                    color="green darken-4"
                                    label="95%"/>
                                <v-radio
                                    :value="99"
                                    color="green darken-4"
                                    label="99%"/>
                            </v-radio-group>
                        </v-card-text>
                        <v-divider/>
                        <v-card-actions>
                            <v-spacer/>
                            <v-btn
                                :href="'/avaliacao-resultados/#/planilha/'+ this.idPronac"
                                dark
                                large
                                color="green darken-4">AVALIAR</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-dialog>
        </v-layout>
    </v-container>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import ModalTemplate from '@/components/modal';

export default {
    name: 'TipoAvaliacao',
    components: {
        ModalTemplate,
    },
    props: [
        'idPronac',
        'pronac',
        'nomeProjeto',
    ],
    data() {
        return {
            dialog: false,
            percentual: 100,
        };
    },
    methods: {
        ...mapActions({
            getTipo: 'avaliacaoResultados/getTipoAvaliacao',
            redirectLink: 'avaliacaoResultados/redirectLinkAvaliacaoResultadoTipo',
        }),

        getTipoAvaliacaoResultado(id) {
            this.getTipo(id);
        },
        redirecionarEncaminhar() {
            const data = { idPronac: this.idPronac, percentual: this.percentual };
            this.redirectLink(data);
        },
    },
    computed: {
        ...mapGetters({
            tipoAvaliacao: 'avaliacaoResultados/tipoAvaliacao',
            redirect: 'avaliacaoResultados/redirectLink',
        }),
    },
    watch: {
        dialog(val) {
            if (val) {
                this.getTipoAvaliacaoResultado(this.idPronac);
            }
        },
    },
};
</script>
