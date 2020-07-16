<template>
    <div class="direct-chat-messages" ref="bajar">
        <template v-if="contacto">
            <!-- Message. Default to the left -->
            <div class="direct-chat-msg " v-for="(item, index) in mensajes" :key="index" :class="(item.to == contacto.id) ? '' : 'right'">
                <div class="direct-chat-infos clearfix">
                    <span class="direct-chat-name " :class="(item.to == contacto.id) ? 'float-left' : 'float-right'" v-text="item.usu_from_fullname"></span>
                    <span class="direct-chat-timestamp " :class="(item.to == contacto.id) ? 'float-right' : 'float-left'">
                        {{ moment(item.created_at).format('MMMM Do YYYY, h:mm:ss a') }}
                    </span>
                </div>
                <!-- /.direct-chat-infos -->
                <img v-if="!item.usu_from_profile_image" class="direct-chat-img" src="/img/user1-128x128.jpg" alt="message user image">
                <img v-else class="direct-chat-img" :src="item.usu_from_profile_image" alt="message user image">
                <!-- /.direct-chat-img -->
                <div class="direct-chat-text">
                    {{ item.text }}
                </div>
                <!-- /.direct-chat-text -->
            </div>
            <!-- /.direct-chat-msg -->
        </template>
        <template v-else>
            <div class="direct-chat-msg">
                <vs-alert shadow >
                    <template #title>
                        Mensaje
                    </template>
                    Debe seleccionar un contacto para iniciar una conversación
                </vs-alert>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            mensajes: {
                type: Array,
                default: []
            },
            contacto: {
                type: Object,
                default: null
            }
        },
        watch: {
            //Ante un cambio en e prop Mensajes que ejecute el metodo scrollToBotom
            mensajes(){
                this.scrollToBotom();
            },
            //Ante un cambio en e prop Contacto que ejecute el metodo scrollToBotom
            contacto(){
                this.scrollToBotom();
            }
        },
        data() {
            return {
                moment: moment
            }
        },
        methods: {
            scrollToBotom(){
                setTimeout(() => {
                    this.$refs.bajar.scrollTop  =   this.$refs.bajar.scrollHeight - this.$refs.bajar.clientHeight
                }, 100);
            }
        },
    }
</script>

<style>

</style>
