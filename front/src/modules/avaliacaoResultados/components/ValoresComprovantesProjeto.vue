<script>
    Vue.component('comprovantes-valores',{
        data: function () {
            return {
                any:any
            }
        },
        template:
        '<v-container grid-list-sm>
            <v-layout row wrap>
            <v-flex xs12 sm12 md12>
        <p><b>Projeto:</b> {{projeto.AnoProjeto}}{{projeto.Sequencial}} - {{projeto.NomeProjeto}}</p>
    </v-flex>
    <v-flex xs12 sm12 md12>
    <p><b>Proponente:</b> {{proponente.CgcCpf}} - {{proponente.Nome}}</p>
    </v-flex>
    </v-layout>
    <v-divider></v-divider>
    </v-container>
    <h4 class="text-sm-center">Quantidade de Comprovantes</h4>
    <v-container grid-list-sm>
    <v-layout row wrap>
    <v-flex xs10 sm3 md3 >
    <div>
    <h4 class="label text-sm-right">Total</h4>
        <p class="text-sm-right">{{consolidacaoComprovantes.qtTotalComprovante}}</p>
    </div>
    </v-flex>
    <v-flex xs10 sm3 md3>
    <div>
    <h4 class="label text-sm-right">Validados</h4>
        <p class="text-sm-right">{{consolidacaoComprovantes.qtComprovantesValidadosProjeto}}</p>
    </div>
    </v-flex>
    <v-flex xs10 sm3 md3>
    <div>
    <h4 class="label text-sm-right">Recusados</h4>
        <p class="text-sm-right">{{consolidacaoComprovantes.qtComprovantesRecusadosProjeto}}</p>
    </div>
    </v-flex>
    <v-flex xs10 sm3 md3>
    <div>
    <h4 class="label text-sm-right">Não Avaliados</h4>
    <p class="text-sm-right">{{consolidacaoComprovantes.qtComprovantesNaoAvaliados}}</p>
    </div>
    </v-flex>
    </v-layout>
    <v-divider></v-divider>
    </v-container>
    <h4 class="text-sm-center">Valores Comprovados</h4>
    <v-container grid-list-sm>
    <v-layout row wrap>
    <v-flex xs10 sm3 md3 >
    <div>
    <h4 class="label text-sm-right">Total</h4>
        <p class="text-sm-right">{{consolidacaoComprovantes.vlComprovadoProjeto}}</p>
    </div>
    </v-flex>
    <v-flex xs10 sm3 md3>
    <div>
    <h4 class="label text-sm-right">Validados</h4>
        <p class="text-sm-right">{{consolidacaoComprovantes.vlComprovadoValidado}}</p>
    </div>
    </v-flex>
    <v-flex xs10 sm3 md3>
    <div>
    <h4 class="label text-sm-right">Recusados</h4>
        <p class="text-sm-right">{{consolidacaoComprovantes.vlComprovadoRecusado}}</p>
    </div>
    </v-flex>
    <v-flex xs10 sm3 md3>
    <div>
    <h4 class="label text-sm-right">Não Avaliados</h4>
    <p class="text-sm-right">{{consolidacaoComprovantes.vlNaoComprovado}}</p>
    </div></v-flex></v-layout><v-divider></v-divider></v-container>'

    }),


</script>
