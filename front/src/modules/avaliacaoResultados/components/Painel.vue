<template>
        <v-data-table
                :headers="cabecalho"
                :items="dados"
                hide-actions
                class="elevation-1"
        >
            <template slot="items" slot-scope="props">
                <td>{{ props.item.numero }}</td>
                <td class="text-xs-right">{{ props.item.pronac }}
                    <v-flex xs12 sm4 text-xs-center>
                        <div>
                            <v-btn>131313131</v-btn>
                        </div>
                    </v-flex>
                </td>
                <td class="text-xs-right">{{ props.item.nome }}</td>
                <td class="text-xs-right">{{ props.item.situacao }}</td>
                <td class="text-xs-right">{{ props.item.area }}</td>
                <td class="text-xs-right">{{ props.item.estado }}</td>
                <td class="text-xs-right">{{ props.item.mecanismo }}</td>
                <td class="text-xs-right">{{ props.item.data }}</td>
                <td class="text-xs-right">{{ props.item.analisar }}
                    <div>
                        <v-btn dark color="teal lighten-2" to="/tipo-avaliacao">
                            <i class="material-icons">compare_arrows</i>
                        </v-btn>
                    </div>
                </td>
                <td class="text-xs-right">{{ props.item.diligencia }}
                    <div>
                        <v-btn dark color="yellow accent-3">
                            <i class="material-icons">
                                warning
                            </i>
                        </v-btn>
                    </div>
                </td>
                <td class="text-xs-right">{{ props.item.historico }}
                    <v-flex xs12 sm4 text-xs-center>
                        <div>
                            <v-btn to="/historico">
                                <i class="material-icons">
                                history
                            </i></v-btn>
                        </div>
                    </v-flex>
                </td>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import ModalTemplate from '@/components/modal';
import ComponenteEncaminhar from './ComponenteEncaminhar';

export default {
    name: 'Painel',
    data () {
        return {
            cabecalho: [
                {
                    text: '#',
                    align: 'left',
                    sortable: false,
                    value: 'numero'
                },
                { text: 'PRONAC', value: 'pronac' },
                { text: 'Nome Do Projeto', value: 'nome' },
                { text: 'Situacao', value: 'situacao' },
                { text: 'Area/Segmento', value: 'area' },
                { text: 'Estado', value: 'estado' },
                { text: 'Mecanismo', value: 'mecanismo' },
                { text: 'Dt.recebimento', value: 'data' },
                { text: 'Analisar', value: 'analisar' },
                { text: 'Diligencia', value: 'diligencia' },
                { text: 'Historico', value: 'historico' },

            ],
            dados: [
                {
                    numero: 1,
                    nome:'Crianca e vida - 15 anos',
                    situacao:'E17',
                    area:'Artes Integradas / Artes Integradas',
                    estado: 'SP',
                    mecanismo: 'Incentivo Fiscal',
                    data: '27/10/2017',
                },
                {
                    numero: 1,
                    nome:'Crianca e vida - 15 anos',
                    situacao:'E17',
                    area:'Artes Integradas / Artes Integradas',
                    estado: 'SP',
                    mecanismo: 'Incentivo Fiscal',
                    data: '27/10/2017',
                },

            ]
        }
    },
    components: {
        ModalTemplate,
        ComponenteEncaminhar,
    },
    methods: {
        ...mapActions({
            criarRegistro: 'foo/criarRegistro',
            modalOpen: 'modal/modalOpen',
            modalClose: 'modal/modalClose',
        }),
        fecharModal() {
            // eslint-disable-next-line
            $3('#modalTemplate').modal('close');
            this.modalClose();
        },
    },
    computed: mapGetters({
        modalVisible: 'modal/default',
    }),
};
</script>
