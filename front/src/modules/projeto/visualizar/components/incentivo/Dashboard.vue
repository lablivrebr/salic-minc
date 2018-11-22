<template>
    <v-container grid-list-xl fluid class="pa-0">
        <v-layout row wrap>
            <v-flex xs12>
                <v-card color="blue-grey darken-2" class="white--text">
                    <v-card-title primary-title>
                        <div>
                            <h3 class="headline">{{ dadosProjeto.NomeProjeto }}</h3>
                            <v-divider></v-divider>
                            <div>{{ dadosProjeto.Proponente }}</div>
                        </div>
                    </v-card-title>
                </v-card>
            </v-flex>
        </v-layout>

        <!--<v-layout row wrap>-->
        <!--<v-flex xs12>-->
        <!--<v-card color="blue-grey darken-2" class="white&#45;&#45;text">-->
        <!--<v-layout row>-->
        <!--<v-flex xs12>-->
        <!--<v-card-title primary-title>-->
        <!--<div>-->
        <!--<div class="headline">{{ dadosProjeto.NomeProjeto }}</div>-->
        <!--<div>{{ dadosProjeto.Proponente }} ({{ dadosProjeto.CNPJ_CPF }})</div>-->
        <!--<div>Pronac: {{ dadosProjeto.Pronac }}</div>-->
        <!--</div>-->
        <!--</v-card-title>-->
        <!--</v-flex>-->
        <!--</v-layout>-->
        <!--</v-card>-->
        <!--</v-flex>-->
        <!--</v-layout>-->

        <v-layout row wrap>
            <v-flex sm12 xs12 md6>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-card>
                            <v-card-text>
                                <v-layout row wrap class="">
                                    <v-flex sm12 xs12 md4>
                                        <b>Pronac</b>
                                        <p>
                                            <SalicTextoSimples :texto="dadosProjeto.Pronac"/>
                                        </p>
                                    </v-flex>
                                    <v-flex sm12 xs12 md4>
                                        <b>UF</b>
                                        <p>
                                            <SalicTextoSimples :texto="dadosProjeto.UfProjeto"/>
                                        </p>
                                    </v-flex>
                                    <v-flex sm12 xs12 md4>
                                        <b>Mecanismo</b>
                                        <p>
                                            <SalicTextoSimples :texto="dadosProjeto.Mecanismo"/>
                                        </p>
                                    </v-flex>
                                </v-layout>
                                <v-layout row wrap class="">
                                    <v-flex sm12 xs12 md4>
                                        <b>&Aacute;rea Cultural</b>
                                        <p>
                                            <SalicTextoSimples :texto="dadosProjeto.Area"/>
                                        </p>
                                    </v-flex>
                                    <v-flex sm12 xs12 md4>
                                        <b>Segmento Cultural</b>
                                        <p>
                                            <SalicTextoSimples :texto="dadosProjeto.Segmento"/>
                                        </p>
                                    </v-flex>
                                    <v-flex sm12 xs12 md4>
                                        <b>Enquadramento</b>
                                        <p>
                                            <SalicTextoSimples :texto="dadosProjeto.Enquadramento"/>
                                        </p>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                </v-layout>

                <v-layout row wrap>
                    <v-flex xs12>
                        <v-card>
                            <v-card-text>
                                <v-layout row wrap class="">
                                    <v-flex sm12 xs12 md4>
                                        <b>N&ordm; Proposta</b>
                                        <p>
                                            <router-link v-if="dadosProjeto.idPreProjeto"
                                                         :to="{ name: 'proposta', params: { idPronac: idPronac }}">
                                                {{ dadosProjeto.idPreProjeto }}
                                            </router-link>
                                            <SalicTextoSimples v-else :texto="dadosProjeto.idPreProjeto"/>
                                        </p>
                                    </v-flex>
                                    <v-flex sm12 xs12 md4>
                                        <b>Data Fixa</b>
                                        <p>
                                            <SalicTextoSimples :texto="dadosProjeto.DataFixa"/>
                                        </p>
                                    </v-flex>
                                    <v-flex sm12 xs12 md4>
                                        <b>Processo</b>
                                        <p>
                                            <SalicTextoSimples :texto="dadosProjeto.Processo"/>
                                        </p>
                                    </v-flex>
                                </v-layout>
                                <v-layout row wrap class="">
                                    <v-flex sm12 xs12 md4>
                                        <b>Prorroga&ccedil;&atilde;o autom&aacute;tica</b>
                                        <p>
                                            <SalicTextoSimples :texto="dadosProjeto.ProrrogacaoAutomatica"/>
                                        </p>
                                    </v-flex>
                                    <v-flex sm12 xs12 md8>
                                        <b>Plano de Execu&ccedil;&atilde;o Imediata</b>
                                        <p>
                                            <SalicTextoSimples :texto="dadosProjeto.PlanoExecucaoImediata"/>
                                        </p>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                </v-layout>

            </v-flex>
            <v-flex lg6 md6 sm12 xs12>
                <v-card>
                    <v-card-text>
                        <e-chart
                            :path-option="[
                  ['dataset.source', dataset.campaign],
                  ['color', [color.lightBlue.base, color.indigo.base, color.pink.base, color.green.base, color.cyan.base, color.teal.base]],
                  ['legend.orient', 'horizontal'],
                  ['legend.y', 'bottom'],
                  ['xAxis.show', false],
                  ['yAxis.show', false],
                  ['series[0].type', 'pie'],
                ]"
                            height="350px"
                            width="100%"
                        >
                        </e-chart>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>


        <v-layout row wrap>
            <v-flex xs12>
                <v-tabs
                    centered
                    color="cyan"
                    dark
                    icons-and-text
                >
                    <v-tabs-slider color="yellow"></v-tabs-slider>

                    <v-tab href="#tab-1">
                        Recents
                        <v-icon>phone</v-icon>
                    </v-tab>

                    <v-tab href="#tab-2">
                        Favorites
                        <v-icon>favorite</v-icon>
                    </v-tab>

                    <v-tab href="#tab-3">
                        Orçamento
                        <v-icon>attach_money</v-icon>
                    </v-tab>

                    <v-tab-item
                        :id="'tab-1'"
                    >
                        <v-card flat>
                            <v-card-text>{{ text }}</v-card-text>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item
                        :id="'tab-2'"
                    >
                        <v-card flat>
                            <v-card-text>{{ text }}</v-card-text>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item
                        :id="'tab-3'"
                    >
                        <v-card flat>
                            <v-card-text>
                                <ValoresDoProjeto :idPronac="idPronac" :dadosProjeto="dadosProjeto"/>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                </v-tabs>
            </v-flex>
        </v-layout>

        <v-layout row wrap>
            <!-- mini chart -->
            <v-flex sm12>
                <h4>Mini Chart</h4>
            </v-flex>
            <v-flex lg3 sm6>
                <mini-chart
                    title="Monthly Sales"
                    sub-title="10%"
                    icon="trending_up"
                    :data="dataset.monthVisit"
                    :chart-color="color.blue.base"
                    type="bar"
                >
                </mini-chart>
            </v-flex>
            <v-flex lg3 sm6>
                <mini-chart
                    title="Daily Visit"
                    sub-title="20%"
                    icon="trending_up"
                    :data="dataset.monthVisit"
                    :chart-color="color.green.base"
                    type="area"
                >
                </mini-chart>
            </v-flex>
            <v-flex lg3 sm6>
                <v-card>
                    <v-card-text>
                        <div class="layout row ma-0 align-center justify-space-between">
                            <div class="text-box">
                                <div class="subheading pb-2">Bounce Rate</div>
                                <span class="grey--text">-10% <v-icon small
                                                                      color="green">trending_down</v-icon> </span>
                            </div>
                            <div class="chart">
                                <v-progress-circular
                                    :size="60"
                                    :width="5"
                                    :rotate="360"
                                    :value="10"
                                    color="success"
                                >
                                    10
                                </v-progress-circular>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex lg3 sm6>
                <mini-chart
                    title="Sales Trend"
                    sub-title="10%"
                    icon="trending_down"
                    :data="dataset.monthVisit"
                    :chart-color="color.red.base"
                    type="line"
                >
                </mini-chart>
            </v-flex>
        </v-layout>
        <!-- box chart -->
        <v-layout row wrap>
            <v-flex sm12>
                <h4>Box Chart</h4>
            </v-flex>
            <v-flex lg4 sm6 xs12>
                <box-chart
                    title="Page views"
                    sub-title="10%"
                    icon="trending_up"
                    :data="dataset.monthVisit"
                    :chart-color="[color.blue.darken1]"
                    type="area"
                >
                </box-chart>
            </v-flex>
            <v-flex lg4 sm6 xs12>
                <box-chart
                    title="Monthly Sales"
                    sub-title="10%"
                    icon="trending_up"
                    card-color="pink"
                    :data="dataset.monthVisit"
                    :chart-color="[color.pink.lighten2]"
                    type="bar"
                >
                </box-chart>
            </v-flex>
            <v-flex lg4 sm6 xs12>
                <box-chart
                    card-color="indigo"
                    title="Page views"
                    sub-title="10%"
                    icon="trending_up"
                    :data="dataset.monthVisit"
                    :chart-color="[color.shades.white]"
                    type="line"
                >
                </box-chart>
            </v-flex>
        </v-layout>
        <!-- complex chart -->
        <v-layout row wrap>
            <v-flex sm12>
                <h4>Complex Chart</h4>
            </v-flex>
            <v-flex lg4 sm12>
                <v-card dark color="green">
                    <v-card-title>
                        <div class="layout row ma-0">
                            <div class="subheading">Today</div>
                            <v-spacer></v-spacer>
                            <div class="caption">
                                <v-icon>trending_up</v-icon>
                                20%
                            </div>
                        </div>
                    </v-card-title>
                    <v-card-media class="white--text">
                        <e-chart
                            ref="chart"
                            :path-option="[
                ['dataset.source', dataset.stackData],
                ['grid.bottom', '10%'],
                ['grid.top', '5%'],
                ['xAxis.show', false],
                ['yAxis.show', false],
                ['series[0].type', 'bar'],
                ['series[0].barGap', '-100%'],
                ['series[0].itemStyle.normal.color', 'rgba(0,0,0,0.1)'],
                ['series[0].barWidth', '50%'],
                ['series[1].barWidth', '50%'],
                ['series[1].type', 'bar'],
                ['series[1].itemStyle.normal.color', color.shades.white],
                ]"
                            height="200px"
                            width="100%"
                        >
                        </e-chart>
                    </v-card-media>
                    <v-card-text class="white">
                        <div class="layout row align-center ma-0">
                            <div class="grey--text">
                                <div class="caption">MAXIMUM BOUNCE</div>
                                <div class="subheading mt-2">2500</div>
                            </div>
                            <v-spacer></v-spacer>
                            <div class="">
                                <v-progress-circular
                                    :size="100"
                                    :width="15"
                                    :rotate="360"
                                    :value="10"
                                    color="blue"
                                >
                                    10
                                </v-progress-circular>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex lg4 sm12>
                <v-card class="elevation-0 box-shadow" dark color="pink">
                    <v-card-title>
                        <div class="layout row ma-0">
                            <div class="subheading">Today</div>
                            <v-spacer></v-spacer>
                            <div class="caption">
                                <v-icon>trending_up</v-icon>
                                20%
                            </div>
                        </div>
                    </v-card-title>
                    <v-card-media
                        class="white--text"
                    >
                        <e-chart
                            :path-option="[
                  ['dataset.source', dataset.monthVisit],
                  ['color', [color.pink.lighten2]],
                  ['grid.left', '0'],
                  ['grid.bottom', '0'],
                  ['grid.right', '0'],
                  ['xAxis.show', false],
                  ['yAxis.show', false],
                ]"
                            height="200px"
                            width="100%"
                        >
                        </e-chart>
                    </v-card-media>
                    <v-card-text class="white">
                        <div class="layout row align-center ma-0">
                            <div class="grey--text">
                                <div class="caption">MAXIMUM BOUNCE</div>
                                <div class="subheading mt-2">2500</div>
                            </div>
                            <v-spacer></v-spacer>
                            <div class="">
                                <v-progress-circular
                                    :size="100"
                                    :width="15"
                                    :rotate="360"
                                    :value="10"
                                    color="blue"
                                >
                                    10
                                </v-progress-circular>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex lg4 sm12>
                <v-card color="blue darken-1" dark>
                    <v-card-title>
                        <div class="layout row ma-0">
                            <div class="subheading">Today</div>
                            <v-spacer></v-spacer>
                            <div class="caption">
                                <v-icon>trending_up</v-icon>
                                20%
                            </div>
                        </div>
                    </v-card-title>
                    <v-card-media
                    >
                        <e-chart
                            :path-option="[
                ['dataset.source', dataset.monthVisit],
                ['color', [color.blue.base]],
                ['xAxis.show', false],
                ['xAxis.boundaryGap', false],
                ['grid.left', '0'],
                ['grid.bottom', '0'],
                ['grid.right', '0'],
                ['yAxis.show', false],
                ['series[0].areaStyle', {} ],
                ['series[0].smooth', true ],
              ]"
                            height="200px"
                            width="100%"
                        >
                        </e-chart>
                    </v-card-media>
                    <v-card-text class="white">
                        <div class="layout row align-center ma-0">
                            <div class="grey--text">
                                <div class="caption">MAXIMUM BOUNCE</div>
                                <div class="subheading mt-2">2500</div>
                            </div>
                            <v-spacer></v-spacer>
                            <div class="">
                                <v-progress-circular
                                    :size="100"
                                    :width="15"
                                    :rotate="360"
                                    :value="10"
                                    color="blue"
                                >
                                    10
                                </v-progress-circular>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex lg6 sm12 xs12>
                <v-widget title="Analysis - Donut">
                    <div slot="widget-content">
                        <e-chart
                            :path-option="[
                  ['dataset.source', dataset.campaign],
                  ['color', [color.amber.base, color.indigo.base, color.pink.base, color.green.base, color.teal.base, color.purple.base]],
                  ['legend.orient', 'horizontal'],
                  ['legend.y', 'bottom'],
                  ['xAxis.show', false],
                  ['yAxis.show', false],
                  ['series[0].type', 'pie'],
                  ['series[0].avoidLabelOverlap', true],
                  ['series[0].radius', ['50%', '70%']],
                ]"
                            height="350px"
                            width="100%"
                        >
                        </e-chart>
                    </div>
                </v-widget>
            </v-flex>

            <v-flex lg12 sm12>
                <v-tabs v-model="selectedTab" @input="handleTabChange">
                    <v-tab ripple href="#tab-1">
                        Sales
                    </v-tab>
                    <v-tab ripple href="#tab-2">
                        Visit
                    </v-tab>
                    <v-tabs-items v-model="selectedTab">
                        <v-tab-item id="tab-1" class="d-block">
                            <v-card>
                                <e-chart
                                    :path-option="[
                    ['color',[color.lightBlue.base, color.purple.base]],
                    ['dataset.source', dataset.monthVisit],
                    ['series[0].type', 'bar'],
                    ['series[1].type', 'bar']
                  ]"
                                    height="350px"
                                    width="100%"
                                >
                                </e-chart>
                            </v-card>
                        </v-tab-item>
                        <v-tab-item id="tab-2">
                            <v-card>
                                <e-chart
                                    :path-option="[
                    ['dataset.source', dataset.monthVisit],
                    ['series[0].type', 'line']
                  ]"
                                    height="350px"
                                    width="100%"
                                >
                                </e-chart>
                            </v-card>
                        </v-tab-item>
                    </v-tabs-items>
                </v-tabs>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import { mapGetters } from 'vuex';
    import EChart from '@/components/chart/echart';
    import { campaignData, locationData, monthVisitData, SinData, StackData } from '@/helpers/mock/chart';
    import { projeto } from '@/helpers/mock/chart';
    import Material from 'vuetify/es5/util/colors';
    import MiniChart from '@/components/widgets/chart/MiniChart';
    import BoxChart from '@/components/widgets/chart/BoxChart';
    import VWidget from '@/components/VWidget';
    import { utils } from '@/mixins/utils';
    import SalicTextoSimples from '@/components/SalicTextoSimples';
    import ValoresDoProjeto from './ValoresDoProjeto';

    export default {
        components: {
            BoxChart,
            MiniChart,
            EChart,
            VWidget,
            SalicTextoSimples,
            ValoresDoProjeto,
        },
        mixins: [utils],
        data() {
            return {
                idPronac: this.$route.params.idPronac,
                selectedTab: 'tab-1',
                option: null,
                dataset: {
                    sinData: SinData,
                    monthVisit: monthVisitData,
                    campaign: campaignData,
                    location: locationData,
                    stackData: StackData,
                },
                color: Material,

            };
        },
        created() {
            console.log(this.$refs.chart);
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
            }),
        },
        methods: {
            /* eslint-disable */
            handleTabChange(val, e) {
                // make sure the chart resized while parent from hidden to show
                window.dispatchEvent(new Event('resize'));
            },
        },
    };
</script>
