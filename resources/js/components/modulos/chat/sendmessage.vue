<template>
    <form action="#" method="post">
        <div class="input-group">
            <input type="text" v-model="message" @keydown.enter.prevent="setRegistrarMensaje" @keydown="escribiendoMensaje" placeholder="Escriba un mensaje..." class="form-control">
            <span class="input-group-append">
                <button type="button" class="btn btn-primary" @click.prevent="setRegistrarMensaje">Enviar</button>
            </span>
        </div>
    </form>
</template>

<script>
    export default {
        props: {
            contacto: {
                type: Object,
                default: null
            },
            usuario: {
                type: Object
            }
        },
        data() {
            return {
                message: ''
            }
        },
        methods: {
            setRegistrarMensaje(){
                if (!this.message) {
                    return
                }
                if (!this.contacto) {
                    return
                }
                var url = '/chat/setRegistrarMensaje';
                axios.post(url, {
                    'nIdContacto'   :   this.contacto.id,
                    'cMensaje'      :   this.message
                }).then(response => {
                    this.$emit('mensaje', response.data[0])
                    this.message = ''
                })
            },
            escribiendoMensaje(){
                Echo.private('escribiendo')
                    .whisper('typing', {
                        usuario :   this.usuario.id,
                        contacto:   this.contacto.id,
                        typing  :   true
                    })
            }
        },
    }
</script>

<style>

</style>
