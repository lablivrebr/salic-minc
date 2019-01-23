<template>
    <div class="itens">
        <v-data-table
            :headers="headers"
            :items="table"
            :rows-per-page-items="[-1]"
            item-key="idPlanilhaProjeto"
            class="elevation-1"
            hide-actions
        >
            <template
                slot="items"
                slot-scope="props">
                <tr
                    :class="definirClasseItem(props.item)"
                    style="cursor: pointer"
                    @click="props.expanded = editarItem(props)"
                >
                    <td class="text-xs-center">{{ props.item.Seq }}</td>
                    <td class="text-xs-left">{{ props.item.Item }}</td>
                    <td class="text-xs-center">{{ props.item.UnidadeProjeto }}</td>
                    <td class="text-xs-center">{{ props.item.diasprop }}</td>
                    <td class="text-xs-center">{{ props.item.quantidadeprop }}</td>
                    <td class="text-xs-center">{{ props.item.ocorrenciaprop }}</td>
                    <td class="text-xs-right">{{ props.item.valorUnitarioprop | filtroFormatarParaReal }}</td>
                    <td class="text-xs-right">{{ props.item.VlSolicitado | filtroFormatarParaReal }}</td>
                    <td
                        class="text-xs-justify"
                        width="30%"
                        v-html="props.item.justificitivaproponente"/>
                    <td class="text-xs-right">{{ props.item.VlSugeridoParecerista | filtroFormatarParaReal }}</td>
                    <td
                        class="text-xs-justify"
                        width="30%"
                        v-html="props.item.dsJustificativaParecerista"/>
                </tr>
            </template>
            <template
                slot="expand"
                slot-scope="props">
                <v-card flat>
                    <v-card-text>
                        <v-form v-model="valid">
                            <v-container>
                                <v-layout row>
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
                                        label="Vl. Unitário"
                                        required
                                    />
                                        <v-text-field
                                            v-model="itemEmEdicao.valorUnitarioparc"
                                            label="Vl. Unitário"
                                            mask="###.###.###,##"
                                            required
                                        />
                                    </v-flex>
                                    <v-flex
                                        xs12
                                        md2
                                    >
                                        <v-text-field
                                            :value="valorSugerido"
                                            label="Vl. Sugerido"
                                            mask="###.###.###,##"
                                            disabled
                                        />
                                    </v-flex>
                                </v-layout>
                                <v-layout row>
                                    <v-flex
                                        xs12
                                        md12>
                                        <v-textarea
                                            v-model="itemEmEdicao.dsJustificativaParecerista"
                                            label="Justificativa"
                                            name="input-7-1"
                                        />
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-form>
                    </v-card-text>
                </v-card>
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
import { mapActions, mapGetters } from 'vuex';
import SalicInputValor from '@/components/SalicInputValor';

export default {
    components: { SalicInputValor },
    mixins: [planilhas],
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
            headers: [
                { text: '#', align: 'center', value: 'Seq' },
                { text: 'Item', align: 'left', value: 'Item' },
                { text: 'Unidade', align: 'left', value: 'UnidadeProjeto' },
                { text: 'Dias', align: 'center', value: 'diasprop' },
                { text: 'Qtde', align: 'center', value: 'quantidadeprop' },
                { text: 'Ocor.', align: 'center', value: 'ocorrenciaprop' },
                { text: 'Vl. Unitário', align: 'right', value: 'valorUnitarioprop' },
                { text: 'Vl. Solicitado', align: 'right', value: 'VlSolicitado' },
                { text: 'Just. Proponente', align: 'left', value: 'justificitivaproponente' },
                { text: 'Valor Sugerido', align: 'left', value: 'VlSugeridoParecerista' },
                { text: 'Just. Parecerista', align: 'left', value: 'dsJustificativaParecerista' },
            ],
            itemEmEdicao: {},
            firstname: '',
            lastname: '',
            nameRules: [
                v => !!v || 'Name is required',
                v => v.length <= 10 || 'Name must be less than 10 characters',
            ],
            email: '',
            emailRules: [
                v => !!v || 'E-mail is required',
                v => /.+@.+/.test(v) || 'E-mail must be valid',
            ],
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
        editarItem(props) {
            // this.$set(this, 'itemEmEdicao', props.item);
            this.itemEmEdicao = Object.assign({}, props.item);
            this.select = { idUnidade: this.itemEmEdicao.idUnidade, Sigla: '', Descricao: this.itemEmEdicao.UnidadeProjeto };
            return !props.expanded;
        },
    },
};
</script>
