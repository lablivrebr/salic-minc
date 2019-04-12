<template>
    <v-container
        fill-height
        style="min-height:calc(100vh - 400px);"
    >
        <v-layout
            align-center
        >
            <v-flex class="text-xs-center">
                <h1 class="display-2 error--text">
                    Erro! :(
                </h1>
                <p
                    class="headline mt-2"
                    v-html="texto"
                />
                <v-btn
                    v-if="urlRetorno"
                    :href="urlRetorno"
                    outline
                    color="error"
                    v-html="msgUrlRetorno"
                />
                <v-btn
                    v-if="rotaRetorno"
                    :to="rotaRetorno"
                    outline
                    color="error"
                    v-html="msgUrlRetorno"
                />
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
export default {
    name: 'SalicMensagemErro',
    props: {
        texto: {
            type: String,
            default: '',
        },
        urlRetorno: {
            type: String,
            default: '',
        },
        msgUrlRetorno: {
            type: String,
            default: 'Voltar',
        },
        rotaRetorno: {
            type: [String, Object],
            default: '',
        },
    },
};
</script>
