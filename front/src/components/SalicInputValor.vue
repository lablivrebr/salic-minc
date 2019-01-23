<template>
    <v-text-field
        ref="textfield"
        v-bind="$attrs"
        v-model="valueComponent"
    />
</template>

<script>
import MixinsPlanilhas from '@/mixins/planilhas';

export default {
    name: 'SalicInputValor',
    mixins: [MixinsPlanilhas],
    props: {
        value: {
            type: [String, Number],
            default: '',
        },
    },
    data() {
        return {
            valueComponent: '',
        };
    },
    watch: {
        value(v) {
            this.valueComponent = v;
        },
        valueComponent(v) {
            this.$emit('input', v);
        },
    },
    mounted() {
        this.valueComponent = this.value;
    },
};
</script>
