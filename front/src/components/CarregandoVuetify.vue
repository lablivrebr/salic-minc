<template>
    <div class="text-xs-center">
        <div style="padding-top: 20px">
            <h6 v-html="text"></h6>
        </div>
        <v-progress-circular
                :rotate="180"
                :size="70"
                :width="7"
                :value="value"
                color="primary"
        >
            {{ value }}
        </v-progress-circular>
    </div>
</template>

<script>
    export default {
        name: 'Carregando',
        props: {
            text: {
                type: String,
                default: '',
            },
        },

        data() {
            return {
                interval: {},
                value: 0,
            };
        },
        mounted() {
            this.interval = setInterval(() => {
                if (this.value === 100) {
                    return (this.value = 0);
                }
                this.value += 10;
            }, 1000);
        },
    };
</script>
