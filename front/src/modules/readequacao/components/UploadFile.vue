<template>
    <div
        class="pa-3"
    >
        <v-btn
            flat
            class="green darken-1 pa-0"
            color="white"
        >
            <label
                class="blue darken-1 text-xs-center body-2 font-weight-medium white--text text-xs-center"
                style="cursor: pointer; border-radius: .15rem; padding: .55rem; padding-left: 1rem; padding-right: 1rem;"
            >
                {{ textoBotao }}
                <input
                    id="arquivo"
                    ref="file"
                    type="file"
                    style="display:none"
                    @change="handleFileUpload()"
                >
                <v-icon
                    small
                    color="white"
                >note_add</v-icon>
            </label>
        </v-btn>
        <v-btn
            v-if="possuiDocumento"
            flat
            class="green darken-1"
            color="white"
            @click="abrirArquivo(idDocumento)"
        >
            VISUALIZAR&nbsp;
            <v-icon small>visibility</v-icon>
        </v-btn>
        <v-btn
            v-if="possuiDocumento"
            flat
            class="red"
            color="white"
            @click="removerArquivo()"
        >
            EXCLUIR
            <v-icon small>
                delete
            </v-icon>
        </v-btn>
    </div>
</template>

<script>
import _ from 'lodash';
import abrirArquivo from '../mixins/abrirArquivo';
import Carregando from '@/components/CarregandoVuetify';

export default {
    name: 'UploadFile',
    components: {
        Carregando,
    },
    mixins: [abrirArquivo],
    props: {
        formatosAceitos: {
            type: String,
            default: 'application/pdf',
        },
        idDocumento: {
            type: [
                Number,
                String,
            ],
            default: 0,
        },
    },
    data() {
        return {
            server: {
                process: (fieldName, file, metadata, load, error, progress, abort) => {
                    const request = new XMLHttpRequest();
                    request.open('POST', '/');
                    request.abort();
                    progress(true, 0, 1024);
                    load(file);
                    return {
                        abort: () => {
                            // Let FilePond know the request has been cancelled
                            abort();
                        },
                    };
                },
                load: null,
                revert: null,
                fetch: null,
            },
            file: '',
        };
    },
    computed: {
        textoBotao() {
            return (this.possuiDocumento) ? 'ALTERAR' : 'ADICIONAR';
        },
        possuiDocumento() {
            if (_.isNull(this.idDocumento)
                || this.idDocumento === 0) {
                return false;
            }
            return true;
        },
    },
    methods: {
        handleFileUpload() {
            const file = this.$refs.file.files[0];
            this.file = file;
            if (this.$refs.file.files[0]) {
                const payload = this.$refs.file.files[0];
                this.$emit('arquivo-anexado', payload);
            } else {
                this.$emit('arquivo-removido');
            }
        },
        removerArquivo() {
            this.$emit('arquivo-removido');
        },
    },
};
</script>
