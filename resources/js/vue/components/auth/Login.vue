<template>
    <section class="min-h-screen flex flex-col sm:justify-center sm:items-center bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded">
            <h2 class="text-center text-3xl mb-6 mt-3 tracking-tight font-bold text-gray-900">Sign in to your account</h2>
            <form @submit.prevent="submit">
                <o-field label="Usuario" :variant="errors.email ? 'danger' : 'primary'" :message="errors.email">
                    <o-input v-model="form.email"></o-input>
                </o-field>
                <o-field label="Contraseña" :variant="errors.password ? 'danger' : 'primary'" :message="errors.password">
                    <o-input v-model="form.password" type="password"></o-input>
                </o-field>
                <o-field class="w-full flex flex-col justify-center items-end" :variant="errors.login ? 'danger' : 'primary'" :message="errors.login">
                    <o-button :disabled="disabledButton" variant="primary" native-type="submit">Acceder</o-button>
                </o-field>
            </form>
        </div>
    </section>
</template>
<script>
export default {
    data(){
        return{
            form: {
                email: '',
                password: '',
            },
            errors: {
                login: "",
            },
            disabledButton: false,
        }
    },
    created(){
        if(this.$root.isLoggedIn){
            this.$router.push({name : 'list'})
        }
    },
    methods: {
        cleanErrorsForm(){
            this.errors.login = ""
        },
        submit(){
            this.disabledButton = true;
            this.cleanErrorsForm();
            this.$axios.post('/api/user/login', this.form).then((res) => {
                setTimeout(() => {    
                    this.disabledButton = true;
                    window.location.href = "/vue"
                }, 1500);
                this.$root.setCookiesAuth(res.data);
                this.$oruga.notification.open({
                    message: 'Login Exitoso',
                    position: 'top-right',
                    variant: 'success',
                    duration: 1000,
                    closable: true
                });
            }).catch((error) => {           
                this.disabledButton = false;
                if(error.response.data){
                    this.errors.email = error.response.data.errors.email[0]
                    this.errors.password = error.response.data.errors.password[0]
                    this.errors.login = error.response.data.message
                }
            });
        }
    }
}
</script>