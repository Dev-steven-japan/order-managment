<template>
    <div class="direct-chat-contacts">
        <div class="input-group search-contact">
            <input type="search" class="form-control" placeholder="Buscar Contacto" v-model="cBuscarContacto">
        </div>
        <ul class="contacts-list">
            <li v-for="(item, index) in filterContactos" :key="index" @click.prevent="seleccionarContacto(index, item)" :class="(selected == index) ? 'selected' : ''">
                <a href="#">
                    <img v-if="!item.profile_imagen" class="contacts-list-img" src="/img/user1-128x128.jpg">
                    <img v-else class="contacts-list-img" :src="item.profile_imagen">

                    <div class="contacts-list-info">
                        <span class="contacts-list-name">
                            {{ item.fullname }}
                            <small class="contacts-list-date float-right contact-notification">{{item.nNumeroMensajeNoLeidos}}</small>
                        </span>
                        <span v-if="typing.state == true && typing.usuario == item.id" class="contacts-list-msg">Está escribiendo ...</span>
                        <span v-else class="contacts-list-msg">{{(!item.cUltimoMensajeNoLeido) ? '' : item.cUltimoMensajeNoLeido.slice(0, 30)+ '...'}}</span>
                    </div>
                    <!-- /.contacts-list-info -->
                </a>
            </li>
        </ul>
        <!-- /.contacts-list -->
    </div>
</template>

<script>
    export default {
        props: {
            contactos: {
                type: Array,
                default: []
            },
            typing: {
                type: Object
            }
        },
        data() {
            return {
                selected: '',
                cBuscarContacto: ''
            }
        },
        computed: {
            filterContactos(){
                return this.contactos.filter(contacto => {
                    return (contacto.fullname.toLowerCase().indexOf(this.cBuscarContacto.toLowerCase()) != -1);
                })
            }
        },
        methods: {
            seleccionarContacto(index, item) {
                this.selected = index;
                this.$emit('contacto', item);
                this.cBuscarContacto = '';
            }
        },
    }
</script>

<style>
    .selected{
        background: #7FFFD466;
    }
    .contact-notification {
        background: #43828c;
        color: #fff;
        right: 11px;
        top: 20px;
        display: flex;
        font-size: 12px;
        font-weight: 700;
        min-width: 20px;
        justify-content: center;
        align-items: center;
        line-height: 20px;
        padding: 0 4px;
        border-radius: 3px;
        border: 1px solid;
        text-align: center;
        width: 28px;
    }
    .search-contact{
        border: 1px solid #52897b;
        padding:2px;
    }
    .contacts-list {
        height: 276px !important;
        overflow: auto !important;
    }
    .contacts-list-img{
        height: 40px;
    }
</style>
