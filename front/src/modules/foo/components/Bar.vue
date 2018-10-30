<template>
    <div>
        <h1>Componente Bar</h1>
        <div>
            <h3>Exemplo Requisição Ajax</h3>
            <div v-for="linha in tabela">
                <div>{{ linha }}</div>
            </div>
        </div>
        <div class="card">
            <h3>Exemplo Computed</h3>
            <input v-model="nome" type="text"/>
            <input v-model="sobrenome" type="text"/>
            <h1 v-html="nomeCompleto"></h1>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Bar',
        data() {
            return {
                tabela: {},
                nome: 'Nome',
                sobrenome: 'Sobrenome',
            };
        },
        created() {
            this.obterDados();
        },
        computed: {
            nomeCompleto() {
                return `${this.nome} ${this.sobrenome}`;
            },
        },
        methods: {
            obterDados() {
                const self = this;
                /* eslint-disable-next-line */
                $3.ajax({
                    url: '/foo/foo-rest',
                    data: {},
                }).done(
                    (response) => {
                        self.tabela = response.data;
                    },
                );
            },
        },
    };
</script>
