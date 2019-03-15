<template>
<div>
    <md-card>
        <md-progress-bar class="md-primary" md-mode="indeterminate" v-if="sending"></md-progress-bar>

        <md-card-header>
            <div class="md-title">Datos del usuario</div>
        </md-card-header>

        <md-card-content class="row">
            <div class="col-lg-2 col-md-3">
                <fileUpload v-if="user.name" @upload="avatar = $event" :disabled="sending" :data="user.avatar"/>
                <p class="text-danger text-center">{{ errors.first('avatar') }}</p>
            </div>

            <div class="col-lg-10 col-md-9">
                <md-field md-clearable v-bind:class="{ 'md-invalid': errors.first('name') }">
                    <md-icon>face</md-icon>
                    <label>Nombre del usuario </label>
                    <md-input required :disabled="sending" v-model="user.name" data-vv-as="nombre" name="name"
                        v-validate="'required|min:5'" data-vv-name="name" maxlength="191"></md-input>
                    <span class="md-error">{{ errors.first('name') }}</span>
                </md-field>

                <md-field md-clearable v-bind:class="{ 'md-invalid': errors.first('email') }">
                    <md-icon>email</md-icon>
                    <label>Correo </label>
                    <md-input required :disabled="sending" v-model="user.email" data-vv-as="correo" name="email"
                    v-validate="'required|min:5|email'" data-vv-name="email" maxlength="191"></md-input>
                    <span class="md-error">{{ errors.first('email') }}</span>
                </md-field>

                <md-field v-bind:class="{ 'md-invalid': errors.first('password') }">
                    <md-icon>lock</md-icon>
                    <label>Contraseña </label>
                    <md-input type="password" required :disabled="sending" v-model="user.password" name="password"
                    data-vv-as="contraseña" v-validate="'min:5|max:10'"
                    data-vv-name="password" maxlength="10"></md-input>
                    <span class="md-error">{{ errors.first('password') }}</span>
                </md-field>

                <md-field v-bind:class="{ 'md-invalid': errors.first('role_id') }">
                    <md-icon>local_offer</md-icon>
                    <label for="role_id">Roles</label>
                    <md-select :disabled="sending" name="role_id" id="role_id" v-model="user.role_id" data-vv-as="rol" data-vv-name="role_id" v-validate="'required'">
                        <md-option v-for="role of roles" :key="role.id" :value="role.id" >{{role.name}}</md-option>
                    </md-select>
                    <span class="md-error">{{ errors.first('role_id') }}</span>
                </md-field>
            </div>
        </md-card-content>
        <md-card-actions>
            <md-button to="/admin/users" :disabled="sending">Atras</md-button>
            <md-button class="md-raised md-primary" :disabled="sending || errors.any()" @click="store()">Actualizar</md-button>
        </md-card-actions>
    </md-card>
    <md-snackbar md-position="center" :md-duration="2000" :md-active.sync="showSnackbar" md-persistent>
        <span>Exito. Se actualizo el usuario!</span>
        <md-button class="md-primary">Ok</md-button>
    </md-snackbar>
</div>
</template>

<script>
import fileUpload from '../../components/FileUploadComponent';
export default {
    name: 'users.edit',
    components: { fileUpload },
    data: () => ({
        sending: false,
        showSnackbar: false,
        roles: [],
        avatar: null,
        user: {},
        id: Number
    }),
    created (){
        this.id =  this.$router.history.current.params.id;
        this.$http.get('/api/roles').then(response => {
            this.roles = response.data.data;
        });
        this.$http.get(`/api/users/${this.id}`).then(response => {
            this.user = response.data;
            console.log(this.user);
        });
    },
    methods: {
        store() {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.sending = true;
                    const fd = this.createFormProduct();
                    this.$http.post(`/api/users/${this.id}`,fd).then(response => {
                        this.sending = false;
                        this.showSnackbar = true;
                        this.$validator.reset();
                    }).catch(err => {
                        if(err.response.status == 422){
                            for(const error in err.response.data){
                                this.$validator.errors.add({
                                    field: error,
                                    msg: err.response.data[error][0]
                                });
                            }
                        }
                        this.sending = false;
                    });
                }
            });
        },
        createFormProduct() {
            const fd = new FormData();
            if(this.avatar != null) {
                fd.append('avatar',this.avatar,this.avatar.name);
            }
            fd.append('name',this.user.name);
            fd.append('email',this.user.email);
            fd.append('password',this.user.password);
            fd.append('role_id',this.user.role_id);
            fd.append('_method','PUT');
            return fd;
        }
    }
}
</script>
