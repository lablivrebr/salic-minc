<template>
    <div class="tabelas">
        <div class="row">
            <slTabelaSimples :dados="dado"/>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import slTabelaSimples from '@/components/slTabelaSimples';

export default {
    name: 'PropostaHistoricoAvaliacoes',
    components: {
        slTabelaSimples,
    },
    props: ['idpreprojeto'],
    data() {
        return {
            dado: [],
        };
    },
    watch: {
        idpreprojeto(value) {
            this.fetch(value);
        },
    },
    mounted() {
        if (typeof this.idpreprojeto !== 'undefined') {
            this.fetch(this.idpreprojeto);
        }
    },
    methods: {
        fetch(id) {
            if (id) {
                const self = this;
                axios.get(`/proposta/visualizar/obter-historico-avaliacoes/idPreProjeto/${id}`)
                    .then((response) => {
                        self.dado = response.data.data;
                    });
            }
        },
    },
};
</script>
