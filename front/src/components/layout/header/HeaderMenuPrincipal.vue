<template>
    <v-toolbar-items class="hidden-sm-and-down">
        <v-menu offset-y v-for="(item, index) in dadosMenu" :key="index">
            <v-btn v-if="item.menu"
                   slot="activator"
                   flat
                   dark
                   small
                   class="caption"
            >
                <span v-html="item.label"></span>
                <!--<v-icon right dark class="ma-0">arrow_drop_down</v-icon>-->
            </v-btn>
            <v-list v-if="item.menu" class="pa-0">
                <v-list-tile
                    v-for="(sub, index) in item.menu"
                    :key="index"
                    :href="('/' + sub.url.module + '/' + sub.url.controller + '/' + sub.url.action)"
                >
                    <v-list-tile-title
                    ><span v-html="sub.label"></span></v-list-tile-title>
                </v-list-tile>
            </v-list>
            <v-btn v-else flat dark small class="caption"
                   :href="'/' + item.url.module + '/' + item.url.controller + '/' + item.url.action">
                <span v-html="item.label"></span>
            </v-btn>
        </v-menu>
    </v-toolbar-items>
</template>

<script>
    export default {
        name: 'MenuPrincipal',
        props: {
            dadosMenu: {},
        },
    };
</script>
