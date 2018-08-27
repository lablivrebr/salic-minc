<template>
    <div>
       TipoAvaliaca 
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import ModalTemplate from '@/components/modal';

export default {
    name: 'Painel',
    data() {
        return {
            DadoNr: null,
        };
    },
    components: {
        ModalTemplate,
    },
    methods: {
        ...mapActions({
            criarRegistro: 'foo/criarRegistro',
            modalOpen: 'modal/modalOpen',
            modalClose: 'modal/modalClose',
        }),
        fecharModal() {
            // eslint-disable-next-line
            $3('#modalTemplate').modal('close');
            this.modalClose();
        },
    },
    computed: mapGetters({
        modalVisible: 'modal/default',
    }),
};
</script>
