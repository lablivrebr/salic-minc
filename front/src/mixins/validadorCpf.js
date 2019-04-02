export const validadorCpf = {
    methods: {
        validarCpf(strCPF) {
            const cpf = strCPF.replace(/[^\d]+/g, '');
            const invalidos = [
                '00000000000',
                '11111111111',
                '22222222222',
                '33333333333',
                '44444444444',
                '55555555555',
                '66666666666',
                '77777777777',
                '88888888888',
                '99999999999',
            ];
            if (invalidos.includes(cpf)) {
                return false;
            }

            let soma;
            let resto;
            soma = 0;

            // Valida primeiro dígito
            for (let i = 1; i <= 9; i += 1) {
                soma += parseInt(cpf.substring(i - 1, i), 10) * (11 - i);
            }
            resto = (soma * 10) % 11;
            if ((resto === 10) || (resto === 11)) {
                resto = 0;
            }
            if (resto !== parseInt(cpf.substring(9, 10), 10)) {
                return false;
            }

            // Valida segundo dígito
            soma = 0;
            for (let i = 1; i <= 10; i += 1) {
                soma += parseInt(cpf.substring(i - 1, i), 10) * (12 - i);
            }
            resto = (soma * 10) % 11;
            if ((resto === 10) || (resto === 11)) {
                resto = 0;
            }
            if (resto !== parseInt(cpf.substring(10, 11), 10)) {
                return false;
            }

            return true;
        },
    },
};
