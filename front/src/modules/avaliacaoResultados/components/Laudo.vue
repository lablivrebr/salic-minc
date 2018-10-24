<template>
    <v-container fluid>
        <v-card>
            <v-card-title>
                <v-spacer></v-spacer>
                <v-text-field
                        v-model="search"
                        append-icon="search"
                        label="Pesquisar"
                        single-line
                        hide-details
                        height="35px"
                ></v-text-field>
            </v-card-title>
            {{acao.assinar}}
            <v-data-table
                    :headers="cabecalho"
                    :items="dados.items"
                    :pagination.sync="pagination"
                    hide-actions
                    :search="search"
            >
                <template slot="items" slot-scope="props">
                    <td class="text-xs-center">{{ props.index+1 }}</td>
                    <td class="text-xs-center">
                        <v-flex>
                            <div>
                                <v-btn :href="'/projeto/#/'+ props.item.IdPronac">{{ props.item.PRONAC }}</v-btn>
                            </div>
                        </v-flex>
                    </td>
                    <td class="text-xs-center">{{ props.item.NomeProjeto }}</td>
                    <td class="text-xs-center">
                        <v-btn v-if="props.item.siManifestacao == 'A'"
                               round
                               color="green darken-4"
                               dark
                               @click.native="sincState(props.item.IdPronac)"
                               :to="{ name: 'VisualizarParecer', params:{ id:props.item.IdPronac }}"
                        >
                            <v-icon>mood</v-icon>Aprovado
                        </v-btn>
                        <v-btn v-if="props.item.siManifestacao == 'P'"
                               round
                               color="green lighten-1"
                               dark
                               @click.native="sincState(props.item.IdPronac)"
                               :to="{ name: 'VisualizarParecer', params:{ id:props.item.IdPronac }}"
                        >
                            <v-icon>sentiment_satisfied_alt</v-icon>Aprovado com ressalva
                        </v-btn>
                        <v-btn v-if="props.item.siManifestacao == 'R'"
                               round
                               color="red"
                               dark
                               @click.native="sincState(props.item.IdPronac)"
                               :to="{ name: 'VisualizarParecer', params:{ id:props.item.IdPronac }}"
                        >
                            <v-icon>sentiment_very_dissatisfied</v-icon>Reprovado
                        </v-btn>
                    </td>
                    <td class="text-xs-center">
                        <v-dialog v-model="dialog" max-width="290">
                            <v-btn slot="activator" flat icon color="green" :disabled="acao !== 'analisar'">
                                <v-icon>keyboard_return</v-icon>
                            </v-btn>
                            <v-card>
                                <v-card-title class="headline">Deseja realmente devolver o documento?</v-card-title>
                                <v-card-text>Devolver parecer para nova análise.</v-card-text>
                                <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="red" flat @click.native="dialog = false">Cancelar</v-btn>
                                <v-btn color="green" flat @click.native="dialog = false">Devolver</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </td>
                    <td v-if="acao == 'analisar'" class="text-xs-center">
                        <v-btn flat icon color="blue"
                               @click.native="sincState(props.item.IdPronac)"
                               :to="{ name: 'EmitirLaudoFinal', params:{ id:props.item.IdPronac }}">
                            <v-tooltip bottom>
                                <v-icon slot="activator" class="material-icons">create</v-icon>
                                <span>Emitir Laudo</span>
                            </v-tooltip>
                        </v-btn>
                    </td>
                    <td v-if="acao == 'assinar'" class="text-xs-center">
                        <v-btn flat icon color="blue"
                               :href="'/assinatura/index/assinar-projeto?IdPRONAC='+props.item.IdPronac+'&idTipoDoAtoAdministrativo=623'">
                            <v-tooltip bottom>
                                <v-icon slot="activator" class="material-icons">assignment_turned_in</v-icon>
                                <span>Assinar Laudo</span>
                            </v-tooltip>
                        </v-btn>
                    </td>
                    <td v-if="acao == 'visualizar'" class="text-xs-center">
                        <v-btn flat icon color="blue"
                               @click.native="sincState(props.item.IdPronac)"
                               :to="{ name: 'VisualizarLaudo', params:{ id:props.item.IdPronac }}">
                            <v-tooltip bottom>
                                <v-icon slot="activator" class="material-icons">visibility</v-icon>
                                <span>Visualizar Laudo</span>
                            </v-tooltip>
                        </v-btn>
                    </td>
                </template>
                <template slot="no-data">
                    <v-alert :value="true" color="error" icon="warning">
                        Nenhum dado encontrado ¯\_(ツ)_/¯

                    </v-alert>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                    Não foi possível encontrar um projeto com a palavra chave '{{search}}'.
                </v-alert>
            </v-data-table>
            <div v-if="pagination.totalItems" class="text-xs-center">
                <div class="text-xs-center pt-2">
                    <v-pagination
                            v-model="pagination.page"
                            :length="pages"
                            :total-visible="3"
                            color="green lighten-2"
                    >
                    </v-pagination>

                </div>
            </div>
        </v-card>
    </v-container>

</template>

<script>
    import { mapActions } from 'vuex';
    import ModalTemplate from '@/components/modal';

    export default {
        name: 'Painel',
        props: ['dados', 'acao'],
        data() {
            return {
                pagination: {
                    rowsPerPage: 10,
                },
                searchLength: 0,
                search: '',
                dialog: false,
                cabecalho: [
                    {
                        align: 'center',
                        text: '#',
                        sortable: false,
                    },
                    {
                        align: 'center',
                        text: 'PRONAC',
                        value: 'PRONAC',
                    },
                    {
                        align: 'center',
                        text: 'Nome Do Projeto',
                        value: 'NomeProjeto',
                    },
                    {
                        align: 'center',
                        text: 'Manifestação',
                        value: 'dsResutaldoAvaliacaoObjeto',
                    },
                    {
                        align: 'center',
                        text: 'Devolver',
                        sortable: false,

                    },
                    {
                        align: 'center',
                        text: 'Ação',
                        sortable: false,
                    },
                ],
            };
        },
        components: {
            ModalTemplate,
        },
        methods: {
            ...mapActions({
                requestEmissaoParecer: 'avaliacaoResultados/getDadosEmissaoParecer',
                getLaudoFinal: 'avaliacaoResultados/getLaudoFinal',
            }),
            sincState(id) {
                this.requestEmissaoParecer(id);
                this.getLaudoFinal(id);
            },
        },
        computed: {
            pages() {
                if (this.pagination.rowsPerPage == null ||
                    this.pagination.totalItems == null
                ) return 0;
                return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage);
            },
        },
    };
</script>
