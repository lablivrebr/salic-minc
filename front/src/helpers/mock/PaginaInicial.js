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

export const buscarComunicados = () => fetch({}, 1000);

