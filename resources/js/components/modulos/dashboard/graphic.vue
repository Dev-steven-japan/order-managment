<template>
    <div class="row">
        <template v-if="listRolPermisosByUsuario.includes('productos.mas.vendidos')">
            <div class="col-md-6">
                <canvas id="myChart" width="400" height="200"></canvas>
            </div>
        </template>
        <template v-if="listRolPermisosByUsuario.includes('ventas.por.dia')">
            <div class="col-md-6">
                <canvas id="myChart2" width="400" height="200"></canvas>
            </div>
        </template>
    </div>
</template>

<script>
    import Chart from 'chart.js'
    export default {
        data() {
            return {
                listProductosMasVendidos: {
                    all: [],
                    name: [],
                    cantidad: [],
                    colores: []
                },
                listVentasPorDia: {
                    all: [],
                    name: [],
                    cantidad: []
                },
                listRolPermisosByUsuario: JSON.parse(sessionStorage.getItem('listRolPermisosByUsuario')),
            }
        },
        mounted() {
            this.getProductosMasVendidos();
            this.getVentasPorDia();
        },
        methods: {
            getProductosMasVendidos(){
                var ruta = '/dashboard/getProductosMasVendidos'
                axios.get(ruta).then( response => {
                    this.listProductosMasVendidos.all = response.data;
                    this.getProductosMasVendidosFiltrar();
                }).catch(error => {
                    if (error.response.status == 401) {
                        this.$router.push({name: 'login'})
                        location.reload();
                        sessionStorage.clear();
                        this.fullscreenLoading = false;
                    }
                })
            },
            getProductosMasVendidosFiltrar() {
                let me = this;
                this.listProductosMasVendidos.all.map(function(x, y){
                    me.listProductosMasVendidos.name.push(x.name);
                    me.listProductosMasVendidos.cantidad.push(x.cantidad);

                    let parametro1 = Math.floor(Math.random() * 256) // 0 - 255
                    let parametro2 = Math.floor(Math.random() * 256) // 0 - 255
                    let parametro3 = Math.floor(Math.random() * 256) // 0 - 255
                    let color = 'rgba('+parametro1+', '+parametro2+', '+parametro3+', 0.2)'
                    me.listProductosMasVendidos.colores.push(color);
                })
                this.getGraficoBar();
            },
            getGraficoBar() {
                let me = this;
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: me.listProductosMasVendidos.name,
                        datasets: [{
                            label: 'Gráfico: Productos mas vendidos',
                            data: me.listProductosMasVendidos.cantidad,
                            backgroundColor: me.listProductosMasVendidos.colores,
                            borderColor: me.listProductosMasVendidos.colores,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            },

            getVentasPorDia(){
                var ruta = '/dashboard/getVentasPorDia'
                axios.get(ruta).then( response => {
                    this.listVentasPorDia.all = response.data;
                    this.getVentasPorDiaFiltrar();
                }).catch(error => {
                    if (error.response.status == 401) {
                        this.$router.push({name: 'login'})
                        location.reload();
                        sessionStorage.clear();
                        this.fullscreenLoading = false;
                    }
                })
            },
            getVentasPorDiaFiltrar() {
                let me = this;
                this.listVentasPorDia.all.map(function(x, y){
                    me.listVentasPorDia.name.push(x.dia);
                    me.listVentasPorDia.cantidad.push(x.total);
                })
                this.getGraficoLine();
            },
            getGraficoLine() {
                let me = this;
                var ctx = document.getElementById('myChart2').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: me.listVentasPorDia.name,
                        datasets: [{
                            label: 'Gráfico: Ventas por día',
                            data: me.listVentasPorDia.cantidad
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }
        },
    }
</script>

<style>

</style>
