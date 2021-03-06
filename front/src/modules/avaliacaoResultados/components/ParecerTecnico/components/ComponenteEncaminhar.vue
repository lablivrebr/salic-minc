<template>
    <v-dialog
        v-model="dialog"
        width="750"
    >
        <v-tooltip
            slot="activator"
            bottom>
            <v-btn
                slot="activator"
                flat
                icon
            >
                <v-icon >assignment_ind</v-icon>
            </v-btn>
            <span>Encaminhar Projeto</span>
        </v-tooltip>
        <v-card>
            <v-form
                ref="form"
                v-model="form"
            >
                <v-card-title
                    class="headline primary"
                    primary-title
                >
                    <span class="white--text">
                        Encaminhamento do projeto
                    </span>
                </v-card-title>
                <v-card-text>
                    <v-list
                        three-line
                        subheader>
                        <v-subheader>
                            <h4 class="headline mb-0 grey--text text--darken-3">
                                {{ pronac }} - {{ nomeProjeto }}
                            </h4>
                        </v-subheader>
                        <v-divider/>
                        <v-subheader>
                            Informações do encaminhamento
                        </v-subheader>
                        <v-list-tile v-if="usuarioLogado.usu_org_max_superior === '251' ">
                            <v-list-tile-action>
                                <v-icon color="green">group</v-icon>
                            </v-list-tile-action>
                            SEFIC/DEIPC/CGARE
                        </v-list-tile>
                        <v-list-tile v-if="usuarioLogado.usu_org_max_superior === '160' ">
                            <v-list-tile-action>
                                <v-icon color="green">group</v-icon>
                            </v-list-tile-action>
                            SAV/CGAV/CEP
                        </v-list-tile>
                        <v-select
                            v-model="destinatarioEncaminhamento"
                            :items="dadosDestinatarios"
                            :rules="[rules.required]"
                            height="10px"
                            solo
                            single-line
                            label="-- Escolha um técnico  --"
                            item-text="usu_nome"
                            item-value="usu_codigo"
                            prepend-icon="perm_identity"
                        />
                        <v-textarea
                            ref="justificativa"
                            v-model="justificativa"
                            :rules="[rules.required]"
                            label="Justificativa de encaminhamento para análise"
                            prepend-icon="create"
                            color="green"
                            autofocus
                            height="150px"
                        />
                    </v-list>
                </v-card-text>
                <v-divider/>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn
                        color="red"
                        flat
                        @click="dialog = false, $refs.form.reset()"
                    >
                        Fechar
                    </v-btn>
                    <v-btn
                        :disabled="!form"
                        color="primary"
                        flat
                        @click="enviarEncaminhamento"
                    >
                        Encaminhar
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'Encaminhar',
    props: {
        idPronac: {
            type: String,
            default: '',
            validator(value) {
                return value !== '';
            },
        },
        nomeProjeto: {
            type: String,
            default: '',
        },
        pronac: {
            type: String,
            default: '',
        },
    },
    data() {
        return {
            dialog: false,
            rules: {
                required: v => !!v,
            },
            destinatarioEncaminhamento: null,
            justificativa: null,
            form: null,
        };
    },
    computed: {
        ...mapGetters({
            dadosDestinatarios: 'avaliacaoResultados/dadosDestinatarios',
            usuarioLogado: 'autenticacao/getUsuario',
        }),
    },
    watch: {
        dialog(val) {
            if (!val) {
                this.$refs.form.reset();
            } else {
                const orgaoSuperior = this.$store.getters['autenticacao/getUsuario'].usu_org_max_superior;
                this.obterDestinatarios(orgaoSuperior);
            }
        },
    },
    methods: {
        ...mapActions({
            obterDestinatarios: 'avaliacaoResultados/obterDestinatarios',
            encaminharParaTecnico: 'avaliacaoResultados/encaminharParaTecnico',
            obterDadosTabelaTecnico: 'avaliacaoResultados/obterDadosTabelaTecnico',
            projetosFinalizados: 'avaliacaoResultados/projetosFinalizados',
            distribuir: 'avaliacaoResultados/projetosParaDistribuir',
        }),
        enviarEncaminhamento() {
            this.encaminharParaTecnico({
                atual: 4,
                proximo: 5,
                idPronac: this.idPronac,
                idOrgaoDestino: 1,
                idAgenteDestino: this.destinatarioEncaminhamento,
                cdGruposDestino: 1,
                dtFimEncaminhamento: '2015-09-25 10:38:41',
                idSituacaoEncPrestContas: 1,
                idSituacao: 1,
                dsJustificativa: this.justificativa,
            });

            this.dialog = false;
            this.$refs.form.reset();

            this.projetosFinalizados({ estadoid: 6 });
            this.obterDadosTabelaTecnico({ estadoid: 5 });
        },
    },
};
</script>
