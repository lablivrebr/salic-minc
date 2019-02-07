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
                indeterminate/>
            <template
                slot="items"
                slot-scope="props"
            >
                <tr
                    :class="obterClasseItem(props.item)"
                    :style="obterEstiloItem(props.item)"
                    @click="props.expanded = editarItem(props)"
                >
                    <td class="text-xs-center">
                        <v-tooltip
                            v-if="validarLinha(props.item).valid === false"
                            bottom>
                            <v-badge
                                slot="activator"
                                left
                                color="red"
                            >
                                <span slot="badge">!</span>
                                {{ props.item.Seq }}
                            </v-badge>
                            <span> {{ validarLinha(props.item).message }}</span>
                        </v-tooltip>
                        <span v-else> {{ props.item.Seq }} </span>
                    </td>
                    <td class="text-xs-left">
                        <a
                            v-if="props.item.isDisponivelParaAnalise===true"
                            href="javascript:void(0);">
                            {{ props.item.Item }}
                        </a>
                        <span v-else>{{ props.item.Item }}</span>
                    </td>
                    <td class="text-xs-center">{{ props.item.UnidadeProjeto }}</td>
                    <td class="text-xs-center">{{ props.item.diasparc }}</td>
                    <td class="text-xs-center">{{ props.item.quantidadeparc }}</td>
                    <td class="text-xs-center">{{ props.item.ocorrenciaparc }}</td>
                    <td class="text-xs-right">{{ props.item.valorUnitarioparc | filtroFormatarParaReal }}</td>
                    <td class="text-xs-right">{{ props.item.VlSugeridoParecerista | filtroFormatarParaReal }}</td>
                    <td
                        class="text-xs-left"
                        width="30%"
                        v-html="$options.filters.filtroDiminuirTexto(props.item.dsJustificativaParecerista, 40)"/>
                </tr>
            </template>
            <template
                slot="expand"
                slot-scope="props">
                <v-layout
                    wrap
                    column
                    class="blue-grey lighten-5 pa-2">
                    <v-card>
                        <v-card-title class="py-1">
                            <h3>Editando item: {{ itemEmEdicao.Item }} </h3>
                        </v-card-title>
                        <v-divider/>
                        <v-card-text>
                            <v-alert
                                :value="messageAlert.length > 0"
                                type="warning"
                            >
                                {{ messageAlert }}
                            </v-alert>
                            <v-form
                                ref="form"
                                v-model="valid"
                                lazy-validation>
                                <v-container fluid>
                                    <v-layout
                                        row
                                        wrap
                                        style="background: #f3f3f3"
                                    >
                                        <v-flex
                                            xs12
                                            md12
                                        >
                                            <h3>Valores Solicitados</h3>
                                        </v-flex>
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
                                            md10>
                                            <b>Justificativa</b>
                                            <div
                                                v-html="itemEmEdicao.justificitivaproponente"
                                            />
                                        </v-flex>
                                    </v-layout>
                                    <v-divider class="mb-3"/>
                                    <v-layout
                                        row
                                        wrap>
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
                                        >    <SalicInputValor
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
                                        wrap>
                                        <v-flex
                                            xs10
                                            md10>
                                            <v-textarea
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
                                    grid-list-xs
                                    text-xs-center
                                    ma-0
                                    pa-0>
                                    <v-btn
                                        :disabled="!valid"
                                        :loading="loading"
                                        color="primary"
                                        @click="salvarAvaliacao(itemEmEdicao)"
                                    >
                                        <v-icon
                                            left
                                            dark>save</v-icon>
                                        Salvar
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
                    style="opacity: 0.5">
                    <td colspan="7"><b>Totais</b></td>
                    <td class="text-xs-right"><b>{{ obterValorSugeridoTotalParecer(table) | formatarParaReal }}</b></td>
                    <td/>
                </tr>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import MxPlanilhas from '@/mixins/planilhas';
import MxPlanilhaParecer from '../mixins/planilhaParecer';
import { utils } from '@/mixins/utils';
import { mapActions, mapGetters } from 'vuex';
import SalicInputValor from '@/components/SalicInputValor';

export default {
    components: { SalicInputValor },
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
            maxChars: 500,
            minChars: 10,
            messageAlert: '',
            headers: [
                { text: '#', align: 'center', value: 'Seq' },
                { text: 'Item', align: 'left', value: 'Item' },
                { text: 'Unidade', align: 'left', value: 'UnidadeProjeto' },
                { text: 'Dias', align: 'center', value: 'diasparc' },
                { text: 'Qtde', align: 'center', value: 'quantidadeparc' },
                { text: 'Ocor.', align: 'center', value: 'ocorrenciaparc' },
                { text: 'Vl. Unitário', align: 'right', value: 'valorUnitarioparc' },
                { text: 'Valor Sugerido', align: 'left', value: 'VlSugeridoParecerista' },
                { text: 'Just. Parecerista', align: 'left', value: 'dsJustificativaParecerista' },
            ],
            itemEmEdicao: {
                VlSugeridoParecerista: '',
                ocorrenciaparc: 0,
                quantidadeparc: 0,
                valorUnitarioparc: 0,
                diasparc: 0,
            },
            comboUnidade: {},
            rules: {
                required: v => !!v || 'Campo obrigatório',
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
            return [v => (!!v && this.valorSugerido) <= this.itemEmEdicao.VlSolicitado
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
                const validacao = this.validarLinha(val);
                this.messageAlert = '';
                if (validacao.valid === false) {
                    this.messageAlert = validacao.message;
                }
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
        }),
        editarItem(props) {
            if (props.item.isDisponivelParaAnalise === false) {
                return false;
            }

            this.itemEmEdicao = Object.assign(this.itemEmEdicao, props.item);
            this.comboUnidade = {
                idUnidade: this.itemEmEdicao.idUnidade,
                Descricao: this.itemEmEdicao.UnidadeProjeto,
                Sigla: '',
            };

            return !props.expanded;
        },
        salvarAvaliacao(avaliacao) {
            if (!this.$refs.form.validate()) {
                return false;
            }

            this.loading = true;
            this.salvarAvaliacaoItem(avaliacao).then(() => {
                this.loading = false;
            }).catch(() => {
                this.loading = false;
            });

            return true;
        },
        validarLinha(row) {
            const isCustoPraticado = (row.stCustoPraticado === true
                || row.stCustoPraticado === '1'
                || row.stCustoPraticado === 1);
            let validacao = {
                valid: true,
                message: '',
            };

            if (isCustoPraticado && (row.dsJustificativaParecerista === null
                || row.dsJustificativaParecerista.length < this.minChars)) {
                validacao = {
                    valid: false,
                    message: 'Proponente ultrapassou a mediana, altere o valor solicitado ou justifique o valor solicitado',
                };
            }

            return validacao;
        },
    },
};
</script>
