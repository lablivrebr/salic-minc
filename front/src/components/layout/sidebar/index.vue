<template>
    <v-hover
        open-delay="250"
    >
        <v-navigation-drawer
            app
            clipped
            fixed
            hide-overlay
            v-model="drawerLeft"
            left
            :mini-variant="mini && !hover"
            slot-scope="{ hover }"
        >
            <v-list class="" dense two-line color="primary">
                <v-list-tile avatar>
                    <v-list-tile-avatar>
                        <v-icon class="green lighten-1 white--text">{{ infos.icone_ativo }}</v-icon>
                    </v-list-tile-avatar>

                    <v-list-tile-content>
                        <v-list-tile-title>{{ infos.titulo }}</v-list-tile-title>
                        <v-list-tile-sub-title>{{ infos.descricao }}</v-list-tile-sub-title>
                    </v-list-tile-content>

                    <v-list-tile-action>
                        <v-btn
                            icon
                            ripple
                            @click.stop="mini = !mini"
                        >
                            <v-icon color="grey lighten-1" v-if="mini">lock_open</v-icon>
                            <v-icon color="grey lighten-1" v-else>lock</v-icon>
                        </v-btn>
                    </v-list-tile-action>
                </v-list-tile>
            </v-list>

            <v-list class="my-0" dense >
                <template v-if="items" v-for="(item) in items">
                    <v-list-group
                        v-if="item.submenu"
                        :prepend-icon="item.icon"
                    >
                        <v-list-tile slot="activator">
                            <v-list-tile-title><span v-html="item.label"></span></v-list-tile-title>
                        </v-list-tile>

                        <template v-for="(subMenu) in item.submenu">
                            <v-list-group
                                v-if="subMenu.submenu"
                                no-action
                                sub-group
                            >
                                <v-list-tile slot="activator">
                                    <v-list-tile-title>{{subMenu.label}}</v-list-tile-title>
                                </v-list-tile>

                                <v-list-tile
                                    v-for="(subitem, i) in subMenu.submenu"
                                    :key="i"
                                    :href="subitem.link"
                                >
                                    <v-list-tile-title v-text="subitem.label"></v-list-tile-title>
                                    <v-list-tile-action if="subitem.icon">
                                        <v-icon v-if="subitem.icon" v-text="subitem.icon"></v-icon>
                                    </v-list-tile-action>
                                </v-list-tile>
                            </v-list-group>
                            <v-list-tile v-else :href="subMenu.link">
                                <span v-html="subMenu.label"></span>
                            </v-list-tile>
                        </template>
                    </v-list-group>
                    <v-list-tile v-else-if="item.link" :href="item.link">
                        <v-list-tile-action v-if="item.icon">
                            <v-icon>{{item.icon}}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-title><span v-html="item.label"></span></v-list-tile-title>
                    </v-list-tile>
                    <v-divider></v-divider>
                </template>
            </v-list>
        </v-navigation-drawer>
    </v-hover>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';

    export default {
        name: 'SalicSidebarEsquerda',
        data() {
            return {
                drawerLeft: false,
                mini: true,
                items: [],
                infos: {},
            };
        },
        computed: {
            ...mapGetters({
                statusSidebarEsquerda: 'layout/getStatusSidebarEsquerda',
                dadosMenu: 'layout/getDadosSidebar',
            }),
        },
        watch: {
            statusSidebarEsquerda(value) {
                this.drawerLeft = value;
            },
            drawerLeft(value) {
                this.atualizarStatusSidebar(value);
            },
            dadosMenu(val) {
                this.items = val;
                if (val.informacoes) {
                    this.infos = val.informacoes;
                }
                this.drawerLeft = true;
            },
        },
        methods: {
            ...mapActions({
                atualizarStatusSidebar: 'layout/atualizarStatusSidebarEsquerda',
            }),
        },
    };
</script>

<style>
    .v-navigation-drawer--mini-variant .v-list__group__items {
        display: none !important;
    }
</style>
