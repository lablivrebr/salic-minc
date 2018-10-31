import * as API from '@/helpers/api/AvaliacaoResultados';
import * as actions from './actions';
import axios from 'axios';

jest.mock('axios');

describe('Testes Actions - Avaliação de Resultados', () => {
    let commit;
    let mockReponse;
    let projeto;
    let params;
    let tecnico;
    let historicoProjeto;

    describe('projetosParaDistribuir - Aba "Encaminhar" ', () => {
        beforeEach(() => {
            mockReponse = {
                data: [
                    {
                        "Pronac": "1410398",
                        "PRONAC": "1410398",
                        "NomeProjeto": "Porto Verão Alegre 2015",
                        "cdSituacao": "E68",
                        "Situacao": "E68",
                        "UfProjeto": "RS",
                        "IdPRONAC": "185373",
                        "Prioridade": "0",
                        "idPronac": "185373"
                    }
                ],
            };

            axios.get.mockResolvedValue(mockReponse);

            commit = jest.fn();

            jest.spyOn(API, 'obterProjetosParaDistribuir');
            actions.projetosParaDistribuir({ commit });

            projeto = [{
                "Pronac": "1410398",
                "PRONAC": "1410398",
                "NomeProjeto": "Porto Verão Alegre 2015",
                "cdSituacao": "E68",
                "Situacao": "E68",
                "UfProjeto": "RS",
                "IdPRONAC": "185373",
                "Prioridade": "0",
                "idPronac": "185373"
            }];
        });

        test('it is commit to projetosParaDistribuir', (done) => {
            done();
            expect(commit).toHaveBeenCalledWith('SET_DADOS_PROJETOS_PARA_DISTRIBUIR', projeto);
        });

        test('it calls API.projetosRevisao', () => {
            expect(API.obterProjetosParaDistribuir).toHaveBeenCalled();
        });
    });

    describe('obterDadosTabelaTecnico - Aba "Em Análise" ', () => {
        beforeEach(() => {
            mockReponse = {
                data: {
                    data: [
                        {
                            NomeProjeto: 'Manutenção das atividades da Orquestra Sinfônica Jovem de Nova Mutum',
                            Situacao: 'E27',
                            UfProjeto: 'MT',
                            PRONAC: '456789',
                            idPronac: '168213',
                            usu_nome: 'Rômulo Menhô Barbosa'
                        },
                    ],
                },
            };

            axios.get.mockResolvedValue(mockReponse);

            params = {
                estadoid: 5,
                idAgente: 123
            }

            commit = jest.fn();

            jest.spyOn(API, 'obterDadosTabelaTecnico');
            actions.obterDadosTabelaTecnico({ commit }, params);

            projeto = [{
                NomeProjeto: 'Manutenção das atividades da Orquestra Sinfônica Jovem de Nova Mutum',
                Situacao: 'E27',
                UfProjeto: 'MT',
                PRONAC: '456789',
                idPronac: '168213',
                usu_nome: 'Rômulo Menhô Barbosa'
            }];
        });

        test('it is commit to obterDadosTabelaTecnico', (done) => {
            done();
            expect(commit).toHaveBeenCalledWith('PROJETOS_AVALIACAO_TECNICA', projeto);
        });

        test('it calls API.obterDadosTabelaTecnico', () => {
            expect(API.obterDadosTabelaTecnico).toHaveBeenCalled();
        });
    });

    describe('projetosAssinatura - Aba "Assinar" ', () => {
        beforeEach(() => {
            mockReponse = {
                data: {
                    data: [
                        {
                            NomeProjeto: 'Manutenção das atividades da Orquestra Sinfônica Jovem de Nova Mutum',
                            Situacao: 'E27',
                            UfProjeto: 'MT',
                            PRONAC: '456789',
                            idPronac: '168213',
                            usu_nome: 'Rômulo Menhô Barbosa'
                        },
                    ],
                },
            };

            axios.get.mockResolvedValue(mockReponse);

            params = {
                estadoid: 6,
                idAgente: 123
            }

            commit = jest.fn();

            jest.spyOn(API, 'projetosRevisao');
            actions.projetosFinalizados({ commit }, params);

            projeto = [{
                NomeProjeto: 'Manutenção das atividades da Orquestra Sinfônica Jovem de Nova Mutum',
                Situacao: 'E27',
                UfProjeto: 'MT',
                PRONAC: '456789',
                idPronac: '168213',
                usu_nome: 'Rômulo Menhô Barbosa'
            }];
        });

        test('it is commit to projetosFinalizados', (done) => {
            done();
            expect(commit).toHaveBeenCalledWith('SET_DADOS_PROJETOS_FINALIZADOS', projeto);
        });

        test('it calls API.projetosRevisao', () => {
            expect(API.obterDadosTabelaTecnico).toHaveBeenCalled();
        });
    });

    describe('projetosAssinatura - Aba "Histórico" ', () => {
        beforeEach(() => {
            mockReponse = {
                data: {
                    data: [
                        {
                            NomeProjeto: 'Manutenção das atividades da Orquestra Sinfônica Jovem de Nova Mutum',
                            Situacao: 'E27',
                            UfProjeto: 'MT',
                            PRONAC: '456789',
                            idPronac: '168213',
                            usu_nome: 'Rômulo Menhô Barbosa'
                        },
                    ],
                },
            };

            axios.get.mockResolvedValue(mockReponse);

            params = { estado: 'historico' };

            commit = jest.fn();

            jest.spyOn(API, 'obterProjetosAssinatura');
            actions.projetosAssinatura({ commit }, params);

            projeto = [{
                NomeProjeto: 'Manutenção das atividades da Orquestra Sinfônica Jovem de Nova Mutum',
                Situacao: 'E27',
                UfProjeto: 'MT',
                PRONAC: '456789',
                idPronac: '168213',
                usu_nome: 'Rômulo Menhô Barbosa'
            }];
        });

        test('it is commit to projetosFinalizados', (done) => {
            done();
            expect(commit).toHaveBeenCalledWith('SET_DADOS_PROJETOS_HISTORICO', projeto);
        });

        test('it calls API.projetosRevisao', () => {
            expect(API.obterProjetosAssinatura).toHaveBeenCalled();
            expect(API.obterProjetosAssinatura).toHaveBeenCalledWith(params);

        });
    });

    describe('obter lista de Técnicos - Aba "Encaminhar" ', () => {
        beforeEach(() => {
            commit = jest.fn();

            jest.spyOn(API, 'obterDestinatarios');
            actions.obterDestinatarios({ commit });

            tecnico = [{
                "usu_codigo": 6087,
                "usu_nome": "Adilson S da Silva",
                "idperfil": 124,
                "idAgente": 6087
            }];
        });

        test('it is commit to projetosFinalizados', (done) => {
            done();
            expect(commit).toHaveBeenCalledWith('DESTINATARIOS_ENCAMINHAMENTO', tecnico.items);
        });

        test('it calls API.obterDestinatarios', () => {
            expect(API.obterDestinatarios).toHaveBeenCalled();
        });
    });

    describe('Obter Histórico de Encaminhamentos', () => {
        beforeEach(() => {
            mockReponse = {
                data: {
                    data: [
                        {
                            "PRONAC": "138419",
                            "NomeProjeto": "Manutenção das atividades da Orquestra Sinfônica Jovem de Nova Mutum",
                            "dtInicioEncaminhamento": "10/15/2018",
                            "dsJustificativa": "Justificando",
                            "NomeOrigem": "Rômulo Menhô Barbosa",
                            "NomeDestino": "Rômulo Menhô Barbosa"
                        },
                    ],
                },
            };

            axios.get.mockResolvedValue(mockReponse);


            commit = jest.fn();

            params = {
                idPronac: 168213
            };

            jest.spyOn(API, 'obterHistoricoEncaminhamento');
            actions.obterHistoricoEncaminhamento({ commit }, params);

            historicoProjeto = [{
                "PRONAC": "138419",
                "NomeProjeto": "Manutenção das atividades da Orquestra Sinfônica Jovem de Nova Mutum",
                "dtInicioEncaminhamento": "10/15/2018",
                "dsJustificativa": "Justificando",
                "NomeOrigem": "Rômulo Menhô Barbosa",
                "NomeDestino": "Rômulo Menhô Barbosa"
            }];
        });

        test('it is commit to projetosFinalizados', (done) => {
            done();
            expect(commit).toHaveBeenCalledWith('HISTORICO_ENCAMINHAMENTO', historicoProjeto.items);
        });

        test('it calls API.obterDestinatarios', () => {
            expect(API.obterDestinatarios).toHaveBeenCalled();
        });
    });

    describe('Dados da Planilha', () => {
        beforeEach(() => {
            mockReponse = require('../mocks/planilhaAnaliseFiltros.json');

            axios.get.mockResolvedValue(mockReponse);

            commit = jest.fn();

            params = {
                idPronac: 159934
            };

            jest.spyOn(API, 'planilha');
            actions.planilha({ commit }, params);

        });

        test('it is commit to planilha', (done) => {
            done();
            expect(commit).toHaveBeenCalledWith('GET_PLANILHA', mockReponse.data);
        });

        test('it calls API.planilha', () => {
            expect(API.planilha).toHaveBeenCalled();
        });
    });

    describe('Analisar Projeto', () => {
        beforeEach(() => {
            mockReponse = {
                "items": {
                    "nomeProjeto": "Manutenção das atividades da Orquestra Sinfônica Jovem de Nova Mutum",
                    "vlTotalComprovar": 308722.05,
                    "vlAprovado": 563996,
                    "vlComprovado": 255273.95,
                    "pronac": "138419",
                    "diligencia": false,
                    "estado": null,
                    "documento": []
                }
            };

            axios.get.mockResolvedValue(mockReponse);

            commit = jest.fn();

            params = {
                idPronac: 168213
            };

            jest.spyOn(API, 'projetoAnalise');
            actions.projetoAnalise({ commit }, params);

        });

        test('it is commit to projetoAnalise', (done) => {
            done();
            expect(commit).toHaveBeenCalledWith('GET_PROJETO_ANALISE', mockReponse.data);
        });

        test('it calls API.projetoAnalise', () => {
            expect(API.projetoAnalise).toHaveBeenCalled();
        });
    });

    describe('setConsolidacaoAnalise', () => {
        beforeEach(() => {
            mockReponse = require('../mocks/ConsolidacaoAnalise.json');

            axios.get.mockResolvedValue(mockReponse);

            commit = jest.fn();

            params = {
                idPronac: 168213
            };

            jest.spyOn(API, 'consolidacaoAnalise');
            actions.consolidacaoAnalise({ commit }, params);

        });

        test('it is commit to consolidacaoAnalise', (done) => {
            done();
            expect(commit).toHaveBeenCalledWith('GET_CONSOLIDACAO_ANALISE', mockReponse.data);
        });

        test('it calls API.consolidacaoAnalise', () => {
            expect(API.consolidacaoAnalise).toHaveBeenCalled();
        });
    });

    // describe('Devolver Projeto', () => {
    //     beforeEach(() => {
    //         mockReponse = {
    //             data: {
    //                 data: 
    //                     {
    //                         idPronac: '138419',
    //                         atual: '6',
    //                         proximo: '5',
    //                         idTipoDoAtoAdministrativo: '622',
    //                     },
    //             },
    //         };

    //         axios.post.mockResolvedValue(mockReponse);
            
    //         function FormDataMock() {
    //             this.append = jest.fn();
    //         }

    //         global.FormData = FormDataMock;
            
    //         params = {
    //             idPronac: '138419',
    //             atual: '6',
    //             proximo: '5',
    //             idTipoDoAtoAdministrativo: '622',
    //         };

    //         commit = jest.fn();
            
    //         jest.spyOn(API, 'devolverProjeto');
    //         actions.devolverProjeto({ commit }, params);

    //     });

    //     test('it is commit to devolverProjeto', (done) => {
    //         done();
    //         expect(commit).toHaveBeenCalledWith('SET_DEVOLVER_PROJETO', params);
    //     });

    //     test('it calls API.devolverProjeto', () => {
    //         expect(API.devolverProjeto).toHaveBeenCalled();
    //     });
    // });

});