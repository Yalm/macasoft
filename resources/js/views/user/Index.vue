<template>
<div>
<md-card class="col-12 mt-4" v-if="searched">
    <md-button @click="create()" class="md-raised md-primary mt-3" to="/admin/users/create">Añadir nuevo</md-button>
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
                <md-table-cell md-label="Rol" md-sort-by="rol" class="text-capitalize">{{ item.role.name }}</md-table-cell>
                <md-table-cell md-label="Acciones" >
                    <md-button @click="edit(item)" class="md-icon-button"><md-icon>edit</md-icon></md-button>
                    <md-button @click="active = true; selected = item.id" class="md-icon-button"><md-icon>delete</md-icon></md-button>
                </md-table-cell>
            </md-table-row>
        </md-table>
        <pagination-material :data="this.searched" :search="search" @dataChange="searched = $event"></pagination-material>
    </md-card-content>
</md-card>
<md-dialog :md-fullscreen="false" :md-active.sync="active">
    <md-progress-bar class="md-primary" md-mode="indeterminate" v-if="sending"></md-progress-bar>
    <md-dialog-title>¿Estás seguro?</md-dialog-title>
    <md-dialog-content>¡No podrás revertir esto! </md-dialog-content >

    <md-dialog-actions>
      <md-button @click="active = false" :disabled="sending">Cancelar</md-button>
      <md-button class="md-primary" @click="onConfirm()" :disabled="sending">Confirmar</md-button>
    </md-dialog-actions>
</md-dialog>

<md-snackbar md-position="center" :md-duration="2000" :md-active.sync="showSnackbar" md-persistent>
    <span>Exito. Se elimino su usuario!</span>
    <md-button class="md-primary">Ok</md-button>
</md-snackbar>

</div>
</template>
<script>
export default {
    name: 'userIndex',
    data: () => ({
        search: null,
        searched: null,
        active: false,
        selected: 0,
        sending: false,
        showSnackbar: false
    }),
    methods: {
        searchOnTable () {
            this.$http.get(`/api/users?search=${this.search}`).then(response => {
                this.searched = response.data;
            });
        },
        edit(item){
            this.$router.push(`/admin/users/${item.id}/edit`);
        },
        onConfirm() {
            this.$http.delete(`/api/users/${this.selected}`).then(response => {
                this.sending = false;
                this.active = false;
                this.showSnackbar = true;
                const index = this.searched.data.findIndex((x => x.id == this.selected));
                this.searched.data.splice(index,1);
            }).catch(err => {
                this.sending = false;
                this.active = false;
                this.showSnackbar = true;
            });
        }
    },
    created () {
        this.$http.get(`/api/users?page=1&search=${this.search}`).then(response => {
            this.searched = response.data;
        });
    }
};
</script>

