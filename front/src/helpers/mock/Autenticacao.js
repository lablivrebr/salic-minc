import autenticacao from './autenticacao/autenticacao.json';

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

export const usuarioLogado = () => {
    return fetch(autenticacao, 1000);
}
