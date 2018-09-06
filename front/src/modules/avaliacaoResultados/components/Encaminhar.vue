<template>

    <div>
        <v-flex xs12 sm6>
            Encaminhar Projeto para An�lise
            124545 - S�o Marcos - espet�culos culturais
        </v-flex>
        <v-flex xs12 sm6 d-flex>
            <v-select
                    v-on:change="getDes"
                    v-model="teste"
                    :items="items"
                    box
                    label="Box style"
            ></v-select>
        </v-flex>
        <v-flex xs6>
            <v-textarea
                    name="input-7-1"
                    label="Default style"
                    value="The Woodman set to work at once, and so sharp was his axe that the tree was soon chopped nearly through."
                    hint="Hint text"
            ></v-textarea>
        </v-flex>
        <v-btn color="success">Success</v-btn>
        <v-btn v-on:click="consoleNosDestinatarios()">Cancelar</v-btn>
{{destinatarios}}
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import ModalTemplate from '@/components/modal';

export default {
    name: 'Painel',
    data() {
        return {
            teste: 'asdasdasd',
            items: [{ text: 'Item 2', value: '2'}, { text: 'Item 1', value: '1'}]
        };
    },
    components: {
        ModalTemplate,
    },
    methods: {
        ...mapActions({
            getDestinatarios: 'avaliacaoResultados/getDestinatariosEncaminhamento'
        }),
        carregarDestinatarios() {
            alert('Olá wouerner');
        },
    },
    mounted() {
        console.log(this.getDestinatarios({
            "idorgao" : 303,
            "idPerfilDestino" : 125,
            "verifica" : "a",
        }));
    },
    computed: mapGetters({
        destinatarios: 'avaliacaoResultados/destinatariosEncaminhamento',

    }),
};
</script>
