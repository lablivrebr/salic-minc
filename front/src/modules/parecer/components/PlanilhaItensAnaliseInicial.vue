<template>
    <div class="itens">
        <v-data-table
            :headers="headers"
            :items="table"
            :rows-per-page-items="[-1]"
            :loading="loading"
            item-key="idPlanilhaProjeto"
            class="elevation-1"
            hide-actions
        >
            <v-progress-linear
                slot="progress"
                color="blue"
                indeterminate
            />
            <template
                slot="items"
                slot-scope="props"
            >
                <tr
                    :class="obterClasseItem(props.item, 'stCustoPraticadoParc')"
                    :style="obterEstiloItem(props.item)"
                    @click="props.expanded = editarItem(props)"
                >
                    <td class="text-xs-center">
                        {{ props.item.Seq }}
                    </td>
                    <td class="text-xs-left">
                        <a
                            v-if="props.item.isDisponivelParaAnalise===true"
                            href="javascript:void(0);"
                        >
                            {{ props.item.Item }}
                        </a>
                        <span v-else>
                            {{ props.item.Item }}
                        </span>
                    </td>
                    <td class="text-xs-center">
                        {{ props.item.UnidadeProjeto }}
                    </td>
                    <td class="text-xs-center">
                        {{ props.item.diasparc }}
                    </td>
                    <td class="text-xs-center">
                        {{ props.item.quantidadeparc }}
                    </td>
                    <td class="text-xs-center">
                        {{ props.item.ocorrenciaparc }}
                    </td>
                    <td class="text-xs-right">
                        <v-tooltip
                            v-if="validarValorPraticado(props.item).valid === false"
                            bottom
                        >
                            <v-badge
                                slot="activator"
                                right
                                color="orange darken-4"
                            >
                                <span slot="badge">
                                    !
                                </span>
                                {{ props.item.valorUnitarioparc | filtroFormatarParaReal }}
                            </v-badge>
                            <span> {{ validarValorPraticado(props.item).message }}</span>
                        </v-tooltip>
                        <span v-else>
                            {{ props.item.valorUnitarioparc | filtroFormatarParaReal }}
                        </span>
                    </td>
                    <td class="text-xs-right">
                        {{ props.item.VlSugeridoParecerista | filtroFormatarParaReal }}
                    </td>
                </tr>
            </template>
            <template
                slot="expand"
                slot-scope="props"
            >
                <v-layout
                    wrap
                    column
                    class="blue-grey lighten-3 pa-2"
                >
                    <v-card>
                        <v-card-title class="py-1 blue-grey lighten-1 white--text">
                            <span class="title">
                                Editando item: {{ props.item.Item }}
                            </span>

                            <v-spacer />

                            <v-menu
                                bottom
                                left
                            >
                                <v-btn
                                    slot="activator"
                                    dark
                                    icon
                                >
                                    <v-icon>more_vert</v-icon>
                                </v-btn>

                                <v-list>
                                    <v-list-tile
                                        @click="zerarItem()"
                                    >
                                        <v-list-tile-action style="min-width: 30px">
                                            <v-icon>money_off</v-icon>
                                        </v-list-tile-action>
                                        <v-list-tile-title>Zerar valores</v-list-tile-title>
                                    </v-list-tile>
                                    <v-list-tile
                                        @click="restaurarItem()"
                                    >
                                        <v-list-tile-action style="min-width: 30px">
                                            <v-icon>restore</v-icon>
                                        </v-list-tile-action>
                                        <v-list-tile-title>Restaurar valores</v-list-tile-title>
                                    </v-list-tile>
                                </v-list>
                            </v-menu>
                        </v-card-title>
                        <v-divider />
                        <v-card-text>
                            <v-alert
                                class="py-2"
                                :value="isCustoPraticado(props.item)"
                                type="warning"
                                dismissible
                                outline
                                color="orange darken-4"
                            >
                                {{ obterMensagemCustoPraticado(props.item) }}
                            </v-alert>
                            <div
                                v-if="isCustoPraticado(props.item)"
                                class="text-xs-center"
                            >
                                <v-btn
                                    color="blue-grey"
                                    class="white--text"
                                    @click="buscarMediana(itemEmEdicao)"
                                >
                                    Ver mediana
                                    <v-icon
                                        right
                                        dark
                                    >
                                        monetization_on
                                    </v-icon>
                                </v-btn>
                            </div>
                            <v-layout
                                row
                                wrap
                                px-3
                            >
                                <v-flex
                                    xs12
                                    md12
                                >
                                    <v-expansion-panel
                                        v-model="openValorSolicitado"
                                        class="mx-auto"
                                    >
                                        <v-expansion-panel-content style="background: #f3f3f3">
                                            <span slot="header">
                                                <h3>Valores solicitados</h3>
                                            </span>
                                            <v-layout
                                                row
                                                wrap
                                                pa-3
                                            >
                                                <v-flex
                                                    xs12
                                                    md2
                                                >
                                                    <b>Unidade</b>
                                                    <div>{{ itemEmEdicao.UnidadeProposta }}</div>
                                                </v-flex>
                                                <v-flex
                                                    xs12
                                                    md1
                                                >
                                                    <b>Dias</b>
                                                    <div>{{ itemEmEdicao.diasprop }}</div>
                                                </v-flex>
                                                <v-flex
                                                    xs12
                                                    md1
                                                >
                                                    <b>Qtd.</b>
                                                    <div>{{ itemEmEdicao.quantidadeprop }}</div>
                                                </v-flex>
                                                <v-flex
                                                    xs12
                                                    md2
                                                >
                                                    <b>Ocorrência</b>
                                                    <div>{{ itemEmEdicao.ocorrenciaprop }}</div>
                                                </v-flex>
                                                <v-flex
                                                    xs12
                                                    md2
                                                >
                                                    <b>Vl. Unitário (R$)</b>
                                                    <div>{{ itemEmEdicao.valorUnitarioprop | filtroFormatarParaReal }}</div>
                                                </v-flex>
                                                <v-flex
                                                    xs12
                                                    md2
                                                >
                                                    <b>Vl. Solicitado (R$)</b>
                                                    <div>{{ itemEmEdicao.VlSolicitado | filtroFormatarParaReal }}</div>
                                                </v-flex>
                                                <v-flex
                                                    xs10
                                                    md10
                                                >
                                                    <b>Justificativa</b>
                                                    <div
                                                        v-html="itemEmEdicao.justificitivaproponente"
                                                    />
                                                </v-flex>
                                            </v-layout>
                                        </v-expansion-panel-content>
                                    </v-expansion-panel>
                                </v-flex>
                            </v-layout>
                            <v-form
                                ref="form"
                                v-model="valid"
                                lazy-validation
                            >
                                <v-container fluid>
                                    <v-layout
                                        row
                                        wrap
                                    >
                                        <v-flex
                                            xs12
                                            md12
                                        >
                                            <h3>Valores parecerista</h3>
                                        </v-flex>
                                        <v-flex
                                            xs12
                                            md2
                                        >
                                            <v-select
                                                v-model="comboUnidade"
                                                :items="unidades"
                                                label="Unidade"
                                                item-text="Descricao"
                                                item-value="idUnidade"
                                                return-object
                                            />
                                        </v-flex>
                                        <v-flex
                                            xs12
                                            md1
                                        >
                                            <v-text-field
                                                v-model="itemEmEdicao.diasparc"
                                                :rules="[rules.required, rules.menorQueZero]"
                                                type="number"
                                                label="Dias"
                                                required
                                            />
                                        </v-flex>
                                        <v-flex
                                            xs12
                                            md1
                                        >
                                            <v-text-field
                                                v-model="itemEmEdicao.quantidadeparc"
                                                :rules="[rules.required, rules.menorQueZero]"
                                                type="number"
                                                label="Qtd."
                                                required
                                            />
                                        </v-flex>
                                        <v-flex
                                            xs12
                                            md2
                                        >
                                            <v-text-field
                                                v-model="itemEmEdicao.ocorrenciaparc"
                                                :rules="[rules.required, rules.menorQueZero]"
                                                type="number"
                                                label="Ocorrência"
                                                required
                                            />
                                        </v-flex>
                                        <v-flex
                                            xs12
                                            md2
                                        >
                                            <SalicInputValor
                                                v-model="itemEmEdicao.valorUnitarioparc"
                                                :rules="[rules.required, rules.menorQueZero]"
                                                label="Vl. Unitário (R$)"
                                            />
                                        </v-flex>
                                        <v-flex
                                            xs12
                                            md2
                                        >
                                            <v-text-field
                                                :value="valorSugerido | filtroFormatarParaReal"
                                                :rules="valorSugeridoRules"
                                                label="Vl. Sugerido"
                                                readonly
                                            />
                                        </v-flex>
                                    </v-layout>
                                    <v-layout
                                        row
                                        wrap
                                    >
                                        <v-flex
                                            xs10
                                            md10
                                        >
                                            <v-textarea
                                                ref="justificativa"
                                                v-model="itemEmEdicao.dsJustificativaParecerista"
                                                :rules="justificativaRules"
                                                label="Justificativa para alterar o item"
                                                name="justificativa"
                                                counter="500"
                                            />
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <v-container
                                    grid-list-md
                                    text-xs-center
                                >
                                    <v-btn
                                        :disabled="!valid"
                                        :loading="loading"
                                        color="primary"
                                        @click="salvarAvaliacao(itemEmEdicao)"
                                    >
                                        <v-icon
                                            left
                                            dark
                                        >
                                            save
                                        </v-icon>
                                        Salvar
                                    </v-btn>
                                    <v-btn
                                        @click="props.expanded = !props.expanded"
                                    >
                                        <v-icon
                                            left
                                            dark
                                        >
                                            clear
                                        </v-icon>
                                        Cancelar
                                    </v-btn>
                                </v-container>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-layout>
            </template>
            <template slot="footer">
                <tr
                    v-if="table && Object.keys(table).length > 0"
                    style="opacity: 0.5"
                >
                    <td colspan="7">
                        <b>Totais</b>
                    </td>
                    <td class="text-xs-right">
                        <b>{{ obterValorSugeridoTotalParecer(table) | formatarParaReal }}</b>
                    </td>
                </tr>
            </template>
        </v-data-table>
        <s-planilha-dialog-dados-mediana v-model="modalMediana" />
    </div>
