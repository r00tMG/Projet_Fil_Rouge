<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import logo from '@/assets/images/logo.png';
import axios from "axios";


export default {
  name: 'Login',
  setup(){
    const email = ref('')
    const password = ref('')
    const router = useRouter()
    const errors = ref({})
    const onLogin = async () => {
      try{
        const response = await axios.post('/Backend/public/api/login', {
            email: email.value,
            password: password.value
        },{
          headers: { 'Content-Type':'application/json' },
        })
        const data = response.data
        console.log(data.user)
       if(data.token && (data.user.role[0].name === "admin") )
        {
          localStorage.setItem('token', data.token)
          //localStorage.setItem('role',JSON.stringify(data.user.role))
          localStorage.setItem('data', JSON.stringify(data))
          await router.push('/users/index')
          //console.log(data.user.role.length)
        }else if(data.token && ((data.user.role[0].name === "Client")||(data.user.role[0].name === "GP"))){
         console.log(data.user.role.length)
          localStorage.setItem('token', data.token)
          //localStorage.setItem('role',JSON.stringify(data.user.role))
          localStorage.setItem('data',JSON.stringify(data))
         //console.log(data.user)
          //await router.push('/home')
         await checkUserProfile(data.token)
        }else {
         Swal.fire({
           title:'failed',
           text:'Authentification échouée',
           icon: 'failed',
           confirmButtonText:'Réessayez'
         })
        }
      } catch (error) {
        if (error.response && error.response.status === 400) {
          errors.value = error.response.data.errors;
          console.error("Validation errors:", errors.value);
        } else {
          console.error("Error: La requête a échoué", error);
        }
      }

    }
    const profile = ref([])
    const checkUserProfile = async (tok) =>{
      const r = await axios.get('/Backend/public/api/profiles',{
        headers:{
          'Accept':'application/json',
          'Authorization':`Bearer ${tok}`
        }
      })
      profile.value = await r.data
      console.log(profile.value.profiles.length)
      if (profile.value.profiles.length > 0)
      {
        await router.push('/profile/index')
      }else{
        await router.push('/create/profile')

      }
    }
    return{
      email,
      password,
      onLogin,
      logo,
      errors
    }
  }
}
</script>

<template>
  <div class="container">
    <div class="row border rounded-1 m-5 shadow border-success">
      <div class="col-md-6  bg-success ">
      <h1 class="text-light mt-5 text-center">Login</h1>
      </div>
      <div class="col-md-6">
        <div class="p-4 w-100">
          <img :src="logo" alt="Logo">
          <form @submit.prevent="onLogin">
            <label>Email</label>
            <input type="text" v-model="email" name="email" class="form_login" placeholder="Email..">
            <p class="text-center" v-if="errors.email">{{errors.email[0]}}</p>
            <label>Password</label>
            <input type="password" v-model="password"	name="password" class="form_login" placeholder="Password ..">

            <input type="submit" class="tombol_login" value="LOGIN">
            <p>Si vous n'êtes pas inscrite veuillez<router-link to="/register"> cliquer ici</router-link></p>
          </form>

        </div>
      </div>
    </div>
  </div>




</template>

<style scoped>
body {
  font-family: sans-serif;
  background: brown;
}

h1 {
  text-align: center;
  font-weight: 300;
}

.tulisan_login {
  text-align: center;

  text-transform: uppercase;
}

.kotak_login {
  background: #f2f2f2;
  /*tengah*/
  margin: 80px auto;
  padding: 30px 20px;
  box-shadow: 0 0 21px;
}

label {
  font-size: 11pt;
}

.form_login {
  box-sizing: border-box;
  width: 100%;
  padding: 10px;
  font-size: 11pt;
  margin-bottom: 20px;
}

.tombol_login {
  background: green;
  color: white;
  font-size: 11pt;
  width: 100%;
  border: none;
  border-radius: 3px;
  padding: 10px 20px;
  cursor:pointer;
}

.link {
  color: #232323;
  text-decoration: none;
  font-size: 10pt;
}

img {

  border-radius: 50%;
  width: 150px;
  margin: 0 auto;
  display: block;
}
input[type="submit"]:hover {
  background-color: #45a049;

}

</style>