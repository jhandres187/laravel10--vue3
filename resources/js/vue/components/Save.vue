<template>
    <form @submit.prevent="submit">
        <h1 v-if="post">Actualizar Post <strong>{{ post.title }}</strong></h1>
        <h1 v-else>Crear Post</h1>
        <div class="grid grid-cols-2 gap-3 my-4">
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
            <o-field class="file w-full" :message="fileErrors">
                <figure v-if="post" class="mr-2">
                    <img :src="'/image/otro/'+this.imagePosted" :alt="post.title" class="w-20">
                </figure>
                <div class="w-full flex gap-2" v-if="post">
                    <o-upload v-model="file">
                        <o-button tag="a" variant="primary">
                            <o-icon icon="upload" />
                            <span>Click para cargar</span>
                        </o-button>
                    </o-upload>
                    <span v-if="file" class="file-name">
                        {{ file.name }}
                    </span>
                    <o-button iconLeft="upload" @click="upload()">Subir</o-button>
                </div>
            </o-field>
            <div>
                <o-field :message="fileErrors">
                    <o-upload v-model="dropFiles" multiple drag-drop>
                        <section class="ex-center">
                            <p>
                                <o-icon icon="upload" size="is-large" />
                            </p>
                            <p>Drop your files here or click to upload</p>
                        </section>
                    </o-upload>
                </o-field>

                <div class="tags">
                    <span v-for="(file, index) in dropFiles" :key="index">
                        {{ file.name }}
                        <o-button
                            icon-left="close"
                            size="small"
                            native-type="button"
                            @click="deleteDropFile(index)">
                        </o-button>
                    </span>
                </div>
            </div>
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
            post:"",
            file: null,
            fileErrors: "",
            dropFiles: null,
            imagePosted: null,
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
        upload(){
            //return console.log(this.file)
            this.fileErrors = ""
            const formData = new FormData()
            formData.append("image", this.file)
            this.$axios.post("/api/post/upload/"+this.post.id,formData,{
                headers:{
                    "Content-Type": "multipart/form-data"
                }
            }).then(res => {
                this.imagePosted = res.data.image;
                this.$oruga.notification.open({
                    message: 'Imagen Actualizada Correctamente',
                    position: 'top-right',
                    variant: 'success',
                    duration: 4000,
                    closable: true
                });
                this.file = null
                console.log(res);
            }).catch(error => {
                this.fileErrors = error.response.data.message;
            });
        },
        deleteDropFile(i){
            return this.dropFiles.splice(i,1)
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
            this.imagePosted = this.post.image ?? null 
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
    },
    watch:{
        dropFiles:{
            handler(neww,old){
                if (neww !== null) {
                    //return console.log(neww[neww.length - 1])
                    this.fileErrors = ""
                    const formData = new FormData()
                    formData.append("image", neww[neww.length - 1])
                    this.$axios.post("/api/post/upload/"+this.post.id,formData,{
                        headers:{
                            "Content-Type": "multipart/form-data"
                        }
                    }).then(res => {
                        this.imagePosted = res.data.image;
                        this.$oruga.notification.open({
                            message: 'Imagen Actualizada Correctamente',
                            position: 'top-right',
                            variant: 'success',
                            duration: 4000,
                            closable: true
                        });
                        this.dropFiles = null;
                    }).catch(error => {
                        this.fileErrors = error.response.data.message;
                    });
                }
            },
            deep: true
        }
    }
}
</script>