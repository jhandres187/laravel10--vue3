<template>
    <div class="container mx-auto">
        <div class="w-full mt-6 mb-2 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-md">
            <o-modal v-model:active="confirmDeleteActive">
                <div class="w-full py-6 px-12 flex flex-col items-start justify-center">
                    <h1 class="my-4">Esta accion no se puede deshacer!</h1>
                    <hr class="my-3 w-full">
                    <p class="text-xl">¿Seguro que quieres Eliminar este Post?</p>
                    <div class="w-full flex justify-end items-center gap-4 my-8">
                        <o-button variant="danger" @click="confirmDeleteActive=false">Cancelar</o-button>
                        <o-button @click="deletePost()">Eliminar</o-button>
                    </div>
                </div>
            </o-modal>
            <h1>Listado post</h1>
            <o-button iconLeft="plus" @click="$router.push({name:'save'})">Crear</o-button>
            <div class="mb-5"></div>
            <o-table :loading="loading" :data="posts.current_page && posts.length == 0 ? []: posts.data">
                <o-table-column field="id" label="ID" numeric v-slot="p">
                    {{ p.row.id }}
                </o-table-column>
                <o-table-column field="title" label="Titulo" v-slot="p">
                    {{ p.row.title }}
                </o-table-column>
                <o-table-column field="posted" label="Posteado" v-slot="p">
                    {{ p.row.created_at }}
                </o-table-column>
                <o-table-column field="category" label="categoria" v-slot="p">
                    {{ p.row.category.title }}
                </o-table-column>
                <o-table-column field="slug" label="Acciones" v-slot="p">
                    <router-link class="mr-3 underline" :to="{name:'save', params:{'slug': p.row.slug}}">Editar</router-link>
                    <o-button 
                        iconLeft="delete" 
                        rounded 
                        size="small" 
                        variant="danger" 
                        @click="deletePostRow=p; confirmDeleteActive=true"
                    >
                        Eliminar
                    </o-button>
                </o-table-column>
            </o-table>
            <br>
            <o-pagination
                v-if="posts.current_page && posts.data.length"
                @change="updatePage()"
                v-model:current="currentPage"
                :total="posts.total"
                :range-before="1"
                :range-after="1"
                order="centered"
                size="small"
                :simple="false"
                :rounded="true"
                :per-page="posts.per_page"
            >
            </o-pagination>
        </div>
    </div>
</template>
<script>
export default{
    data(){
        return {
            posts : [],
            loading : true,
            currentPage: 1,
            confirmDeleteActive: false,
            deletePostRow:""
        }
    },
    mounted(){
        this.listPage()
    },
    methods: {
        updatePage(){
            setTimeout(this.listPage, 100);
        },
        listPage(){
            const config = {
                headers: {
                    Authorization: `Bearer ${this.$root.token}`
                }
            }
            this.loading = true;
            const postsResponse = this.$axios.get('/api/post?page='+this.currentPage, config).then((res) => {
                //Almacenar los posts en una variable
                this.posts = res.data
                this.loading = false
            });
        },
        deletePost(){
            this.posts.data.splice(this.deletePostRow.index,1);
            const config = {
                headers: {
                    Authorization: `Bearer ${this.$root.token}`
                }
            }
            this.$axios.delete("/api/post/"+this.deletePostRow.row.id, config);
            this.confirmDeleteActive = false;
            this.$oruga.notification.open({
                message: 'Registro Eliminado',
                position: 'top-right',
                variant: 'success',
                duration: 4000,
                closable: true
            });
        }
    }
}
</script>