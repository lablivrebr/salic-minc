import moment from 'moment';
import cnpjFilter from '@/filters/cnpj';
import moneyFilter from '@/filters/money';
/* eslint-disable */
export const utils = {
    methods: {
        mensagemSucesso: function (msg) {
            Materialize.toast(msg, 8000, 'green white-text');
        },
        mensagemErro: function (msg) {
            Materialize.toast(msg, 8000, 'red darken-1 white-text');
        },
        mensagemAlerta: function (msg) {
            Materialize.toast(msg, 8000, 'mensagem1 orange darken-3 white-text');
        },
        formatarValor: function (valor) {
            valor = parseFloat(valor);
            return moneyFilter(valor);
        },
        label_sim_ou_nao: function (valor) {
            if (valor == 1) {
                return 'Sim';
            }

            return 'N\xE3o';
        },
        isDataExpirada(date) {
            return moment().diff(date, 'days') > 0;
        },
    },
    filters: {
        formatarData(date) {
            if (date && date.length === 0 || date === null) {
                return '-';
            }
            return moment(date)
                .format('DD/MM/YYYY');
        },
        formatarAgencia(agencia) {
            // formato: 9999-9
            if (agencia && agencia.length === 5) {
                agencia = agencia.replace(/(\d{4})(\d)/, '$1-$2');
            }
            return agencia;
        },
        formatarConta(conta) {
            // formato: 99999-9
            return conta.toString().replace(/^\d{6}(.+)(\w{1})$/, "$1-$2");
        },
        formatarLabelSimOuNao(valor) {
            if (valor === 1 || valor === '1') {
                return 'Sim';
            }
            return 'N\xE3o';
        },
        formatarLabelMecanismo(valor) {
            switch (valor) {
                case '1':
                case 1:
                    return 'Mecenato';
                default:
                    return 'Inv\xE1lido';
            }
        },
        formatarLabelEsfera(esfera) {
            let string;

            switch (esfera) {
                case '1':
                    string = 'Municipal';
                    break;
                case '2':
                    string = 'Estadual';
                    break;
                case '3':
                    string = 'Federal';
                    break;
                default:
                    string = 'N\xE3o informada';
                    break;
            }

            return string;
        },
        formatarCpfOuCnpj(value) {
            if(typeof value === 'undefined') {
                return '';
            }
            return cnpjFilter(value);
        },
        formatarTipoPessoa(tipo) {
            let string = 'Pessoa F\xEDsica';

            if (tipo === '1') {
                string = 'Pessoa Jur\xEDdica';
            }

            return string;
        },
        formatarCep(v) {

            if (v.length !== 8) {
                return '';
            }

            let value = v.replace(/\D/g, '');
            value = value.replace(/(\d{2})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1-$2');
            return value;
        },
        formatarParaReal(v) {
            return moneyFilter(v);
        },
        filtroFormatarParaReal(value) {
            const parsedValue = parseFloat(value);
            return moneyFilter(parsedValue);
        },
    },
};
