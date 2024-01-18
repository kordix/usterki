<?php
session_start();

if(!isset($_SESSION['zalogowany'])){
  //  header('Location: /usterki/logowanie.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Rejestracja</title>
        <meta name="robots" content="noindex">
</head>
<body style="margin-top:20px">



<div id="app" class="container">
<p><b>Zarejestruj użytkownika</b></p>

<label for="">Login</label>
<input type="text" v-model="login" AUTOCOMPLETE="off">
<label for="">Haslo</label>
<input type="password" v-model="password" AUTOCOMPLETE="off">
<label for="">Powtórz hasło</label>

<input type="password" v-model="password2" AUTOCOMPLETE="off">


<button @click="register">Zarejestruj</button>

<p><b>{{error}}</b></p>

<p v-if="registered">Świetnie - zarejestrowano użytkownika {{loginregistered}} . Możesz się  <a href="/usterki/login.php">zalogować</a></p>


</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

<script>
let app = new Vue({
    el:'#app',
    data:{
        login:'',
        loginregistered:'',
        password:'',
        password2:'',
        error:'',
        registered:false
    },
    methods:{
        register(){
            if (this.password !== this.password2){
                this.error = 'Hasła się nie zgadzają wpisz jeszcze raz';
                return;
            }
            let self = this;
            axios.post('api/register.php',{login:this.login,password:this.password.toLowerCase()}).then((res)=>{
                console.log(res.data)

         

                if(res.data.trim() == 'SUCCESS'){
                    self.registered = true;
                    self.loginregistered = this.login;
                    self.login = '';
                    self.password = '';
                    self.password2 = '';
                    self.error = '';
                }else if(res.data.trim() == 'NOCONNECTION'){
                    self.error = 'BRAK POŁĄCZENIA';
                }else if(res.data.trim() == 'BADLOGIN'){
                    self.error = 'Zły login lub hasło';
                    
                }else{
                    self.error = 'Wystąpił jakiś błąd';
                }
              
            })
        }
    }
})

</script>
</body>
</html>