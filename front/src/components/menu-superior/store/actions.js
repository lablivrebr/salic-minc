import * as avaliacaoResultadosHelperAPI from '@/helpers/api/AvaliacaoResultados';
import * as desencapsularResponse from '@/helpers/actions';
import * as types from './types';

export const buscarPerfisDisponiveis = ({ commit }, params) => {
    avaliacaoResultadosHelperAPI.buscarPerfisDisponiveis(params)
        .then((response) => {
            const items = desencapsularResponse.default(response);
            commit(types.SET_PERFIS_DISPONIVEIS, items.perfisDisponoveis);
            commit(types.SET_USUARIO_ATIVO, items.usuarioAtivo);
            commit(types.SET_GRUPO_ATIVO, items.grupoAtivo);
            commit(types.SET_GRUPO_SELECIONADO_INDEX, items.grupoSelecionadoIndex);
        });
};

export const alterarPerfil = (_, perfil) => {
    const grupoAtivo = perfil.gru_codigo;
    const orgaoAtivo = perfil.uog_orgao;
    const url = `/autenticacao/perfil/alterarperfil?codGrupo=${grupoAtivo}&codOrgao=${orgaoAtivo}`;
    window.location.replace(url);
};
