<?php
session_start();

if(!isset($_SESSION['zalogowany'])){
  header('Location: /logowanie.php');
}

if($_SESSION['group'] != 'admin') {
  header('Location: /logowanie.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Rejestracja</title>
           <meta name="robots" content="noindex,nofollow" />
</head>
<body style="margin-top:20px">



<div id="app" class="container">
<p><a href="/"><--- powrót do strony głównej</a></p>
<p><b>Zarejestruj użytkownika</b></p>

<label for="">Login</label>
<input type="text" v-model="login" AUTOCOMPLETE="off">
<!-- <label for="">Haslo</label>
<input type="password" v-model="password" AUTOCOMPLETE="off">
<label for="">Powtórz hasło</label> -->
<!-- <input type="password" v-model="password2" AUTOCOMPLETE="off"> -->

<label for="">Rola:</label>

<select name="" id="" v-model="group">
    <option value="admin">Administrator</option>
    <option value="serwis">Serwisant</option>
    <option value="klient">Klient</option>
</select>

<p style="color:red" v-if="login && !group"><b>WYBIERZ GRUPE</b></p>


<button @click="register">Zarejestruj</button>

<p><b>{{error}}</b></p>

<p><b>{{response}}</b></p>

<!-- <p v-if="registered">Świetnie - zarejestrowano użytkownika {{loginregistered}} . Możesz się  <a href="/login.php">zalogować</a></p> -->


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
        registered:false,
        group:'',
        response:''

    },
    methods:{
        register(){
            if(!this.group){
                return;
            }

            if (this.password !== this.password2){
                this.error = 'Hasła się nie zgadzają wpisz jeszcze raz';
                return;
            }
            let self = this;
            axios.post('api/register.php',{login:this.login,group:this.group}).then((res)=>{
                self.response = res.data
              
            })
        }
    }
})

</script>
</body>
</html>