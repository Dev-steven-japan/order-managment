<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Cliente</h1>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="content container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <template v-if="listRolPermisosByUsuario.includes('cliente.crear')">
                            <router-link class="btn btn-info btn-sm" :to="{name: 'cliente.crear'}">
                                <i class="fas fa-plus-square"></i> Nuevo Cliente
                            </router-link>
                        </template>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Criterios de Búsqueda</h3>
                            </div>
                            <div class="card-body">
                                <form role="form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">Nombre</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" v-model="fillBsqCliente.cNombre" @keyup.enter="getListarClientes">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">#Documento</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" v-model="fillBsqCliente.cDocumento" @keyup.enter="getListarClientes">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-4 offset-4">
                                        <button class="btn btn-flat btn-info btnWidth" @click.prevent="getListarClientes" v-loading.fullscreen.lock="fullscreenLoading">Buscar</button>
                                        <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriteriosBsq">Limpiar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Bandeja de Resultados</h3>
                            </div>
                            <div class="card-body table-responsive">
                                <template v-if="listarClientesPaginated.length">
                                    <table class="table table-hover table-head-fixed text-nowrap projects">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Documento</th>
                                                <th>Correo</th>
                                                <th>Teléfono</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in listarClientesPaginated" :key="index">
                                                <td v-text="item.fullname"></td>
                                                <td v-text="item.document"></td>
                                                <td v-text="item.email"></td>
                                                <td v-text="item.phone"></td>
                                                <td>
                                                    <template v-if="listRolPermisosByUsuario.includes('cliente.editar')">
                                                        <router-link class="btn btn-flat btn-info btn-sm" :to="{name:'cliente.editar', params:{id: item.id}}">
                                                            <i class="fas fa-pencil-alt"></i> Editar
                                                        </router-link>
                                                    </template>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="card-footer clearfix">
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <li class="page-item" v-if="pageNumber > 0">
                                                <a href="#" class="page-link" @click.prevent="prevPage">Ant</a>
                                            </li>
                                            <li class="page-item" v-for="(page, index) in pagesList" :key="index"
                                                :class="[page == pageNumber ? 'active' : '']">
                                                <a href="#" class="page-link" @click.prevent="selectPage(page)"> {{ page+1 }} </a>
                                            </li>
                                            <li class="page-item" v-if="pageNumber < pageCount - 1">
                                                <a href="#" class="page-link" @click.prevent="nextPage" >Post</a>
                                            </li>
                                        </ul>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="callout callout-info">
                                        <h5>No se encontraron resultados...</h5>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                fillBsqCliente: {
                    cNombre : '',
                    cDocumento: ''
                },
                listClientes: [],
                listRolPermisosByUsuario: JSON.parse(sessionStorage.getItem('listRolPermisosByUsuario')),
                fullscreenLoading: false,
                pageNumber: 0,
                perPage: 5
            }
        },
        computed: {
            // Obtener el número de páginas
            pageCount() {
                // 20
                // 5
                // 20 /5 = 4
                let a = this.listClientes.length,
                    b = this.perPage;
                return Math.ceil(a / b);
            },
            // Obtener registros paginados
            listarClientesPaginated(){
                // 0 * 5 = 0
                // 0 + 5 = 5
                // 0 - (5-1)
                // 1 * 5 = 5
                // 5 + 5 = 10
                // 5 - (10-1)
                // 2 * 5 = 10
                // 10 + 5 = 15
                // 10 - (15-1)

                let inicio = this.pageNumber * this.perPage,
                    fin    = inicio + this.perPage;
                return this.listClientes.slice(inicio, fin);
            },
            pagesList() {
                let a = this.listClientes.length,
                    b = this.perPage;
                let pageCount = Math.ceil(a / b);
                let count = 0,
                    pagesArray = [];

                // 0 < 4 => 0
                // 1 < 4 => 0, 1
                // 2 < 4 => 0, 1, 2
                // 3 < 4 => 0, 1, 2, 3
                // 4 < 4 ??
                while (count < pageCount) {
                    pagesArray.push(count);
                    count++;
                }
                return pagesArray;
            }
        },
        methods: {
            limpiarCriteriosBsq(){
                this.fillBsqCliente.cNombre      =   '';
                this.fillBsqCliente.cDocumento   =   '';
            },
            getListarClientes(){
                this.fullscreenLoading = true;

                var url = '/operacion/cliente/getListarClientes'
                axios.get(url, {
                    params: {
                        'cNombre'       :   this.fillBsqCliente.cNombre,
                        'cDocumento'    :   this.fillBsqCliente.cDocumento
                    }
                }).then(response => {
                    this.inicializarPaginacion();
                    this.listClientes   =   response.data;
                    this.fullscreenLoading = false;
                }).catch(error => {
                    if (error.response.status == 401) {
                        this.$router.push({name: 'login'})
                        location.reload();
                        sessionStorage.clear();
                        this.fullscreenLoading = false;
                    }
                })
            },
            nextPage() {
                this.pageNumber++;
            },
            prevPage() {
                this.pageNumber--;
            },
            selectPage(page){
                this.pageNumber = page;
            },
            inicializarPaginacion(){
                this.pageNumber = 0;
            }
        }
    }
</script>

<style>

</style>
