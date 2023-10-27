<template>
    <form @submit.prevent="submit">
        <h1 v-if="post">Actualizar Post <strong>{{ post.title }}</strong></h1>
        <h1 v-else>Crear Post</h1>
        <div class="grid grid-cols-2 gap-3">
            <o-field class="col-span-2" label="Titulo" :variant="errors.title?'danger':'primary'" :message="errors.title">
                <o-input v-model="form.title"></o-input>
            </o-field>
            <o-field label="Descripcion" :variant="errors.description?'danger':'primary'" :message="errors.description">
                <o-input v-model="form.description" type="textarea"></o-input>
            </o-field>
            <o-field label="Contenido" :variant="errors.content?'danger':'primary'" :message="errors.content">
                <o-input v-model="form.content" type="textarea"></o-input>
            </o-field>
            <o-field label="Categoria" :variant="errors.category_id?'danger':'primary'" :message="errors.category_id">
                <o-select placeholder="Seleccione una Categoria" v-model="form.category_id">
                    <option
                        v-for="c in categories"
                        v-bind:key="c.id"
                        :value="c.id"
                    >
                        {{ c.title }}
                    </option>
                </o-select>
            </o-field>
            <o-field label="Posted" :variant="errors.posted?'danger':'primary'" :message="errors.posted">
                <o-select placeholder="Seleccione un estado" v-model="form.posted">
                    <option value="yes">Si</option>
                    <option value="not">No</option>
                </o-select>
            </o-field>
        </div>
        <o-field>
            <o-button variant="primary" native-type="submit">Enviar</o-button>
        </o-field>
    </form>
</template>
<script>
export default {
    data(){
        return {
            categories:[],
            form:{
                title: "",
                description: "",
                content: "",
                category_id: "",
                posted: ""
            },
            errors:{
                title: "",
                description: "",
                content: "",
                category_id: "",
                posted: ""
            },
            post:""
        };
    },
    async mounted(){
        if(this.$route.params.slug){
            await this.getPost();

            this.initPost();
        }
        this.getCategory();
    },
    methods: {
        clearErrorsForm(){
            this.errors.title = ""
            this.errors.description = ""
            this.errors.content = ""
            this.errors.posted = ""
            this.errors.category_id = ""
        },
        submit(){

            this.clearErrorsForm();

            if(this.post == ""){
                return this.$axios.post(
                            "/api/post", 
                            this.form
                        ).then(res => {
                            this.$oruga.notification.open({
                                message: 'Registro Creado Correctamente',
                                position: 'top-right',
                                variant: 'success',
                                duration: 4000,
                                closable: true
                            });
                        }).catch(error => {
                            this.errorsForm(error);
                        });
            }
            //actualizar
            this.$axios.patch(
                "/api/post/"+this.post.id, 
                this.form
                ).then(res => {
                    this.$oruga.notification.open({
                        message: 'Registro Actualizado Correctamente',
                        position: 'top-right',
                        variant: 'success',
                        duration: 4000,
                        closable: true
                    });
                }).catch(error => {
                    this.errorsForm(error);
            });
        },
        async getPost(){
            this.post = await this.$axios.get("/api/post/slug/"+this.$route.params.slug);
            this.post = this.post.data
        },
        getCategory(){
            this.$axios.get("/api/category/all").then(response => {
                this.categories = response.data
            })
        },
        initPost(){
            this.form.title = this.post.title
            this.form.description = this.post.description
            this.form.content = this.post.content
            this.form.category_id = this.post.category_id
            this.form.posted = this.post.posted
        },
        errorsForm(error){
            const errorData = error.response.data;
            const fieldToErrorMap = this.errors
            // Iterar sobre el mapeo y establecer los errores correspondientes
            for (const field in fieldToErrorMap) {
                if (errorData[field]) {
                    this.errors[fieldToErrorMap[field]] = errorData[field][0];
                }
            }
        }
    }
}
</script>