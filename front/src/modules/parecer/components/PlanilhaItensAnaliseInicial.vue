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
                        class="text-xs-justify"
                        width="30%"
                        v-html="$options.filters.filtroDiminuirTexto(props.item.dsJustificativaParecerista, 40)"/>
                    <td class="text-xs-right">{{ props.item.VlSolicitado | filtroFormatarParaReal }}</td>
                    <td
                        class="text-xs-justify"
                        width="30%"
                        v-html="$options.filters.filtroDiminuirTexto(props.item.justificitivaproponente, 40)"/>
                </tr>
            </template>
            <template
                slot="expand"
                slot-scope="props">
                <v-layout
                    row
                    justify-center
                    class="blue-grey lighten-5 pa-2">
                    <v-card>
                        <v-card-title class="py-1">
                            <h3>Editando item: {{ itemEmEdicao.Item }} </h3>
                        </v-card-title>
                        <v-divider/>
                        <v-card-text>
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
                                                v-model="select"
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
                                                label="Ocorrência"
                                                required
                                            />
                                        </v-flex>
                                        <v-flex
                                            xs12
                                            md2
                                        >    <SalicInputValor
                                            v-model="itemEmEdicao.valorUnitarioparc"
                                            label="Vl. Unitário (R$)"
                                            required
                                        />
                                        </v-flex>
                                        <v-flex
                                            xs12
                                            md2
                                        >
                                            <v-text-field
                                                :value="valorSugerido | filtroFormatarParaReal"
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
                                        dark
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
                    <td class="text-xs-right"><b>{{ obterValorSolicitadoTotal(table) }}</b></td>
                    <td/>
                    <td class="text-xs-right"><b>{{ obterValorSugeridoTotal(table) }}</b></td>
                    <td colspan="2"/>
                </tr>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import planilhas from '@/mixins/planilhas';
import { utils } from '@/mixins/utils';
import { mapActions, mapGetters } from 'vuex';
import SalicInputValor from '@/components/SalicInputValor';

export default {
    components: { SalicInputValor },
    mixins: [planilhas, utils],
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
                { text: 'Vl. Solicitado', align: 'right', value: 'VlSolicitado' },
                { text: 'Just. Proponente', align: 'left', value: 'justificitivaproponente' },
            ],
            itemEmEdicao: {},
            select: {},
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
    },
    watch: {
        select(value) {
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
            // this.$set(this, 'itemEmEdicao', props.item);
            if (props.item.isDisponivelParaAnalise === false) {
                return false;
            }

            this.itemEmEdicao = Object.assign({}, props.item);
            this.select = { idUnidade: this.itemEmEdicao.idUnidade, Sigla: '', Descricao: this.itemEmEdicao.UnidadeProjeto };
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
        isLinhaAlterada(row) {
            const a1 = [
                row.Unidade,
                row.VlSolicitado,
                row.ocorrenciaprop,
                row.quantidadeprop,
                row.diasprop,
                row.valorUnitarioprop,
            ];
            const a2 = [
                row.idUnidade,
                row.VlSugeridoParecerista,
                row.ocorrenciaparc,
                row.quantidadeparc,
                row.diasparc,
                row.valorUnitarioparc,
            ];
            return JSON.stringify(a1) !== JSON.stringify(a2);
        },
        obterClasseItem(row) {
            return {
                'blue lighten-5': this.isLinhaAlterada(row),
                'grey lighten-3 grey--text text--darken-3': row.isDisponivelParaAnalise === false,
                ...this.definirClasseItem(row),
            };
        },
        obterEstiloItem(row) {
            return {
                cursor: row.isDisponivelParaAnalise === false ? 'not-allowed' : 'pointer',
            };
        },
        validarLinha(row) {
            const isCustoPraticado = (row.stCustoPraticado === true || row.stCustoPraticado === '1' || row.stCustoPraticado === 1);

            if (isCustoPraticado && row.dsJustificativaParecerista.length < 3) {
                return {
                    valid: false,
                    message: 'Proponente ultrapassou a mediana, altere o valor solicitado ou justifique o valor solicitado',
                };
            }

            return {
                valid: true,
            };
        },
    },
};
</script>
