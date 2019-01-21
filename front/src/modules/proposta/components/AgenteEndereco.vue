<template>
    <v-card
        class="mb-2">
        <v-card-title
            primary
            class="title">Endereços</v-card-title>
        <v-data-table
            :headers="headers"
            :items="enderecos"
            class="elevation-1"
        >
            <template
                slot="items"
                slot-scope="props">
                <td>{{ props.item.tipoendereco }}</td>
                <td>{{ props.item.dstipologradouro }}</td>
                <td>{{ props.item.logradouro }}</td>
                <td>{{ props.item.numero }}</td>
                <td>{{ props.item.complemento }}</td>
                <td>{{ props.item.bairro }}</td>
                <td>{{ props.item.municipio }}</td>
                <td>{{ props.item.uf }}</td>
                <td>{{ props.item.cep }}</td>
            </template>
        </v-data-table>
    </v-card>
</template>
<script>
import { utils } from '@/mixins/utils';

export default {
    name: 'AgenteEndereco',
    mixins: [utils],
    props: {
        enderecos: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            headers: [
                { text: 'Tipo de Endereço', value: 'tipoendereco' },
                { text: 'Tipo do Logradouro', value: 'dstipologradouro' },
                { text: 'Logradouro', value: 'logradouro' },
                { text: 'Número', value: 'numero' },
                { text: 'Complemento', value: 'complemento' },
                { text: 'Bairro', value: 'bairro' },
                { text: 'Cidade', value: 'municipio' },
                { text: 'UF', value: 'uf' },
                { text: 'Cep', value: 'cep' },
            ],
        };
    },
};
</script>
