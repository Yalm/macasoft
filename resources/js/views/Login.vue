<template>
    <div class="container">
        <div class="row d-flex justify-content-center pt-5">
            <md-card class="col-lg-5 col-md-6 mt-5 col-11">
                <md-progress-bar class="md-primary" md-mode="indeterminate" v-if="sending"></md-progress-bar>
                <md-card-header>
                    <div class="md-title">INGRESE A SU CUENTA</div>
                </md-card-header>

                <md-card-content>
                    <div class="alert alert-danger" role="alert" v-if="unauthorized">
                        Usuario no autorizado
                    </div>

                    <md-field v-bind:class="{ 'md-invalid': errors.first('email') }">
                        <md-icon>email</md-icon>
                        <label>Dirección de correo electrónico</label>
                        <md-input type='email' name="email" v-model="email" :disabled="sending" required autofocus maxlength="191"
                                data-vv-as="correo" data-vv-name="email" v-validate="'required|email|min:5|max:191'"></md-input>
                        <span class="md-error">{{ errors.first('email') }}</span>
                    </md-field>

                    <md-field v-bind:class="{ 'md-invalid': errors.first('password') }">
                        <md-icon>lock</md-icon>
                        <label>Contraseña </label>
                        <md-input type="password" v-model="password" maxlength="10" required :disabled="sending"
                                data-vv-as="contraseña" data-vv-name="password" v-validate="'min:6|max:10'"></md-input>
                        <span class="md-error">{{ errors.first('password') }}</span>
                    </md-field>

                </md-card-content>

                <md-card-actions>
                    <md-button :disabled="sending || errors.any()" class="md-raised md-primary" @click="login()">Iniciar Sesión</md-button>
                </md-card-actions>

            </md-card>
        </div>
        <md-snackbar md-position="center" :md-duration="4000" :md-active.sync="showSnackbar" md-persistent>
            <span>{{messageSnackbar}}</span>
            <md-button class="md-primary">Ok</md-button>
        </md-snackbar>
    </div>
</template>
<script>
export default {
    data: () => ({
        email: '',
        password: '',
        sending: false,
        showSnackbar: false,
        messageSnackbar: '',
        unauthorized: false
    }),
    methods: {
        login() {
            this.$validator.validateAll().then((result) => {
                if (result){
                    let email = this.email;
                    let password = this.password;
                    this.sending = true;
                    this.unauthorized = false;
                    this.$store.dispatch("login", { email, password })
                        .then( () => {
                            this.$router.push("/");
                            this.sending = false;
                        })
                        .catch(err => {
                            this.sending = false;
                            if(err.response.status == 401) {
                                this.unauthorized = true;
                            }else {
                                this.messageSnackbar = 'Opps..., Algo salio mal.';
                                this.showSnackbar = true;
                            }
                        });
                }
            });
        }
    }
};
</script>
