<?php
session_start();

if(isset($_SESSION['zalogowany'])) {
    header('location:/');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zaloguj</title>
      <meta name="robots" content="noindex,nofollow" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="robots" content="noindex">
</head>
<body style="margin-top:20px">


<div id="app" class="container">

<img src="https://bertrand.pl/wp-content/uploads/2023/11/logo_pl_anniversary.png" alt="" style="max-width:250px">
<br><br>
<p style="font-size:22px"><b>Usterki lokatorskie - panel administracyjny</b></p>


<label for="">Login</label>
<input type="text" v-model="login" id="logininput">
<label for="">Haslo</label>
<input type="password" v-model="password">

<button @click="zaloguj">Zaloguj</button>

<p><b>{{error}}</b></p>



</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

<script>
let app = new Vue({
    el:'#app',
    data:{
        login:'',
        password:'',
        error:''
    },
    methods:{
        zaloguj(){
            let self = this;
            axios.post('/api/zaloguj.php',{login:this.login,password:this.password.toLowerCase()}).then((res)=>{
                self.error = res.data;

              

                if(res.data.length == 12 || res.data.length == 11){
                    location.reload();
                }else{
                    // self.error = 'Zły login lub hasło';
                }
            })
        }
    },
    mounted(){
        document.querySelector('#logininput').focus()
        let self = this;
        document.onkeydown = function (evt) {
                evt = evt || window.event;

                if (evt.key === "Enter") {
                    evt.preventDefault();
                    self.zaloguj(); 
                }
            }

    }
})

</script>
</body>
</html>