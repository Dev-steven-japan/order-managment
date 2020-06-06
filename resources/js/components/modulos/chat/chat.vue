<template>
    <div class="row">
        <div class="col-md-6">
            <div class="card direct-chat direct-chat-primary " :class="(openToCloseContact) ? 'direct-chat-contacts-open': ''">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title">Mensajes Instantaneos</h3>

                    <div class="card-tools">
                        <span data-toggle="tooltip" title="3 New Messages" class="badge badge-primary">{{totalMensajesNoLeidos}}</span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Contacts" @click.prevent="abrirContactos">
                            <i class="fas fa-comments"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Conversations are loaded here -->
                    <Conversation :mensajes="listMensajes" :contacto="selectedContact"></Conversation>
                    <!--/.direct-chat-messages-->

                    <!-- Contacts are loaded here -->
                    <ContactList :contactos="listContactos" @contacto="getListarConversaciones" :typing="userTyping"></ContactList>
                    <!-- /.direct-chat-pane -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <SendMessage @mensaje="setNuevoMensaje" :contacto="selectedContact" :usuario="usuario"></SendMessage>
                </div>
                <!-- /.card-footer-->
            </div>
        </div>
    </div>
</template>

<script>
    import Conversation from './conversation'
    import ContactList from './contactlist'
    import SendMessage from './sendmessage'

    export default {
        props: ['usuario'],
        components: {Conversation, ContactList, SendMessage},
        data() {
            return {
                listContactos: [],
                listMensajes: [],
                selectedContact: null,
                openToCloseContact: false,
                userTyping: {
                    usuario: 0,
                    state: false
                }
            }
        },
        computed: {
            totalMensajesNoLeidos(){
                return this.listContactos.reduce(function(valorAnterior, valorActual){
                    return valorAnterior + parseFloat(valorActual.nNumeroMensajeNoLeidos)
                },0)
            }
        },
        mounted() {
            Echo.private(`mensaje.${this.usuario.id}`)
                .listen('NuevoMensaje', (e) => {
                    // console.log(e)
                    this.setMensajeEntrante(e.mensaje);
                });

            Echo.private('escribiendo')
                .listenForWhisper('typing', (e) => {
                    if (this.usuario.id == e.contacto) {
                        this.userTyping.state = true;
                        this.userTyping.usuario = e.usuario

                        setTimeout(() => {
                            this.userTyping.state = false
                        }, 2000);
                    }
                })

            this.getListarContactos();
        },
        methods: {
            abrirContactos() {
                this.openToCloseContact = !this.openToCloseContact;
            },
            getListarContactos(){
                var url = '/chat/getListarContactos'
                axios.get(url).then(response => {
                    this.listContactos = response.data
                })
            },
            getListarConversaciones(contacto){
                this.abrirContactos();

                var url = '/chat/getListarConversaciones'
                axios.get(url, {
                    params: {
                        'nIdContacto'   :   contacto.id
                    }
                }).then(response => {
                    this.listMensajes       =   response.data
                    this.selectedContact    =   contacto
                    this.getListarContactos();
                })
            },
            setNuevoMensaje(rpta){
                this.listMensajes.push(rpta)
            },
            setMensajeEntrante(mensaje){
                if (this.selectedContact && this.selectedContact.id == mensaje.from) {
                    this.setNuevoMensaje(mensaje)
                }

                var sound = new Howl({
                    src: ['/ringtone/notificacion.mp3']
                });

                sound.play();

                //Notificacion
                const noti = this.$vs.notification({
                    square: true,
                    color: 'rgb(59,222,200)',
                    title: mensaje.usu_from_fullname,
                    text: mensaje.text
                })
                this.getListarContactos();
            }
        },
    }
</script>

<style>

</style>
