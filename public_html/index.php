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
    <title>Usterki lokatorskie</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/mybootstrap.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <link href="
    https://cdn.jsdelivr.net/npm/roboto-font@0.1.0/css/fonts.min.css
    " rel="stylesheet">

    <meta name="robots" content="noindex,nofollow" />
<style>
    body{
        font-size:16px;
    }
</style>


</head>

<body >

    <div id="app" class="container" v-cloak>
        <br>
        <div style="display:flex;justify-content:space-between">
            <img src="https://bertrand.pl/wp-content/uploads/2023/11/logo_pl_anniversary.png" alt="" style="max-width:250px">
            
            
            
            <div v-if="user">
                <span v-if="user.group != 'klient'"><a href="/register.php">Rejestracja użytkowników</a> &nbsp; &nbsp; &nbsp;</span>
                <span>Zalogowany: <b>    {{user.login}} </b> <a href="./api/logout.php"> <button>Wyloguj</button></a></span>
            
            </div>
        </div>
        <br><br>
        
       
        <!-- <button @click="deleteproject" v-if="activeproject">Usuń projekt</button> -->

        <div style="display:flex">
            <div>
                <h2>USTERKI LOKATORSKIE - ZESTAWIENIE PROJEKTÓW</h2>
                <div>
                    <table style="font-size:14px">
                        
                        <tr style="font-weight:bold">
                            <!-- <td>id</td> -->
                            <td>Nazwa projektu</td>
                            <td>Adres</td>
                       
                            <td>Inwestor</td>
                            <td>Product manager</td>
                            <td>Handlowiec</td>
                            <td>Przedstawiciel budowy</td>
    
                            <td>Data </td>
                            <td>Nowe usterki:</td>
                        </tr>
                      
                        <tr v-for="elem in projekty" style="cursor:pointer" @click="activeproject = elem.id"
                            :class="{'highlight':elem.id == activeproject}">
                            <!-- <td>#{{elem.id}}</td> -->
                            <td>{{elem.nazwa_projektu}}</td>
                            <td>{{elem.adres}}</td>
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
                            <td><b v-if="elem.ile > 0"> {{elem.ile}} </b><span v-else>{{elem.ile}}</span> </td>
                       
                            <td><a :href="'/project.php?id='+elem.id"> <button class="btn btn-primary" @click.stop="preview(elem.id)"><i class="bi bi-box-arrow-in-left"></i> Wejdź</button></a></td>
                            <td v-if="user.group =='admin'">
                                <button class="btn btn-sm btn-warning" @click="edit(elem)"> <i class="bi bi-pen"></i></button>
                            </td>
                        </tr>
                    </table>

                    <br><br>
                    <div v-if="activeproject">
                        <div>
                            <p>Użytkownicy: <span :class="{'activeright': el.id == activeright.id }" class="highlighthover" @click="activeright = el" style="cursor:pointer" v-for="el in rights.filter((el)=>el.project_id == activeproject)"><b> {{el.login}} </b> &nbsp;</span>   </p>
                            <p v-if="activeright?.id">Usunąć użytkownika {{activeright.login}} z projektu? <button @click="removeright(activeright.id)" class="btn btn-danger"><i class="bi bi-trash"></i> Usuń</button> </p>
                            <p style="display:inline-block">Dodaj użytkownika: &nbsp;</p>
                            <select name="" id="" v-model="useradd" style="display:inline-block">
                                <option value=""></option>
                                <option :value="u.id" v-for="u in users">{{u.login}}</option>
                            </select>
                            &nbsp;
                            <button v-if="useradd" @click="addright" class="btn btn-secondary">Dodaj</button>
                            <br><br>

                        </div>

                        <hr>
                        <p>Nagłówki projektu:</p>
                        <span v-for="head in headers.filter((el)=>el.project_id == activeproject)"><b> {{head.header}} </b> &nbsp;  </span>
                        
                        <br>
                        <span v-if="projekty.find((el)=>el.id == activeproject)?.ilerazem == 0">    
                            <input type="text" v-model="headeradd"> <button @click="addheader">Dodaj nagłówek</button> &nbsp;
                            <button @click="setTemplateHeaders" v-if="headers.filter((el)=>el.project_id == activeproject)?.length == 0">Ustaw nagłówki z szablonu</button>
                        </span>
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

        <button class="btn btn-primary" @click="formbool = !formbool" v-if="user.group =='admin' && crudmode !='edit'">+ dodaj projekt</button>

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