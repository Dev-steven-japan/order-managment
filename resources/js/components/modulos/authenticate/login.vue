<template>
    <div class="login-box">
        <div class="login-logo">
            <router-link :to="{name:'login'}">
                <b>Iniciar Sesión</b>
            </router-link>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Ingresa tus credenciales para Iniciar Sesión</p>

                <form method="post">
                    <div class="input-group mb-3">
                        <vs-input :state="(error)?'danger':''" icon-after @keyup.enter="login" v-model="fillLogin.cEmail" placeholder="Correo Electronico">
                            <template #icon>
                                <i class='fas fa-envelope'></i>
                            </template>
                        </vs-input>
                    </div>
                    <div class="input-group mb-3">
                        <vs-input :state="(error)?'danger':''" type="password" icon-after @keyup.enter="login" v-model="fillLogin.cContrasena" placeholder="Contraseña">
                            <template #icon>
                                <i class='fas fa-lock'></i>
                            </template>
                        </vs-input>
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-12">
                        <span v-if="error">
                            <div v-for="(e, index) in mensajeError" :key="index" v-text="e" class="callout callout-danger"></div>
                        </span>
                    </div>
                </div>

                <div class="social-auth-links text-center mb-3">
                    <button class="btn btn-flat btn-block btn-danger" @click.prevent="login">
                        Iniciar Sesión
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                fillLogin : {
                    cEmail: '',
                    cContrasena: ''
                },
                listRolPermisosByUsuario: [],
                listRolPermisosByUsuarioFilter: [],
                fullscreenLoading: false,
                error: 0,
                mensajeError: []
            }
        },
        methods: {
            login() {
                if (this.validarLogin()) {
                    return;
                }
                const loading = this.$vs.loading({
                    type: 'square',
                    color: '#D5397B',
                    background: '#FFFFFF',
                    text: 'Cargando...'
                })
                var url = '/authenticate/login'
                axios.post(url, {
                    'cEmail'        : this.fillLogin.cEmail,
                    'cContrasena'   : this.fillLogin.cContrasena
                }).then(response => {
                    // console.log(response.data)
                    if (response.data.code == 401) {
                        this.loginFailed();
                    }
                    if (response.data.code == 200) {
                        // this.loginSuccess();
                        this.getListarRolPermisosByUsuario(response.data.authUser)
                    }
                    setTimeout(() => {
                        loading.close()
                    }, 2000)
                })
            },
            getListarRolPermisosByUsuario(authUser){
                var ruta = '/administracion/usuario/getListarRolPermisosByUsuario'
                axios.get(ruta,{
                    params: {
                        'nIdUsuario'    :   authUser.id
                    }
                }).then( response => {
                    this.listRolPermisosByUsuario = response.data;
                    this.filterListarRolPermisosByUsuario(authUser);
                })
            },
            filterListarRolPermisosByUsuario(authUser) {
                let me = this;
                me.listRolPermisosByUsuario.map(function(x, y){
                    me.listRolPermisosByUsuarioFilter.push(x.slug)
                })
                sessionStorage.setItem('listRolPermisosByUsuario', JSON.stringify(me.listRolPermisosByUsuarioFilter));
                sessionStorage.setItem('authUser', JSON.stringify(authUser));
                this.loginSuccess();
            },
            validarLogin() {
                this.error = 0;
                this.mensajeError = [];

                if (!this.fillLogin.cEmail) {
                    this.mensajeError.push('El correo electronico es un campo obligatorio')
                }
                if (!this.fillLogin.cContrasena) {
                    this.mensajeError.push('La contraseña es un campo obligatorio')
                }
                if(this.mensajeError.length){
                    this.error = 1;
                }
                return this.error;
            },
            loginFailed(){
                this.error = 0;
                this.mensajeError = [];
                this.mensajeError.push('Estas credenciales no coinciden con nuestros registros');
                this.fillLogin.cContrasena = '';
                if(this.mensajeError.length){
                    this.error = 1;
                }
                return this.error;
            },
            loginSuccess(){
                this.$router.push({name: 'dashboard.index'})
                location.reload();
            }
        },
    }
</script>

<style>

</style>
