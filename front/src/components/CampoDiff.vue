<template>
    <v-layout>
        <v-flex
            md6
            sm12
            xs12
        >
            <v-card
                flat
            >
                <v-card-title
                    class="subheading"
                >
                    <v-btn
                        fab
                        depressed
                        small
                        class="green lighten-1"
                    >
                        <v-icon color="white">menu</v-icon>
                    </v-btn>
                    Versão original
                </v-card-title>
                <v-card-text
                    v-html="textDiff.before"
                />
                />
            </v-card>
        </v-flex>
        <v-spacer/>
        <v-flex
            md6
            sm12
            xs12
        >
            <v-card
                flat
            >
                <v-card-title
                    class="subheading"
                >
                    <v-btn
                        fab
                        depressed
                        small
                        class="green lighten-1"
                    >
                        <v-icon color="white">playlist_add</v-icon>
                    </v-btn>
                    Versão alterada
                </v-card-title>
                <v-card-text 
                    v-html="textDiff.after"
                />
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
import * as Diff from 'diff';

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
        method: {
            type: String,
            default: 'diffWordsWithSpace',
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
        changedText() {
            if (this.changedText !== '') {
                this.showDiff();
            }
        },
    },
    created() {
        this.showDiff();
    },
    methods: {
        makeDiff(original, changed) {
            /** see all available methods at https://www.npmjs.com/package/diff */
            let dd = {};
            switch (this.method) {
            case 'diffChars':
                dd = Diff.diffChars(original, changed);
                break;
            case 'diffWords':
                dd = Diff.diffWords(original, changed);
                break;
            case 'diffWordsWithSpacediffWords':
                dd = Diff.diffWordsWithSpacediffWords(original, changed);
                break;
            case 'diffLines':
                dd = Diff.diffLines(original, changed);
                break;
            default:
                dd = Diff.diffWordsWithSpace(original, changed);
                break;
            }
            return dd;
        },
        showDiff() {
            this.textDiff.after = '';
            this.textDiff.before = '';
            const dd = this.makeDiff(this.originalText, this.changedText);
            dd.forEach((part) => {
                let color = '';
                let span = '';
                if (part.added === true) {
                    color = this.colors.added;
                } else if (part.removed === true) {
                    color = this.colors.removed;
                } else {
                    color = this.colors.normal;
                }
                span = `<span class="${color}">${part.value}</span>`;
                if (part.removed === true) {
                    this.textDiff.before += span;
                } else if (part.added === true) {
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
