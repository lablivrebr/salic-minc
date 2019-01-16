<template>
    <v-card id="create">
        active: {{ active }}
        <v-fab-transition>
            <v-btn
                v-if="active"
                v-model="fab"
                color="blue darken-2"
                dark
                fab
                @click="dialog = !dialog"
            >
                <v-icon>edit</v-icon>
            </v-btn>
        </v-fab-transition>

        <Proposta
            v-if="Object.keys(produto).length > 0"
            :idpreprojeto="produto.idProjeto"/>

        <v-dialog
            v-model="dialog"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
        >
            <v-card tile>
                <v-toolbar
                    card
                    dark
                    color="primary">
                    <v-btn
                        icon
                        dark
                        @click="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>
                        Análise de conteúdo - Produto:
                        {{ produto.dsProduto }}
                    </v-toolbar-title>
                    <v-spacer/>
                    <v-toolbar-items>
                        <v-btn
                            dark
                            flat
                            @click="dialog = false">Save</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card-text>
                    <form>
                        <v-text-field
                            v-model="name"
                            :error-messages="nameErrors"
                            :counter="10"
                            label="Name"
                            required
                            @input="$v.name.$touch()"
                            @blur="$v.name.$touch()"
                        />
                        <v-text-field
                            v-model="email"
                            :error-messages="emailErrors"
                            label="E-mail"
                            required
                            @input="$v.email.$touch()"
                            @blur="$v.email.$touch()"
                        />
                        <v-select
                            v-model="select"
                            :items="items"
                            :error-messages="selectErrors"
                            label="Item"
                            required
                            @change="$v.select.$touch()"
                            @blur="$v.select.$touch()"
                        />
                        <v-checkbox
                            v-model="checkbox"
                            :error-messages="checkboxErrors"
                            label="Do you agree?"
                            required
                            @change="$v.checkbox.$touch()"
                            @blur="$v.checkbox.$touch()"
                        />

                        <v-btn @click="submit">submit</v-btn>
                        <v-btn @click="clear">clear</v-btn>
                    </form>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
import Proposta from '@/modules/proposta/visualizar/Proposta';
import { validationMixin } from 'vuelidate';
import { required, maxLength, email } from 'vuelidate/lib/validators';


export default {
    name: 'AnaliseDeConteudo',
    components: { Proposta },
    mixins: [validationMixin],
    props: {
        produto: {
            type: Object,
            default: () => {},
        },
        active: {
            type: Boolean,
            default: false,
        },
    },
    validations: {
        name: { required, maxLength: maxLength(10) },
        email: { required, email },
        select: { required },
        checkbox: {
            checked(val) {
                return val;
            },
        },
    },
    data() {
        return {
            dialog: false,
            overlay: true,
            direction: 'top',
            fab: false,
            fling: false,
            hover: false,
            tabs: null,
            top: true,
            right: true,
            bottom: false,
            left: false,
            transition: 'slide-y-reverse-transition',
            name: '',
            email: '',
            select: null,
            items: [
                'Item 1',
                'Item 2',
                'Item 3',
                'Item 4',
            ],
            checkbox: false,
        };
    },
    computed: {
        checkboxErrors() {
            const errors = [];
            if (!this.$v.checkbox.$dirty) return errors;
            !this.$v.checkbox.checked && errors.push('You must agree to continue!');
            return errors;
        },
        selectErrors() {
            const errors = [];
            if (!this.$v.select.$dirty) return errors;
            !this.$v.select.required && errors.push('Item is required');
            return errors;
        },
        nameErrors() {
            const errors = [];
            if (!this.$v.name.$dirty) return errors;
            !this.$v.name.maxLength && errors.push('Name must be at most 10 characters long');
            !this.$v.name.required && errors.push('Name is required.');
            return errors;
        },
        emailErrors() {
            const errors = [];
            if (!this.$v.email.$dirty) return errors;
            !this.$v.email.email && errors.push('Must be valid e-mail');
            !this.$v.email.required && errors.push('E-mail is required');
            return errors;
        },
    },
    methods: {
        submit() {
            this.$v.$touch();
        },
        clear() {
            this.$v.$reset();
            this.name = '';
            this.email = '';
            this.select = null;
            this.checkbox = false;
        },
    },
};
</script>

<style>
    /* This is for documentation purposes and will not be needed in your application */
    #create {
        position: relative;
    }
    #create .v-btn--floating,
    #create .v-speed-dial {
        position: fixed;
        top: 230px;
        right: 5%;
    }

    #create .v-btn--floating {
        position: fixed;
        top: 230px;
        right: 5%;
    }
</style>
