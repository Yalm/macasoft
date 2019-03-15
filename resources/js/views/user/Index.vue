<template>
<md-card class="col-12 mt-4" v-if="searched">
    <md-button @click="create()" class="md-raised md-primary mt-3">Añadir nuevo</md-button>
    <md-card-content class="pl-0 pr-0 pt-0">
        <md-table v-model="searched.data" md-sort="name" md-sort-order="asc" md-fixed-header>
            <md-table-toolbar>
                <div class="md-toolbar-section-start">
                    <h1 class="md-title text-capitalize">Usuarios</h1>
                </div>

                <md-field class="md-toolbar-section-end">
                    <md-input placeholder="Buscar..." aria-label="Buscar..." v-model="search" @input="searchOnTable" />
                </md-field>
            </md-table-toolbar>

            <md-table-empty-state
                :md-label="`No se encontraron Usuarios`"
                :md-description="`Ningún usuario encontrado para esta '${search}' consulta. Intente un término de búsqueda diferente o cree un nuevo usuario.`">
            </md-table-empty-state>

            <md-table-row slot="md-table-row" slot-scope="{ item }">
                <md-table-cell md-label="ID" md-sort-by="id" md-numeric>{{ item.id }}</md-table-cell>
                <md-table-cell md-label="Nombre" md-sort-by="date" class="text-capitalize">{{ item.name }}</md-table-cell>
                <md-table-cell md-label="Correo" md-sort-by="email">{{ item.email }}</md-table-cell>
                <md-table-cell md-label="Rol" md-sort-by="rol" class="text-capitalize">{{ item.rol_id }}</md-table-cell>
                <md-table-cell md-label="Acciones" >
                    <md-button @click="edit(item)" class="md-icon-button"><md-icon>edit</md-icon></md-button>
                </md-table-cell>
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
        searched: null,
    }),
    methods: {
        searchOnTable () {
            this.$http.get(`/api/users?search=${this.search}`).then(response => {
                this.searched = response.data;
            });
        },
        edit(item){
            this.$router.push(`/admin/users/${item.id}/edit`);
        }
    },
    created () {
        this.$http.get(`/api/users?page=1&search=${this.search}`).then(response => {
            this.searched = response.data;
        });
    }
};
</script>

