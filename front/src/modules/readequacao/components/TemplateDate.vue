<template>
    <v-container fluid>
        <v-layout
            row
            wrap
        >
            <v-flex
                xs10
                md5
            >
                <v-card height="140px">
                    <v-card-title class="grey lighten-2 title">Versão original</v-card-title>
                    <v-divider/>
                    <v-card-text>
                        {{ campo.valor }}
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex
                xs10
                md5
                offset-md2
            >
                <v-card
                    height="140px"
                >
                    <v-card-title
                        class="green lighten-2 title"
                    >
                        Versão readequada
                    </v-card-title>
                    <v-card-actions>
                        <v-menu
                            ref="menu"
                            v-model="menu"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            lazy
                            transition="scale-transition"
                            offset-y
                            full-width
                            max-width="290px"
                            min-width="290px"
                        >
                            <v-text-field
                                slot="activator"
                                v-model="dateFormatted"
                                label="Date"
                                hint="Formato DD/MM/YYYY"
                                persistent-hint
                                prepend-icon="event"
                            />
                            <v-date-picker
                                v-model="date"
                                no-title
                                locale="pt-br"
                                @input="menu = false"
                            />
                        </v-menu>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>

export default {
    name: 'TemplateDate',
    props: {
        campo: { type: Object, default: () => {} },
        dadosReadequacao: { type: Object, default: () => {} },
    },
    data() {
        return {
            date: '',
            dateFormatted: '',
            menu: false,
        };
    },
    watch: {
        date() {
            this.$emit('dados-update', this.date);
            this.dateFormatted = this.formatDate(this.date);
        },
        campo() {
            if (this.campo.valor !== '') {
                let date;
                if (this.dadosReadequacao.dsSolicitacao === ' '
                    || this.dadosReadequacao.dsSolicitacao === '') {
                    date = this.campo.valor;
                }
                date = this.dadosReadequacao.dsSolicitacao;
                if (date === '') {
                    const today = new Date();
                    date = today.getDate();
                }
                this.date = this.prepareDate(date);
            }
        },
    },
    methods: {
        prepareDate(date) {
            const [day, month, year] = date.substr(0, 10).split('-');
            return `${day}-${month}-${year}`;
        },
        formatDate(date) {
            if (!date) {
                return null;
            }
            if (date.includes('-')) {
                const [year, month, day] = date.split('-');
                return `${day}/${month}/${year}`;
            }
            return this.parseDate(date);
        },
        parseDate(date) {
            if (!date) {
                return null;
            }
            const [day, month, year] = date.split('/');
            return `${day.padStart(2, '0')}/${month.padStart(2, '0')}/${year}`;
        },
    },
};
</script>
