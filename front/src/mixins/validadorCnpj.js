export const validadorCnpj = {
    methods: {
        validarCnpj(strCNPJ) {
            const cnpj = strCNPJ.replace(/[^\d]+/g, '');
            const invalidos = [
                '00000000000000',
                '11111111111111',
                '22222222222222',
                '33333333333333',
                '44444444444444',
                '55555555555555',
                '66666666666666',
                '77777777777777',
                '88888888888888',
                '99999999999999',
            ];
            if (invalidos.includes(cnpj)) {
                return false;
            }

            let soma = 0;
            let resto;
            let resultado;
            let tamanho = cnpj.length - 2;
            let numeros = cnpj.substring(0, tamanho);
            const digitos = cnpj.substring(12);
            let posicao = tamanho - 7;

            // Valida primeiro dígito
            for (let i = 0; i < tamanho; i += 1) {
                soma += parseInt(numeros.charAt(i), 10) * posicao;
                posicao -= 1;
                if (posicao < 2) {
                    posicao = 9;
                }
            }
            resto = soma % 11;
            resultado = (resto < 2) ? 0 : (11 - resto);
            if (resultado !== parseInt(digitos.charAt(0), 10)) {
                return false;
            }

            // Valida segundo dígito
            tamanho += 1;
            numeros = cnpj.substring(0, tamanho);
            posicao = tamanho - 7;
            soma = 0;
            for (let i = 0; i < tamanho; i += 1) {
                soma += parseInt(numeros.charAt(i), 10) * posicao;
                posicao -= 1;
                if (posicao < 2) {
                    posicao = 9;
                }
            }
            resto = soma % 11;
            resultado = (resto < 2) ? 0 : (11 - resto);
            if (resultado !== parseInt(digitos.charAt(1), 10)) {
                return false;
            }

            return true;
        },
    },
};
