<template>
    <div id="conteudo">
        <table class="tabela">
            <tbody>
            <tr>
                <td align="left">
                    <input type="button"
                           class="btn_adicionar"
                           id="objetivos"
                           @click="setActiveTab(1);">
                    <b v-html="dadoComplementar">{{ dadoComplementar }}</b>
                </td>
            </tr>
            <tr v-if="activeTab === 1" align="left" style="padding: 5px">
                <div v-if="dsDadoComplementar" v-html="dsDadoComplementar">
                    <td>{{ dsDadoComplementar }}</td>
                </div>
                <div v-else-if="custosVinculados">
                    <table class="tabela">
                        <thead>
                        <tr>
                            <th>ITEM</th>
                            <th>PERCENTUAL</th>
                        </tr>
                        </thead>
                        <tbody v-for="(custosVinculado, index) in custosVinculados" :key="index">
                        <tr>
                            <td>{{ custosVinculado.Descricao }}</td>
                            <td>{{ custosVinculado.Percentual }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else>N&atilde;o se aplica.</div>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        name: 'TabelaDadosComplementares',
        props: ['dadoComplementar', 'custosVinculados', 'dsDadoComplementar'],
        data() {
            return {
                activeTab: -1,
            };
        },
        methods: {
            setActiveTab(index) {
                if (this.activeTab === index) {
                    this.activeTab = -1;
                } else {
                    this.activeTab = index;
                }
            },
        },
    };
</script>

