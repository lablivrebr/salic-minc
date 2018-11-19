<template>
    <div class="conteudo">
        <legend>Identificação</legend>
        <table class="tabela">
            <tr class="destacar">
                <td align="center"><b>PRONAC</b></td>
                <td align="center"><b>Nome do Projeto</b></td>
            </tr>
            <tr>
                <td align="center">{{dadosProjeto.Pronac}}</td>
                <td align="center">{{dadosProjeto.NomeProjeto}}</td>
            </tr>
        </table>
        <br clear="all">
        <table class="tabela" v-if="ProponenteInabilitado" style="background-color: red;">
            <tr style="background-color: red;">
                <td align="center" style="text-transform: uppercase; color: red;">
                    <b>Proponente Inabilitado</b>
                </td>
            </tr>
        </table>
        <table class="tabela" v-else v-for="(proponente, index) in proponentes" v-bind:key="index">
            <tr class="destacar">
                <td tabindex="1" align="center"><b>CNPJ/CPF</b></td>
                <td tabindex="2" align="center"><b>Nome do Proponente</b></td>
                <td tabindex="3" align="center"><b>Tipo de Pessoa</b></td>
            </tr>
            <tr>
                <td tabindex="4" align="center" v-if="proponente.CNPJCPF">
                    <SalicFormatarCpfCnpj :cpf="proponente.CNPJCPF"/>
                </td>
                <td tabindex="4" align="center" v-else>Dado não informado!</td>
                <td tabindex="5" align="center" v-if="proponente.nmeProponente">
                    {{proponente.nmeProponente}}
                </td>
                <td tabindex="5" align="center" v-else>Dado não informado!</td>
                <td tabindex="6" align="center">{{tipoProponente}}</td>
            </tr>
        </table>
    </div>
</template>

<script type="text/javascript">

    import { mapGetters } from 'vuex';
    import SalicFormatarCpfCnpj from '@/components/SalicFormatarCpfCnpj';

    export default{
        data() {
            return{
                codPronac: '510115', nmeProjeto: '', ProponenteInabilitado: false,
                proponentes:[
                    {CNPJCPF:'12378965215', nmeProponente:'Teste Teste'},
                ],
            };
        },
        components:{
            SalicFormatarCpfCnpj,
        },
        computed:{
            tipoProponente: function(campo){
                for(campo of this.proponentes){
                    if(String(campo.CNPJCPF).length>0){
                        if(String(campo.CNPJCPF).length===11){
                            return 'Pessoa Física';
                        }else if(String(campo.CNPJCPF).length===14){
                            return 'Pessoa Jurídica';
                        }else{
                            return 'CPF/CNPJ inválido!'
                        }
                    }else{
                        return 'Dado não informado!'
                    }
                }
            },
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
            }),
        }
    };
</script>