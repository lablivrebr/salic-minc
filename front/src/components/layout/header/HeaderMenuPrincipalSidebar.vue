<template>
    <v-navigation-drawer
        fixed
        temporary
        v-model="drawerRight"
        right
    >
        <v-list class="my-1">
            <template  v-for="(item) in dadosMenu">
                <v-list-group
                    v-if="item.menu"
                    :prepend-icon="item.icon"
                >
                    <v-list-tile slot="activator">
                        <v-list-tile-title><span v-html="item.label"></span></v-list-tile-title>
                    </v-list-tile>

                    <template v-for="(subMenu) in item.menu">
                        <v-list-group
                            v-if="subMenu.menu"
                            no-action
                            sub-group
                        >
                            <v-list-tile slot="activator">
                                <v-list-tile-title>{{subMenu.label}}</v-list-tile-title>
                            </v-list-tile>

                            <v-list-tile
                                v-for="(subitem, i) in subMenu.menu"
                                :key="i"
                                :href="'/' + subitem.url.module + '/' + subitem.url.controller + '/' + subitem.url.action"
                            >
                                <v-list-tile-title v-text="subitem.label"></v-list-tile-title>
                                <v-list-tile-action if="subitem.icon">
                                    <v-icon v-if="subitem.icon" v-text="subitem.icon"></v-icon>
                                </v-list-tile-action>
                            </v-list-tile>
                        </v-list-group>
                        <v-list-tile v-else :href="'/' + subMenu.url.module + '/' + subMenu.url.controller + '/' + subMenu.url.action">
                            <span v-html="subMenu.label"></span>
                        </v-list-tile>
                    </template>
                </v-list-group>
                <v-list-tile v-else :href="'/' + item.url.module + '/' + item.url.controller + '/' + item.url.action">
                    <v-list-tile-action v-if="item.icon">
                        <v-icon>{{item.icon}}</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title><span v-html="item.label"></span></v-list-tile-title>
                </v-list-tile>
                <v-divider></v-divider>
            </template>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';

    export default {
        name: 'MenuPrincipal',
        props: {
            dadosMenu: {},
        },
        data() {
            return {
                drawerRight: false,
            };
        },
        computed: {
            ...mapGetters({
                statusSidebarDireita: 'layout/getStatusSidebarDireita',
            }),
        },
        watch: {
            statusSidebarDireita(value) {
                this.drawerRight = value;
            },
            drawerRight(value) {
                this.atualizarStatusSidebar(value);
            },
        },
        methods: {
            ...mapActions({
                atualizarStatusSidebar: 'layout/atualizarStatusSidebarDireita',
            }),
        },
    };
</script>
