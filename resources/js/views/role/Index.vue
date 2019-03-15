<template>
<md-card class="col-12 mt-4" v-if="searched">
    <md-card-content class="pl-0 pr-0 pt-0">
        <md-table v-model="searched.data" md-sort="name" md-sort-order="asc" md-fixed-header>
            <md-table-toolbar>
                <div class="md-toolbar-section-start">
                    <h1 class="md-title text-capitalize">Roles</h1>
                </div>

                <md-field class="md-toolbar-section-end">
                    <md-input placeholder="Buscar..." aria-label="Buscar..." v-model="search" @input="searchOnTable" />
                </md-field>
            </md-table-toolbar>

            <md-table-empty-state
                :md-label="`No se encontraron Roles`"
                :md-description="`Ningún usuario encontrado para esta '${search}' consulta. Intente un término de búsqueda diferente o cree un nuevo rol.`">
            </md-table-empty-state>

            <md-table-row slot="md-table-row" slot-scope="{ item }">
                <md-table-cell md-label="ID" md-sort-by="id" md-numeric>{{ item.id }}</md-table-cell>
                <md-table-cell md-label="Nombre" md-sort-by="date" class="text-capitalize">{{ item.name }}</md-table-cell>
                <md-table-cell md-label="Slug" md-sort-by="slug">{{ item.slug }}</md-table-cell>
                <md-table-cell md-label="Descripción" md-sort-by="rol" class="text-capitalize">{{ item.description }}</md-table-cell>
            </md-table-row>
        </md-table>
        <pagination-material :data="this.searched" :search="search" @dataChange="searched = $event"></pagination-material>
    </md-card-content>
</md-card>
</template>
<script>
export default {
    name: 'userIndex',
    data: () => ({
        search: null,
        searched: null
    }),
    methods: {
        searchOnTable () {
            this.$http.get(`/api/roles?search=${this.search}`).then(response => {
                this.searched = response.data;
            });
        }
    },
    created () {
        this.$http.get(`/api/roles?page=1&search=${this.search}`).then(response => {
            this.searched = response.data;
        });
    }
};
</script>

