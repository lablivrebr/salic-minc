<template>
    <div class="conteudo">
        <legend>Identificação</legend>
        <table class="tabela">
            <tr class="destacar">
                <td align="center"><b>PRONAC</b></td>
                <td align="center"><b>Nome do Projeto</b></td>
            </tr>
            <tr>
                <td align="center">{{ dadosProjeto.Pronac }}</td>
                <td align="center">{{ dadosProjeto.NomeProjeto }}</td>
            </tr>
        </table>
        <br clear="all">
        <table
            v-if="ProponenteInabilitado"
            class="tabela"
            style="background-color: red;">
            <tr style="background-color: red;">
                <td
                    align="center"
                    style="text-transform: uppercase; color: red;">
                    <b>Proponente Inabilitado</b>
                </td>
            </tr>
        </table>
        <table
            v-for="(proponente, index) in proponentes"
            v-else
            :key="index"
            class="tabela">
            <tr class="destacar">
                <td
                    tabindex="1"
                    align="center"><b>CNPJ/CPF</b></td>
                <td
                    tabindex="2"
                    align="center"><b>Nome do Proponente</b></td>
                <td
                    tabindex="3"
                    align="center"><b>Tipo de Pessoa</b></td>
            </tr>
            <tr>
                <td
                    v-if="proponente.CNPJCPF"
                    tabindex="4"
                    align="center">
                    <SalicFormatarCpfCnpj :cpf="proponente.CNPJCPF"/>
                </td>
                <td
                    v-else
                    tabindex="4"
                    align="center">Dado não informado!</td>
                <td
                    v-if="proponente.nmeProponente"
                    tabindex="5"
                    align="center">
                    {{ proponente.nmeProponente }}
                </td>
                <td
                    v-else
                    tabindex="5"
                    align="center">Dado não informado!</td>
                <td
                    tabindex="6"
                    align="center">{{ tipoProponente }}</td>
            </tr>
        </table>
    </div>
</template>

<script type="text/javascript">

import { mapGetters } from 'vuex';
import SalicFormatarCpfCnpj from '@/components/SalicFormatarCpfCnpj';

export default{
    components: {
        SalicFormatarCpfCnpj,
    },
    data() {
        return {
            codPronac: '510115',
            nmeProjeto: '',
            ProponenteInabilitado: false,
            proponentes: [
                { CNPJCPF: '12378965215', nmeProponente: 'Teste Teste' },
            ],
        };
    },
    computed: {
        tipoProponente(campo) {
            for (campo of this.proponentes) {
                if (String(campo.CNPJCPF).length > 0) {
                    if (String(campo.CNPJCPF).length === 11) {
                        return 'Pessoa Física';
                    } if (String(campo.CNPJCPF).length === 14) {
                        return 'Pessoa Jurídica';
                    }
                    return 'CPF/CNPJ inválido!';
                }
                return 'Dado não informado!';
            }
        },
        ...mapGetters({
            dadosProjeto: 'projeto/projeto',
        }),
    },
};
</script>
