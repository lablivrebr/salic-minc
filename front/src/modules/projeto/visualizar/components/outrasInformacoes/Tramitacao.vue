<template>
    <div id="conteudo">
        <div v-if="dadosProjeto">
            <IdentificacaoProjeto :pronac="dadosProjeto.Pronac"
                                  :nomeProjeto="dadosProjeto.NomeProjeto">
            </IdentificacaoProjeto>
            <fieldset style="margin: 0px;">
                <legend>&Uacute;ltima Tramita&ccedil;&atilde;o</legend>
                <UltimaTramitacao :idPronac="dadosProjeto.idPronac"></UltimaTramitacao>
            </fieldset>
            <fieldset style="margin: 0px;">
                <legend>Tramita&ccedil;&atilde;o do Projeto</legend>
                <TramitacaoProjeto
                        :idPronac="dadosProjeto.idPronac">
                </TramitacaoProjeto>
            </fieldset>
            <fieldset style="margin: 0px;">
                <legend>Tramita&ccedil;&atilde;o de Documentos</legend>
                <TramitacaoDocumento
                        :idPronac="dadosProjeto.idPronac">
                </TramitacaoDocumento>
            </fieldset>
        </div>
    </div>
</template>
<script>
    import { mapGetters } from 'vuex';
    import IdentificacaoProjeto from './IdentificacaoProjeto';
    import UltimaTramitacao from './components/UltimaTramitacao';
    import TramitacaoProjeto from './components/TramitacaoProjeto';
    import TramitacaoDocumento from './components/TramitacaoDocumento';

    export default {
        name: 'Tramitacao',
        props: ['idPronac'],
        components: {
            IdentificacaoProjeto,
            UltimaTramitacao,
            TramitacaoProjeto,
            TramitacaoDocumento,
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
            }),
        },
    };
</script>

