<template>
    <form action="#" method="post">
        <div class="input-group">
            <button type="button" @click="toogleDialogEmoji" :readonly="(!contacto)?true:false">😃</button>
            <input type="text" v-model="message" @keydown.enter.prevent="setRegistrarMensaje" @keydown="escribiendoMensaje" placeholder="Escriba un mensaje..." class="form-control" :readonly="(!contacto)?true:false">
            <span class="input-group-append">
                <button type="button" class="btn btn-primary" @click.prevent="setRegistrarMensaje" :readonly="(!contacto)?true:false">Enviar</button>
            </span>
        </div>
        <VEmojiPicker
            v-show="showDialog"
            labelSearch="Search"
            lang="pt-BR"
            @select="onSelectEmoji"
        />
    </form>
</template>

<script>
    import VEmojiPicker, { emojisDefault, categoriesDefault } from "v-emoji-picker";
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
        components: {
            VEmojiPicker
        },
        data() {
            return {
                message: '',
                showDialog: false
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
                if (!this.message) {
                    return
                }
                if (!this.contacto) {
                    return
                }
                Echo.private('escribiendo')
                    .whisper('typing', {
                        usuario :   this.usuario.id,
                        contacto:   this.contacto.id,
                        typing  :   true
                    })
            },
            onSelectEmoji(emoji) {
                console.log(emoji)
                this.message += emoji.data
            },
            toogleDialogEmoji() {
                if (!this.contacto) {
                    return
                }
                this.showDialog = !this.showDialog;
            },
        },
    }
</script>

<style>
    #EmojiPicker{
        position: absolute;
        margin-top: -29.5rem;
    }
</style>
