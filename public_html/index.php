<?php

session_start();

if(!isset($_SESSION['zalogowany'])) {
    header('Location: /logowanie.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="data:;base64,=">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/mybootstrap.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <link href="
    https://cdn.jsdelivr.net/npm/roboto-font@0.1.0/css/fonts.min.css
    " rel="stylesheet">

    <meta name="robots" content="noindex">
<style>
    body{
        font-size:16px;
    }
</style>


</head>

<body>

    <div id="app" class="container">
        <br>
        <img src="https://bertrand.pl/wp-content/uploads/2023/11/logo_pl_anniversary.png" alt="" style="max-width:250px">
        <br><br>
        
        <div v-if="user"><span>Zalogowany: {{user.login}} <a href="./api/logout.php"> <button>Wyloguj</button></a> &nbsp; &nbsp; &nbsp;</span></div>
        <button @click="deleteproject" v-if="activeproject">Usuń projekt</button>

        <div style="display:flex">
            <div>
                <h2>USTERKI LOKATORSKIE - ZESTAWIENIE PROJEKTÓW</h2>
                <div>
                    <table style="font-size:14px">
                        
                            <tr style="font-weight:bold">
                                <td>id</td>
                                <td>Nazwa projektu</td>
                                <td>Adres</td>
                                <td v-if="user.group == 'admin'">Użytkownicy</td>
                                <td>Inwestor</td>
                                <td>Project manager</td>
                                <td>Handlowiec</td>
                                <td>Przedstawiciel budowy</td>
        
                                <td>Data </td>
                            </tr>
                      
                        <tr v-for="elem in projekty" style="cursor:pointer" @click="activeproject = elem.id"
                            :class="{'highlight':elem.id == activeproject}">
                            <td>#{{elem.id}}</td>
                            <td>{{elem.nazwa_projektu}}</td>
                            <td>{{elem.adres}}</td>
                            <td v-if="user.group == 'admin'">
                                <span v-for="el in rights.filter((el)=>el.project_id == elem.id)">{{el.login}} &nbsp;</span>    
                            </td>
                            <td>
                                {{elem.inwestor}}
                            </td>
                            <td>
                                {{elem.project_manager}}
                            </td>
                             <td>
                                {{elem.handlowiec}}
                            </td>
                            <td>
                                {{elem.przedstawiciel}}
                            </td>
                   
                   
                            <td>{{elem.created_at}}</td>
                       
                            <td><a :href="'/project.php?id='+elem.id"> <button class="btn btn-primary" @click="preview(elem.id)"><i class="bi bi-box-arrow-in-left"></i> Wejdź</button></a></td>
                            <td>
                                <button class="btn btn-sm btn-warning" @click="edit(elem)"> <i class="bi bi-pen"></i></button>
                            </td>
                        </tr>
                    </table>

                    <br><br>
                    <div v-if="activeproject">
                        <p>Dodaj użytkownika</p>
                        <select name="" id="" v-model="useradd">
                            <option value=""></option>
                            <option :value="u.id" v-for="u in users">{{u.login}}</option>
                        </select>
                        <button v-if="useradd" @click="addright" class="btn btn-secondary">Dodaj</button>
                    </div>
                </div>
            </div>

            <div>
                <div class="formgroup">
                    <p v-for="elem in messages" style="color:green"><b>{{elem}}</b></p>
                </div>
            </div>

        </div>

        <br><br>

        <button class="btn btn-primary" @click="formbool = !formbool" v-if="user.group =='admin'">+ dodaj projekt</button>

        <div id="addform" v-if="formbool">
            <div class="form-group">
                <label for="">Nazwa projektu</label>
                <input type="text" v-model="form.nazwa_projektu">
            </div>

            <div class="form-group">
                <label for="">Adres</label>
                <input type="text" v-model="form.adres">
            </div>

            <div class="form-group">
                <label for="">Inwestor</label>
                <input type="text" v-model="form.inwestor">
            </div>

             <div class="form-group">
                <label for="">Project manager</label>
                <input type="text" v-model="form.project_manager">
            </div>

            <div class="form-group">
                <label for="">Handlowiec</label>
                <input type="text" v-model="form.handlowiec">
            </div>

            <div class="form-group">
                <label for="">Przedstawiciel budowy</label>
                <input type="text" v-model="form.przedstawiciel">
            </div>


            <button @click="save" v-if="crudmode == 'add'">Zapisz </button>
            <button @click="update" v-if="crudmode == 'edit'">Zapisz zmiany </button>


        </div>  
    </div>



    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.2/axios.min.js"></script>

    <script src="/scriptindex.js">

    </script>
</body>

</html>