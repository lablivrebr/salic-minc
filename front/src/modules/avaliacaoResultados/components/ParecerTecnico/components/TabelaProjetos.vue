<template>
    <div>
        <v-card-title>
            <v-spacer/>
            <v-text-field
                v-model="search"
                append-icon="search"
                label="Pesquisar"
                single-line
                hide-details
            />
        </v-card-title>
        <v-data-table
            :headers="cab()"
            :items="dados.items"
            :pagination.sync="pagination"
            :search="search"
            hide-actions
        >
            <template
                slot="items"
                slot-scope="props">
                <td>{{ props.index+1 }}</td>
                <td class="text-xs-right">
                    <v-flex
                        xs12
                        sm4
                        text-xs-center>
                        <div>
                            <v-btn
                                :href="'/projeto/#/'+ props.item.idPronac"
                                flat>{{ props.item.PRONAC }}</v-btn>
                        </div>
                    </v-flex>
                </td>
                <td class="text-xs-left">{{ props.item.NomeProjeto }}</td>
                <td class="text-xs-center">{{ props.item.Situacao }}</td>
                <td class="text-xs-center">{{ props.item.UfProjeto }}</td>
                <td
                    v-if="mostrarTecnico"
                    class="text-xs-center">{{ props.item.usu_nome }}</td>
                <td class="text-xs-center">
                    <template
                        v-for="(c, index) in componentes.acoes"
                        d-inline-block>
                        <component
                            :key="index"
                            :obj="props.item"
                            :is="c"
                            :link-direto-assinatura="true"
                            :documento="props.item.idDocumentoAssinatura"
                            :id-pronac="props.item.IdPRONAC"
                            :pronac="props.item.PRONAC"
                            :nome-projeto="props.item.NomeProjeto"
                            :atual="componentes.atual"
                            :proximo="componentes.proximo"
                            :id-tipo-do-ato-administrativo="componentes.idTipoDoAtoAdministrativo"
                            :usuario="componentes.usuario"
                            :tecnico="{
                                idAgente: props.item.idAgente,
                                nome: props.item.usu_nome
                            }"
                        />
                    </template>
                </td>
            </template>
            <template slot="no-data">
                <v-alert
                    :value="true"
                    color="error"
                    icon="warning">
                    Nenhum dado encontrado ¯\_(ツ)_/¯
                </v-alert>
            </template>
        </v-data-table>
        <div class="text-xs-center">
            <div class="text-xs-center pt-2">
                <v-pagination
                    v-model="pagination.page"
                    :length="pages"
                    :total-visible="4"
                    color="primary "
                />
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'TabelaProjetos',
    props: ['dados', 'componentes', 'mostrarTecnico'],
    data() {
        return {
            pagination: {
                rowsPerPage: 10,
            },
            selected: [],
            search: '',
        };
    },
    methods: {
        ...mapActions({
            obterDadosTabelaTecnico: 'avaliacaoResultados/obterDadosTabelaTecnico',
        }),
        cab() {
            let dados = [];

            dados = [
                {
                    text: '#',
                    align: 'left',
                    sortable: false,
                    value: 'numero',
                },
                {
                    text: 'PRONAC',
                    value: 'PRONAC',
                },
                {
                    text: 'Nome Do Projeto',
                    align: 'center',
                    value: 'NomeProjeto',
                },
                {
                    text: 'Situacao',
                    align: 'center',
                    value: 'Situacao',
                },
                {
                    text: 'Estado',
                    align: 'center',
                    value: 'UfProjeto',
                },
            ];

            if (this.mostrarTecnico) {
                dados[5] = {
                    text: 'Tecnico',
                    align: 'center',
                    value: '',
                };
            }

            dados[6] = {
                text: 'Ações',
                sortable: false,
                align: 'center',
            };

            return dados;
        },
    },
    computed: {
        ...mapGetters({
            dadosTabelaTecnico: 'avaliacaoResultados/dadosTabelaTecnico',
            getProjetosFinalizados: 'avaliacaoResultados/getProjetosFinalizados',
        }),
        pages() {
            if (this.pagination.rowsPerPage == null
                || this.pagination.totalItems == null
            ) return 0;
            return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage);
        },
    },
    watch: {
        dadosTabelaTecnico() {
            if (this.dados.items !== undefined) {
                this.pagination.totalItems = this.dados.items.length;
            }
        },
    },
};
</script>
