<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Dashboard</h1>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="content container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="container-fluid">
                        <Graphic></Graphic>
                    </div>
                </div>

                <div class="card-body">
                    <div class="container-fluid">
                        <Chat :usuario="authUser"></Chat>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Graphic from './graphic'
    import Chat from '../chat/chat'

    export default {
        components: {Graphic, Chat},
        data() {
            return {
                authUser: JSON.parse(sessionStorage.getItem('authUser'))
            }
        },

    }
</script>

<style>

</style>
