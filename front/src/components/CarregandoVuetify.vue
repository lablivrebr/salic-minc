<template>
    <v-layout
        column
        class="pb-3">
        <v-flex xs12>
            <div class="text-xs-center">
                <div style="padding: 20px">
                    <h3 v-html="text"/>
                </div>
                <v-progress-circular
                    :size="50"
                    indeterminate
                    color="primary"
                />
            </div>
        </v-flex>
    </v-layout>
</template>

<script>
export default {
    name: 'CarregandoVuetify',
    props: {
        text: {
            type: String,
            default: '',
        },
    },
};
</script>
