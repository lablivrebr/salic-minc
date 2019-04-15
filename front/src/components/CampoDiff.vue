<template>
    <v-layout>
        <v-flex
            xs5
            offset-xs1
        >
            <div v-html="textDiff.before"/>
        </v-flex>
        <v-flex
            xs5
            offset-xs1
        >
            <div v-html="textDiff.after"/>
        </v-flex>
    </v-layout>
</template>

<script>
import * as Diff from 'diff'

export default {
    name: 'CampoDiff',
    props: {
        originalText: {
            type: String,
            default: '',
        },
        changedText: {
            type: String,
            default: '',
        },
    },
    data() {
        return {
            colors: {
                added: 'green accent-1',
                removed: 'red lighten-3',
                normal: '',
            },
            textDiff: {
                before: '',
                after: '',
            },
        };
    },
    watch: {
        originalText() {
            if (this.originalText !== '') {
                this.showDiff();
            }
        },
    },
    methods: {
        showDiff() {
            let color = '';
            let span = '';
            let first = true;
            const dd = Diff.diffChars(this.originalText, this.changedText);
            dd.forEach((part) => {
                color = part.added ? this.colors.added : part.removed ? this.colors.removed : this.colors.normal;
                span =`<span class="${color}">${part.value}</span>`;
                if (part.removed) {
                    this.textDiff.before += span;
                } else if (part.added) {
                    this.textDiff.after += span;
                } else {
                    this.textDiff.before += span;
                    this.textDiff.after += span;
                }
            });
        },
    },
};
</script>
