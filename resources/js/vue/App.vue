<template>
    <div>
        <nav class="bg-white border-b border-gray-100">
            <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="w-full flex">
                    <div class="flex items-center">
                        Logo
                    </div>
                    <div class="flex w-full py-4 px-4 sm:px-6 items-end justify-between">
                        <div class="flex">
                            <router-link class="inline-flex py-1 uppercase border-b-2 text-sm leading-5 px-4 text-gray-600 text-center font-bold hover:text-gray-900 hover:border-gray-700 duration-150 transition-all hover:translate-y-1 mx-3" v-if="$root.isLoggedIn" :to="{name: 'list'}">Post</router-link>
                            <router-link class="inline-flex py-1 uppercase border-b-2 text-sm leading-5 px-4 text-gray-600 text-center font-bold hover:text-gray-900 hover:border-gray-700 duration-150 transition-all hover:translate-y-1 mx-3" v-if="!$root.isLoggedIn" :to="{name: 'login'}">Login</router-link>
                            <o-button v-if="$root.isLoggedIn" variant="danger" @click="logout()">Logout</o-button>
                        </div>
                        <div class="flex flex-col items-center mx-3" v-if="$root.isLoggedIn">
                            <div class="rounded-full w-9 h-9 bg-blue-300 text-center p-1 font-bold">
                                {{ $root.user.name.substr(0,2).toUpperCase() }}
                            </div>
                            <p>
                                {{ $root.user.name }}
                            </p>
                        </div>
                    </div>
                </div>
            </header>
        </nav>
        <router-view></router-view>
    </div>
</template>
<script>
export default {
    data(){
        return{
            isLoggedIn: false,
            user: "",
            token: ""
        }
    },
    created(){
        if(window.Laravel.isLoggedIn){
            this.isLoggedIn = true
            this.user = window.Laravel.user
            this.token = window.Laravel.token
        }else{
            const auth = this.$cookies.get('auth')
            if(auth){
                this.isLoggedIn = true
                this.user = auth.user
                this.token = auth.token
                this.$axios.post('/api/user/token-check',{
                    'token': auth.token
                }).then(()=>{
                }).catch((error)=>{
                    this.setCookiesAuth('');
                    window.location.href = '/vue/login';
                });
            }
        }
    },
    methods: {
        logout(){
            this.$axios.post('/api/user/logout',{
                token: this.token,
            }).then((res) => {
                this.setCookiesAuth('');
                window.location.href = '/vue/login';
            });
        },
        setCookiesAuth(data){
            this.$cookies.set('auth', data);
        }
    }
}
</script>