</template>

<script>
import MxPlanilhas from '@/mixins/planilhas';
import MxPlanilhaParecer from '../mixins/planilhaParecer';
import { utils } from '@/mixins/utils';
import { mapActions, mapGetters } from 'vuex';
import SalicInputValor from '@/components/SalicInputValor';
import SPlanilhaDialogDadosMediana from '@/components/Planilha/PlanilhaDialogDadosMediana';

export default {
    name: 'PlanilhaItensAnaliseInicial',
    components: { SPlanilhaDialogDadosMediana, SalicInputValor },
    mixins: [MxPlanilhas, MxPlanilhaParecer, utils],
    props: {
        table: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            valid: false,
            expand: false,
            loading: false,
            openValorSolicitado: 1,
            maxChars: 500,
            minChars: 10,
            messageAlert: '',
            validacao: {},
            headers: [
                { text: '#', align: 'center', value: 'Seq' },
                { text: 'Item', align: 'left', value: 'Item' },
                { text: 'Unidade', align: 'left', value: 'UnidadeProjeto' },
                { text: 'Dias', align: 'center', value: 'diasparc' },
                { text: 'Qtde', align: 'center', value: 'quantidadeparc' },
                { text: 'Ocor.', align: 'center', value: 'ocorrenciaparc' },
                { text: 'Vl. Unitário', align: 'right', value: 'valorUnitarioparc' },
                { text: 'Valor Sugerido', align: 'left', value: 'VlSugeridoParecerista' },
            ],
            itemEmEdicao: {
                VlSugeridoParecerista: '',
                ocorrenciaparc: 0,
                quantidadeparc: 0,
                valorUnitarioparc: 0,
                diasparc: 0,
            },
            modalMediana: false,
            comboUnidade: {},
            rules: {
                required: v => !!v || v === 0 || 'Campo obrigatório',
                menorQueZero: v => this.converterParaMoedaAmericana(v) >= 0 || 'Não pode ser menor que zero',
            },
        };
    },
    computed: {
        ...mapGetters({
            unidades: 'planilha/obterUnidades',
        }),
        valorSugerido() {
            return (this.itemEmEdicao.valorUnitarioparc
                * this.itemEmEdicao.ocorrenciaparc
                * this.itemEmEdicao.quantidadeparc
            );
        },
        valorSugeridoRules() {
            return [v => (!!v && parseFloat(this.valorSugerido).toFixed(2)) <= parseFloat(this.itemEmEdicao.VlSolicitado).toFixed(2)
                || 'O valor sugerido não pode ser maior que o valor solicitado.'];
        },
        justificativaRules() {
            const rules = [];
            rules.push(v => !!v || 'Justificativa &eacute; obrigat&oacute;ria');
            rules.push(v => v.length <= this.maxChars || `O parecer não pode ultrapassar ${this.maxChars} caracteres`);
            rules.push(v => v.length >= this.minChars || `O parecer deve ter no mínimo ${this.minChars} caracteres`);
            return rules;
        },
    },
    watch: {
        itemEmEdicao: {
            handler(val) {
                this.validarValorPraticado(val);
            },
            deep: true,
        },
        comboUnidade(value) {
            this.itemEmEdicao.idUnidade = value.idUnidade;
            this.itemEmEdicao.UnidadeProjeto = value.Descricao;
        },
        valorSugerido(val) {
            this.itemEmEdicao.VlSugeridoParecerista = val;
        },
    },
    methods: {
        ...mapActions({
            salvarAvaliacaoItem: 'parecer/salvarAvaliacaoItem',
            obterMediana: 'planilha/obterMediana',
        }),
        editarItem(props) {
            if (props.item.isDisponivelParaAnalise === false) {
                return false;
            }

            this.itemEmEdicao = Object.assign(this.itemEmEdicao, props.item);
            this.atualizarCombo();

            return !props.expanded;
        },
        salvarAvaliacao(avaliacao) {
            if (!this.$refs.form.validate()) {
                return false;
            }

            this.loading = true;
            this.salvarAvaliacaoItem(avaliacao).finally(() => {
                this.loading = false;
            });

            return true;
        },
        validarValorPraticado(item) {
            let validacao = {
                valid: true,
                message: '',
            };

            if (this.isCustoPraticado(item) && (item.dsJustificativaParecerista === null
                || item.dsJustificativaParecerista.length < this.minChars)) {
                validacao = {
                    valid: false,
                    message: this.obterMensagemCustoPraticado(item),
                };
            }

            return validacao;
        },
        isCustoPraticado(item) {
            return (item.stCustoPraticadoParc === true
                || parseInt(item.stCustoPraticadoParc, 10) === 1);
        },
        obterMensagemCustoPraticado(item) {
            return `O valor unitário (${this.formatarParaReal(item.valorUnitarioprop)}) deste item para ${item.Cidade},
                    ultrapassa o valor aprovado por este orgão. Faça uma nova sugestão de valor ou justifique`;
        },
        buscarMediana(item) {
            this.modalMediana = true;
            this.obterMediana({
                idProduto: item.idProduto,
                idUnidade: item.idUnidade,
                idPlanilhaItem: item.idPlanilhaItem,
                idUfDespesa: item.idUfDespesa,
                idMunicipioDespesa: item.idMunicipioDespesa,
            });
        },
        atualizarCombo() {
            this.comboUnidade = {
                idUnidade: this.itemEmEdicao.idUnidade,
                Descricao: this.itemEmEdicao.UnidadeProjeto,
                Sigla: '',
            };
        },
        zerarItem() {
            this.itemEmEdicao.idUnidade = 1;
            this.itemEmEdicao.ocorrenciaparc = 0;
            this.itemEmEdicao.quantidadeparc = 0;
            this.itemEmEdicao.diasparc = 0;
            this.itemEmEdicao.valorUnitarioparc = 0;
            this.atualizarCombo();
        },
        restaurarItem() {
            this.itemEmEdicao.idUnidade = this.itemEmEdicao.Unidade;
            this.itemEmEdicao.ocorrenciaparc = this.itemEmEdicao.ocorrenciaprop;
            this.itemEmEdicao.quantidadeparc = this.itemEmEdicao.quantidadeprop;
            this.itemEmEdicao.diasparc = this.itemEmEdicao.diasprop;
            this.itemEmEdicao.valorUnitarioparc = this.itemEmEdicao.valorUnitarioprop;
            this.atualizarCombo();
        },
    },
};
</script>
