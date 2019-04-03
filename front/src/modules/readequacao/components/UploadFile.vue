<template>
    <v-card elevation="4">
        <file-pond
            ref="pond"
            name="upload"
            label-idle="<span class='subheading'>CARREGAR ARQUIVO</span>"
            label-file-type-not-allowed="Tipo de arquivo inválido."
            file-validate-type-label-expected-types="Somente {allTypes} serão aceitos."
            label-button-remove-item="Excluir"
            label-button-process-item="Upload"
            allow-image-preview="false"
            :accepted-file-types="formatosAceitos"
            :files="arquivo"
            :server="server"
            @removefile="arquivoAnexado()"
            @processfile="arquivoAnexado()"
        />
    </v-card>
</template>

<script>
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import vueFilePond from 'vue-filepond';
import 'filepond/dist/filepond.min.css';


const FilePond = vueFilePond(FilePondPluginFileValidateType);

export default {
    name: 'UploadFile',
    components: {
        FilePond,
    },
    props: {
        formatosAceitos: { type: String, default: 'application/pdf' },
        arquivoInicial: {},
    },
    data() {
        return {
            arquivo: [],
            server: {
                process:(fieldName, file, metadata, load, error, progress, abort) => {
                    const request = new XMLHttpRequest();
                    request.open('POST', '/');
                    request.abort();

                    progress(true, 0, 1024);
                    load(file);

                    // Should expose an abort method so the request can be cancelled
                    return {
                        abort: () => {
                            // This function is entered if the user has tapped the cancel button
                            request.abort();

                            // Let FilePond know the request has been cancelled
                            abort();
                        }
                    };
                },
                load: (source, load, error, progress, abort, headers) => {
                    error('Arquivo não carregado da base!');

                    progress(true, 0, 1024);
                    load(file);

                    return {
                        abort: () => {
                            // User tapped cancel, abort our ongoing actions here

                            // Let FilePond know the request has been cancelled
                            abort();
                        }
                    };
                },
                revert: null,
                restore: null,
                fetch: null,
            },
        };
    },
    computed: {},
    methods: {
        arquivoAnexado() {
            if (this.$refs.pond.getFiles()[0]) {
                const payload = this.$refs.pond.getFiles()[0].file;
                this.$emit('arquivo-anexado', payload);
            } else {
                this.$emit('arquivo-removido', undefined);
            }
        },
    },
    created() {
    }
};
</script>
