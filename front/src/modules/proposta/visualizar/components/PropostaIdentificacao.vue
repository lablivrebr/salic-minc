<template>
    <div class="dados-basicos" v-if="proposta">
        <div class="card">
            <div class="card-content">
                <h5>Identifica&ccedil;&atilde;o</h5>
                <div class="row">
                    <div class="col s12 l3 m3" v-if="proposta.PRONAC">
                        <b>Pronac</b><br>
                        {{ proposta.PRONAC }}
                    </div>
                    <div class="col s12 l3 m3" v-if="proposta.idPreProjeto">
                        <b>N&ordm; da proposta</b><br>
                        {{ proposta.idPreProjeto }}
                    </div>
                    <div class="col s12 l6 m6">
                        <b>Nome Projeto</b><br>
                        <SalicTextoSimples :texto="proposta.NomeProjeto"></SalicTextoSimples>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-content">
                <h5>Informa&ccedil;&otilde;es complementares</h5>
                <div class="row">
                    <div class="col s12 l3 m3" v-if="proposta.Mecanismo">
                        <b>Mecanismo</b><br>
                        {{ mecanismo }}
                    </div>
                    <div class="col s12 l3 m3" v-if="proposta.DtInicioDeExecucao">
                        <b>In&iacute;cio Execu&ccedil;&atilde;o</b><br>
                        {{ dtInicioDeExecucao }}
                    </div>
                    <div class="col s12 l3 m3" v-if="proposta.DtFinalDeExecucao">
                        <b>Final Execu&ccedil;&atilde;o</b><br>
                        {{ dtFinalDeExecucao }}
                    </div>
                    <div class="col s12 l3 m3" v-if="proposta.stDataFixa">
                        <b>Dt. Fixa</b><br>
                        {{ stDataFixa }}
                    </div>
                </div>
                <div class="divider"></div>
                <div class="row">
                    <div class="col s12 l3 m3">
                        <b>Ag&ecirc;ncia banc&aacute;ria</b><br>
                        <SalicTextoSimples :texto="proposta.AgenciaBancaria"></SalicTextoSimples>
                    </div>
                    <div class="col s12 l3 m3" v-if="proposta.AreaAbrangencia">
                        <b>&Eacute; proposta audiovisual</b><br>
                        {{ areaAbrangencia }}
                    </div>
                    <div class="col s12 l3 m3" v-if="proposta.tpProrrogacao">
                        <b>Prorroga&ccedil;&atilde;o autom&aacute;tica</b><br>
                        {{ tpProrrogacao }}
                    </div>
                    <div class="col s12 l3 m3">
                        <b>Tipo de execu&ccedil;&atilde;o</b><br>
                        <SalicTextoSimples :texto="proposta.TipoExecucao"></SalicTextoSimples>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="proposta.NrAtoTombamento && proposta.NrAtoTombamento.length > 1" class="card">
            <div class="card-content">
                <h5>Tombamento</h5>
                <div class="row">
                    <div class="col s12 l4 m4">
                        <b>Nº Ato</b><br>
                        {{ proposta.NrAtoTombamento }}
                    </div>
                    <div class="col s12 l4 m4">
                        <b>Dt. Ato</b><br>
                        {{ dtAtoTombamento }}
                    </div>
                    <div class="col s12 l4 m4">
                        <b>Esfera</b><br>
                        {{ esferaTombamento }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import SalicTextoSimples from '@/components/SalicTextoSimples';
import moment from 'moment';
import { utils } from '@/mixins/utils';

export default {
    name: 'PropostaIdentificacao',
    props: {
        idpreprojeto: null,
        proposta: {
            type: Object,
            default() {
                return {};
            },
        },
    },
    mixins: [
        utils,
    ],
    components: {
        SalicTextoSimples,
    },
    methods: {
        labelMecanismo(valor) {
            switch (valor) {
            case '1':
            case 1:
                return 'Mecenato';
            default:
                return 'Inv\xE1lido';
            }
        },
        formatar_data(date) {
            date = moment(date).format('DD/MM/YYYY');

            return date;
        },
        labelEsfera(esfera) {
            let string;

            switch (esfera) {
            case '1':
                string = 'Municipal';
                break;
            case '2':
                string = 'Estadual';
                break;
            case '3':
                string = 'Federal';
                break;
            default:
                string = 'N\xE3o informada';
                break;
            }

            return string;
        },
    },
    computed: {
        stDataFixa() {
            return this.label_sim_ou_nao(this.proposta.stDataFixa);
        },
        areaAbrangencia() {
            return this.label_sim_ou_nao(this.proposta.AreaAbrangencia);
        },
        tpProrrogacao() {
            return this.label_sim_ou_nao(this.proposta.tpProrrogacao);
        },
        mecanismo() {
            return this.labelMecanismo(this.proposta.Mecanismo);
        },
        dtInicioDeExecucao() {
            return this.formatar_data(this.proposta.DtInicioDeExecucao);
        },
        dtFinalDeExecucao() {
            return this.formatar_data(this.proposta.DtFinalDeExecucao);
        },
        dtAtoTombamento() {
            return this.formatar_data(this.proposta.DtAtoTombamento);
        },
        esferaTombamento() {
            return this.labelEsfera(this.proposta.EsferaTombamento);
        },
    },
};
</script>
