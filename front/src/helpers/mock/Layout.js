import menuPrincipal from './layout/menu_principal.json';
import dadosLayout from './layout/dados_layout.json';
import perfis from './layout/perfis.json';


const fetch = (mockData, time = 0) => {
    const response = {
        data: mockData,
    };
    return new Promise((resolve) => {
        setTimeout(() => {
            resolve(response);
        }, time);
    });
};

export const buscarDadosLayout = () => fetch(dadosLayout, 1000);

export const buscarPerfisDisponiveis = () => fetch(perfis, 1000);

export const buscarDadosMenu = () => fetch(menuPrincipal, 1000);
