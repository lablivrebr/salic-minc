<template>
    <select
        ref="combo"
        v-model="selecionado"
        style="width: 75px; display: inline-block;"
        tabindex="-1"
        class="browser-default"
        @change="valorSelecionado(selecionado)">
        <option
            v-for="item in items"
            :value="item">{{ item }}%</option>
    </select>
</template>

<script>
export default {
    name: 'SalicSelectPercent',
    props: {
        disabled: {
            type: Boolean,
            default: false,
        },
        maximoCombo: {},
        selected: {
            default: 0,
        },
    },
    data() {
        return {
            retorno: 1,
            selecionado: this.maximoCombo,
        };
    },
    computed: {
        items() {
            const total = [];
            for (let i = this.maximoCombo; i >= 0; i--) {
                total.push(parseInt(i));
            }
            return total;
        },
    },
    watch: {
        selected(val) {
            this.selecionado = parseInt(val);
        },
        disabled() {
            this.$refs.combo.disabled = this.disabled;
            if (this.disabled) {
                this.value = 0;
            }
        },
    },
    mounted() {
        this.$refs.combo.disabled = this.disabled;
    },
    methods: {
        valorSelecionado(value) {
            this.retorno = value;
            this.$emit('evento', parseInt(this.retorno));
        },
    },
};
</script>

<style scoped>

</style>
