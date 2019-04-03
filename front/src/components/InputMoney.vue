<template>
    <input
      type="text"
      class="validate right-align"
      ref="input"
      v-bind:disabled="false"
      v-bind:value="value"
      v-on:input="updateMoney($event.target.value)"
      v-on:blur="formatValue"
      />
</template>
<script>
import { utils } from '@/mixins/utils';

export default {
    name: 'InputMoney',
    props: {
        value: {
            default: 0,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    },
    mixins: [utils],
    data() {
        return {
            val: 0,
        };
    },
    mounted() {
        this.$refs.input.disabled = this.disabled;
        this.val = this.value;
        this.formatValue();
    },
    methods: {
        formatValue() {
            const num = this.formatFloat(this.$refs.input.value);
            this.$refs.input.value = this.converterParaMoedaPontuado(num);
            this.updateMoney(
                this.formatFloat(this.$refs.input.value),
            );
        },
        formatFloat(value) {
            if (value.search(',') > 0) {
                value = value.replace('.', '');
                value = value.replace(',', '.');
            }
            return value;
        },
        updateMoney(value) {
            this.val = value;
            this.$emit('ev', this.val);
        },
    },
    watch: {
        disabled() {
            this.$refs.input.disabled = this.disabled;
            if (this.disabled) {
                this.val = 0;
            }
        },
    },
};
</script>
