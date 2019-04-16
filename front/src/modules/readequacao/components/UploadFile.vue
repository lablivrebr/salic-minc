<template>
    <v-card
        elevation="4"
    >
        <file-pond
            ref="pond"
            :server="server"
            :accepted-file-types="formatosAceitos"
            :label-idle="textoBotao"
            name="upload"
            label-file-type-not-allowed="Tipo de arquivo inválido."
            file-validate-type-label-expected-types="Somente {allTypes} serão aceitos."
            label-button-remove-item="Excluir"
            label-button-process-item="Upload"
            allow-image-preview="false"
            @removefile="arquivoAnexado()"
            @processfile="arquivoAnexado()"
        />
    </v-card>
</template>

<script>
import _ from 'lodash';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import vueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import Carregando from '@/components/CarregandoVuetify';

const FilePond = vueFilePond(FilePondPluginFileValidateType);

export default {
    name: 'UploadFile',
    components: {
        Carregando,
        FilePond,
    },
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
        };
    },
    computed: {
        textoBotao() {
            const texto = (_.isNull(this.idDocumento)) ? 'CARREGAR ARQUIVO' : 'ALTERAR ARQUIVO';
            return `<span class='subheading'>${texto}</span>`;
        },
    },
    methods: {
        arquivoAnexado() {
            if (this.$refs.pond.getFiles()[0]) {
                const payload = this.$refs.pond.getFiles()[0].file;
                this.$emit('arquivo-anexado', payload);
            } else {
                this.$emit('arquivo-removido');
            }
        },
    },
};
</script>
