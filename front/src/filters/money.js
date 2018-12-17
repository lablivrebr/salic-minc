export default (value) => {
    const moeda = Number(value);
    return moeda.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
};
