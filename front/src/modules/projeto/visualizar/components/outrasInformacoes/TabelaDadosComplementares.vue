<template>
    <v-expansion-panel
        popout
        focusable>
        <v-expansion-panel-content class="elevation-1">
            <v-layout
                slot="header"
                class="primary--text">
                <v-icon class="mr-3 primary--text">subject</v-icon>
                <span v-html="dadoComplementar">{{ dadoComplementar }}</span>
            </v-layout>
            <v-card>
                <v-card-text>
                    <div
                        v-if="dsDadoComplementar && dsDadoComplementar.length > 1"
                        v-html="dsDadoComplementar">
                        <td>{{ dsDadoComplementar }}</td>
                    </div>
                    <div v-else-if="custosVinculados">
                        <v-data-table
                            :pagination.sync="pagination"
                            :headers="headers"
                            :items="custosVinculados"
                            class="elevation-1 container-fluid"
                        >
                            <template
                                slot="items"
                                slot-scope="props">
                                <td class="text-xs-left">{{ props.item.Descricao }}</td>
                                <td class="text-xs-center pl-5">{{ props.item.Percentual }}</td>
                                <td class="text-xs-center pl-5">{{ props.item.dtCadastro | formatarData }}</td>
                            </template>
                        </v-data-table>
                    </div>
                    <div v-else>
                        <v-data-table
                            no-data-text="Nenhum dado encontrado"
                            hide-actions
                            hide-headers
                        />
                    </div>
                </v-card-text>
            </v-card>
        </v-expansion-panel-content>
    </v-expansion-panel>
</template>

<script>
import { utils } from '@/mixins/utils';

export default {
    name: 'TabelaDadosComplementares',
    props: {
        dadoComplementar: {
            type: String,
            default: '',
        },
        dsDadoComplementar: {
            type: String,
            default: '',
        },
        custosVinculados: {
            type: Array,
            default: () => [],
        },
    },
    mixins: [utils],
    data() {
        return {
            pagination: {
                sortBy: 'dtCadastro',
                descending: true,
            },
            headers: [
                {
                    text: 'ITEM',
                    align: 'left',
                    value: 'Descricao',
                },
                {
                    text: 'PERCENTUAL',
                    align: 'center',
                    value: 'Percentual',
                },
                {
                    text: 'DATA',
                    align: 'center',
                    value: 'dtCadastro',
                },
            ],
        };
    },
};
</script>
