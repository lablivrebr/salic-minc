<template>
    <v-dialog
        v-model="dialog"
        width="650"
    >
        <v-tooltip slot="activator" bottom>
            <v-btn 
                slot="activator"
                color="green lighten-2"
                text="white"
                flat
                icon
                light
            >
                <v-icon class="material-icons">replay</v-icon>
            </v-btn>
            <span>Devolver Projeto</span>
        </v-tooltip>
        <v-card>
            <v-card-text>
                Você deseja devolver o projeto '{{ pronac }} - {{ nomeProjeto }}' para análise?
            </v-card-text>
            <v-card-actions>
                <v-btn
                    color="success"
                    flat
                    @click="devolver()"
                >
                    Sim
                </v-btn>
                <v-btn
                    color="error"
                    flat
                    @click="dialog = false"
                >
                    Não
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    import { mapActions } from 'vuex';
    import Modal from '@/components/modal';

    export default {
        name: 'Devolver',
        data() {
            return {
                dialog: false,
            };
        },
        props: {
            idPronac: String,
            atual: String,
            proximo: String,
            nomeProjeto: String,
            pronac: String,
        },
        components: {
            Modal,
        },
        methods: {
            ...mapActions({
                setDevolverProjeto: 'avaliacaoResultados/devolverProjeto',
            }),
            devolver() {
                this.dialog = false;
                this.setDevolverProjeto(
                    {
                        idPronac: this.idPronac,
                        atual: this.atual,
                        proximo: this.proximo,
                        idTipoDoAtoAdministrativo: 622,
                    });
                location.reload();
            },
        },
    };
</script>
