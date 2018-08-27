<template>
    <div>
 <v-layout wrap align-center>
        <v-flex xs12 sm6 d-flex>
          <v-select
            :items="items"
            label="Standard"
          ></v-select>
        </v-flex>

        <v-flex xs12 sm6 d-flex>
          <v-select
            :items="items"
            box
            label="Box style"
          ></v-select>
        </v-flex>

        <v-flex xs12 sm6 d-flex>
          <v-select
            :items="items"
            label="Outline style"
            outline
          ></v-select>
        </v-flex>

        <v-flex xs12 sm6 d-flex>
          <v-select
            :items="items"
            label="Solo field"
            solo
          ></v-select>
        </v-flex>
      </v-layout>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import ModalTemplate from '@/components/modal';

export default {
    name: 'UpdateBar',
    data() {
        return {
            currentRegistro: {
                Codigo: '',
                DadoNr: '',
            },
            items: ['pedro', 'leo'],
        };
    },
    props: ['registroAtivo'],
    components: {
        ModalTemplate,
    },
    methods: {
        ...mapActions({
            atualizarRegistro: 'foo/atualizarRegistro',
            setRegistroAtivo: 'foo/setRegistroAtivo',
            modalOpen: 'modal/modalOpen',
            modalClose: 'modal/modalClose',
        }),
        buildRegistro(event) {
            const DadoNr = event.target.value;
            this.currentRegistro.DadoNr = DadoNr;
            this.currentRegistro.Codigo = this.registro.Codigo;
        },
        checkChangesAndUpdate() {
            if (this.currentRegistro !== this.registro) {
                this.atualizarRegistro(this.currentRegistro);
            }
        },
        fecharModal() {
            // eslint-disable-next-line
            $3('#modalTemplate').modal('close');
            this.modalClose();
        },
    },
    computed: {
        ...mapGetters({
            registro: 'foo/registro',
            modalVisible: 'modal/default',
        }),
    },
};
</script>
