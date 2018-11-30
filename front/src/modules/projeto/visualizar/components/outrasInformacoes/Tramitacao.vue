<template>
    <div>
        <div v-if="dadosProjeto">
            <UltimaTramitacao :idPronac="dadosProjeto.idPronac"></UltimaTramitacao>
            <TramitacaoProjeto
                    :idPronac="dadosProjeto.idPronac">
            </TramitacaoProjeto>
            <TramitacaoDocumento
                    :idPronac="dadosProjeto.idPronac">
            </TramitacaoDocumento>
        </div>
    </div>
</template>
<script>
    import { mapGetters } from 'vuex';
    import UltimaTramitacao from './components/UltimaTramitacao';
    import TramitacaoProjeto from './components/TramitacaoProjeto';
    import TramitacaoDocumento from './components/TramitacaoDocumento';

    export default {
        name: 'Tramitacao',
        props: ['idPronac'],
        components: {
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

