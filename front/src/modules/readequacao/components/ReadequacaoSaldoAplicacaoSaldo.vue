<template>
    <div class="col s3">
        <div v-if="!disabled">
            <label>Saldo dispon&iacute;vel *</label>
            <input-money
	      ref="readequacaoSaldo"
              :value="dadosReadequacao.dsSolicitacao"
	      v-on:blur.native="alterarSaldo"
	      >
            </input-money>
        </div>
        <div v-else>
            <span>Saldo dispon&iacute;vel</span> {{readequacao.saldo }}
        </div>
    </div>
</template>

<script>
import InputMoney from '@/components/InputMoney';
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'ReadequacaoSaldoAplicacaoSaldo',
    components: {
        InputMoney,
    },
    props: [
        'dsSolicitacao',
        'disabled',
    ],
    methods: {
        alterarSaldo(event) {
            const params = {
                "dsSolicitacao": event.target.value,
                "idReadequacao": this.dadosReadequacao.idReadequacao
            };
            this.updateReadequacaoDsSolicitacao(params);
        },
        ...mapActions({
            updateReadequacaoDsSolicitacao: 'readequacao/updateReadequacao',
        }),
    },
    computed: {
        ...mapGetters({
            dadosReadequacao: 'readequacao/readequacao',
        }),
    },
};
</script>
