<template>
    <div>
        <h1>Listado post</h1>
        <o-field grouped group-multiline>
            <o-switch v-model="loading">Loading state</o-switch>
        </o-field>
        <o-table :data="posts.length == 0 ? []: posts" :loading = "loading">
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
        </o-table>
    </div>
</template>
<script>
export default{
    data(){
        return {
            posts : [],
            loading : true
        }
    },
    async mounted(){
        this.$axios.get('http://laraprimerospasoscrudpost.test/api/post/all').then((res) =>{
            this.posts = res.data;
            console.log(this.posts);
            // this.loading = false;
        })
    }
}
</script>