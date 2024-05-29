<?php

session_start();

if(!isset($_SESSION['zalogowany'])) {
    header('Location: /logowanie.php');

}



if(!isset($_SESSION['zalogowany'])) {
    echo 'NIEZALOGOWANY';
    return;
}

require($_SERVER['DOCUMENT_ROOT'] . '/db.php');

if($_SESSION['group'] == 'klient') {
    $dbh = new PDO("mysql:host=$hostname;dbname=$dbname;charset=UTF8", $user, $pass);

    $id = $_SESSION['id'];
    $projectid = $_GET['id'];

    $query = "select distinct p.* from projects p
    join rights r on p.id = r.project_id
    where r.user_id = $id and r.project_id = ?
    ";

    $sth = $dbh->prepare($query);
    $sth->execute([$projectid]);

    $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

    if(count($rows)) {

    } else {
        echo 'NIE MASZ UPRAWNIENIA DO TEGO PROJEKTU';
        return;
    }

}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="data:;base64,=">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usterki</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/mybootstrap.css">

    <meta name="robots" content="noindex">

    <!-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" /> -->

       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">


</head>

<body>



    <p id="projectid" style="display:none"><?php echo $_GET['id'] ?></p>

    <input type="hidden" value="<?php echo $_SESSION['id']; ?>" id="userid">

    <div id="navbar">
        <a href="/"><i><-</i></a>
        <span>{{project.nazwa_projektu}} {{project.adres}} </span>
        <span>Zalogowany: {{user.login}} <a href="./api/logout.php"> <button>Wyloguj</button></a> &nbsp; &nbsp; &nbsp;</span>
    </div>

    <!-- <div id="navbar2">

        <div :class="{'active':activesection == 'usterki'}" @click="activesection = 'usterki'">USTERKI</div>
        <div :class="{'active':activesection == 'plany'}" @click="setPlany" >PLANY</div>
        <div></div>
        <div></div>
        <div></div>
        <div :class="{'active':activesection == 'raporty'}" @click="activesection = 'raporty'"> RAPORTY</div>
        <div :class="{'active':activesection == 'ustawienia'}" @click="activesection = 'ustawienia'">Ustawienia</div>

    </div> -->

    <div v-show="activesection == 'usterki'">
        <div>
            <br>
            <table id="usterkitable">
                <!-- NAGŁÓWEK -->
                <tr>
                    <th @click="sortuj('id')" style="cursor:pointer">id</td>
                    <th style="width:140px">
                        Data zgłoszenia
                    </th>
                  
                    <th style="width:20px;cursor:pointer" @click="sortuj('lokal')">
                        Nr budowlany
                    </th>
                    <th>
                        Adres administracyjny
                    </th>
                    <th>Nr admin.</th>
                    <th>Kontakt do klienta</th>
                    <th>Data zgłoszenia przez klienta</th>
                    <th>
                        <span>Typ usterki</span>
                    </th>
                    <th>
                        <b>Usterka</b>
                    </th>
                    <th >
                        <b>Uwagi inwestora</b>
                    </th>
                    <th class="clientside">
                        Link
                    </th>
                    <th>
                        <b>Status</b>
                    </th>
                    <th style="width:300px">
                        <b>Komentarz serwisu</b>
                    </th>

                    <th>
                        Nr oferty
                    </th>
                   
                    <th>
                        SPW
                    </th>
                   
                    <!-- <th style="width:20px">
                        <span style="opacity:0.8">x</span>
                    </th>
                    <th style="width:20px">
                        <span style="opacity:0.8">y</span>
                    </th> -->
                    <th>
                        <span style="opacity:0.8">Klasyfikacja</span>
                    </th>
                    <th>
                        ostatnia akcja
                    </th>


                    <th>
                        akcje
                    </th>

                </tr>
                <!-- FILTRY -->
                <tr class="filtry" v-if="usterki.length > 0">
                    <th>
                        <input type="text" v-model="filtry.id" style="width:20px">
                    </th>
                    <th></th>
                    <th>
                        <input type="text" v-model="filtry.lokal" style="width:90%">
                    </th>
                    <th>
                        <input type="text" v-model="filtry.adres_admin" style="width:90%">
                    </th>
                    <th>
                        <input type="text" v-model="filtry.nr_admin" style="width:90%">
                    </th>
                    <th>
                        <input type="text" v-model="filtry.kontakt_klient" style="width:90%">
                    </th>
                    <th>
                        <!-- <input type="date" v-model="filtry.data_klient" style="width:90%"> -->
                    </th>
                    <th>
                        <select name="" id="" v-model="filtry.typ_niezgodnosci" class="typusterkiselect">
                            <option value="">-</option>
                            <option value="Wada szyby">Wada szyby</option>
                            <option value="Uszkodzone / Wada powierzchni">Uszkodzone</option>
                            <option value="Niezgodność asortymentowa">Niezgodność Asortymentowa</option>
                            <option value="Brak / niekompletność">Brak / niekompletność</option>
                            <option value="Wada funkcjonowania">Wada funkcjonowania</option>
                            <option value="Wada wymiarowa">Wada wymiarowa</option>
                        </select>
                    </th>
                    <th>
                        <input type="text" v-model="filtry.opis_niezgodnosci" style="width:95%">
                    </th>
                    <th >
                        <input type="text" v-model="filtry.uwagi_inwestora" style="width:95%">
                    </th>
                    <th class="clientside">

                    </th>
                    <th>
                        <select name="" id="" v-model="filtry.status" >
                            <option value="">-</option>
                            <option value="Zgłoszona">Zgłoszona</option>
                            <option value="Wykonana">Wykonana</option>
                            <option value="Częściowo wykonana">Częściowo wykonana</option>
                            <option value="Zatwierdzona">Zatwierdzona</option>
                            <option value="Niezatwierdzona">Niezatwierdzona</option>
                            <option value="Rezygnacja">Rezygnacja</option>
                            <option value="Niezasadna">Niezasadna</option>
                        </select>
                    </th>
                   
                    <th>
                        <input type="text" v-model="filtry.komentarz_serwisu" style="width:95%">
                    </th>
                      <th>
                        <input type="text" v-model="filtry.nr_oferty">
                    </th>
                
                    <th>
                        <input type="text" v-model="filtry.SPW">
                    </th>

                    <!-- <th></th>
                    <th></th> -->
                    <th>
                        <select name="" id="" v-model="filtry.klasyfikacja" style="width:95%">
                            <option value="">-</option>
                            <option value="Gwarancyjna">Gwarancyjna</option>
                            <option value="W normie">W normie</option>
                            <option value="Odpłatna">Odpłatna</option>
                            <option value="Błąd">Błędne zgłoszenie</option>
                        </select>
                    </th>
                  
                </tr>


                <!-- DANE -->
                <template v-for="(elem,index) in filtered" >      
                    <tr :class="{'wykonana':elem.status == 'wykonana' , 'separator': shouldAddSeparator(index) , 'hiddenrow':elem.hidden }">
                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="copyvalues(elem)" style="cursor:pointer">
                        <span v-if="elem.hidden" style="font-weight:bold">*</span>
                        <span v-else>#</span>
                        {{elem.usterka_numer}}
                        </td>
                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length"> <span style="width:100px;display:block">  {{elem.created_at}}</span></td>
                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'lokal')">
                            <span v-if="!elem.editable"> {{elem.lokal}}</span>
                            <input @keyup.enter="elem.editable = false" :id="elem.id+'lokal'" v-else type="text" v-model="elem.lokal" @change="updateAuto(elem,'lokal')" style="width:90%" @blur.stop="elem.editable = false">
                        </td>

                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'adres_admin')">
                            <span v-if="!elem.editable"> {{elem.adres_admin}}</span>
                            <input @keyup.enter="elem.editable = false" :id="elem.id+'adres_admin'"  v-else type="text" v-model="elem.adres_admin" @change="updateAuto(elem,'adres_admin')" @blur.stop="elem.editable = false">
                        </td>

                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'nr_admin')" >
                            <span v-if="!elem.editable"> {{elem.nr_admin}}</span>
                            <input :id="elem.id+'nr_admin'"  v-else type="text" v-model="elem.nr_admin" @change="updateAuto(elem,'nr_admin')" @blur.stop="elem.editable = false">
                        </td>

                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'kontakt_klient')" >
                            <span v-if="!elem.editable"> {{elem.kontakt_klient}}</span>
                            <input :id="elem.id+'kontakt_klient'"  v-else type="text" v-model="elem.kontakt_klient" @change="updateAuto(elem,'kontakt_klient')" @blur.stop="elem.editable = false">
                        </td>

                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'data_klient')">
                            <span v-if="!elem.editable"> {{elem.data_klient}}</span>
                            <input v-else :id="elem.id+'data_klient'" type="date" v-model="elem.data_klient" @blur.stop="updateAuto(elem,'data_klient')">
                        </td>
                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'typ_niezgodnosci')">
                            <span v-if="!elem.editable">{{elem.typ_niezgodnosci}}</span>
                            <select name="" :id="elem.id+'typ_niezgodnosci'" v-model="elem.typ_niezgodnosci" @change="updateAuto(elem,'typ_niezgodnosci')" style="width:95%" v-if="elem.editable" @blur.stop="elem.editable = false">
                            <option value="">-</option>
                                <option value="Wada szyby">Wada szyby</option>
                                <option value="Uszkodzone / Wada powierzchni">Uszkodzone / Wada powierzchni</option>
                                <option value="Niezgodność asortymentowa">Niezgodność Asortymentowa</option>
                                <option value="Brak / niekompletność">Brak / niekompletność</option>
                                <option value="Wada funkcjonowania">Wada funkcjonowania</option>
                                <option value="Wada wymiarowa">Wada wymiarowa</option>
                            </select>

                        </td>
                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'opis_niezgodnosci')">
                            <span v-if="!elem.editable"> {{elem.opis_niezgodnosci}}</span>
                            <textarea :id="elem.id+'opis_niezgodnosci'" v-else v-model="elem.opis_niezgodnosci" @change="updateAuto(elem,'opis_niezgodnosci')" @blur.stop="elem.editable = false" style="width:95%"></textarea>
                        </td>
                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'uwagi_inwestora')" >
                            <span v-if="!elem.editable"> {{elem.uwagi_inwestora}}</span>
                            <input :id="elem.id+'uwagi_inwestora'" v-else  v-model="elem.uwagi_inwestora" @change="updateAuto(elem,'uwagi_inwestora')" @blur.stop="elem.editable = false">
                        </td>

                        <td class="clientside">
                            <a v-if="elem.link":href="elem.link" target="_blank">link</a>
                            <input :id="elem.id+'link'" v-if="!elem.link" type="text" v-model="elem.link" @change="updateAuto(elem,'link')" style="width:100px" @blur.stop="elem.editable = false">
                        </td>

                        <!-- SERWIS -->
                        <td  @click="handleChange(elem,'status')" :class="{disabledcursor:user.group == 'klient'}">
                            <span v-if="!elem.editable">{{elem.status}}</span>
                            <select name="" :id="elem.id+'status'"  v-model="elem.status" :disabled="user.group == 'klient'"
                                @change="updateAuto(elem,'status')" style="width:95%" v-if="elem.editable">
                                <option value="Zgłoszona">Zgłoszona</option>
                                <option value="Wykonana">Wykonana</option>
                                <option value="Częściowo wykonana">Częściowo wykonana</option>
                                <option value="Zatwierdzona">Zatwierdzona</option>
                                <option value="Niezatwierdzona">Niezatwierdzona</option>
                                <option value="Rezygnacja">Rezygnacja</option>
                                <option value="Niezasadna">Niezasadna</option>
                        
                            </select>

                        </td>

                        <td @click="handleChange(elem,'komentarz_serwisu')" :class="{disabledcursor:user.group == 'klient'}" style="position:relative">
                            <span v-if="!elem.editable"> {{elem.komentarz_serwisu}}</span>
                            <textarea :id="elem.id+'komentarz_serwisu'" v-else  v-model="elem.komentarz_serwisu" :disabled="user.group == 'klient'" @change="updateAuto(elem,'komentarz_serwisu')" style="width:95%" @blur="elem.editable = false"></textarea>
                        </td>
                        <td @click="handleChange(elem,'nr_oferty')" :class="{disabledcursor:user.group == 'klient'}">
                            <span v-if="!elem.editable"> {{elem.nr_oferty}}</span>
                            <input :id="elem.id+'nr_oferty'" v-else  v-model="elem.nr_oferty" @change="updateAuto(elem,'nr_oferty')" @blur="elem.editable = false">
                        </td>
                        <td @click="handleChange(elem,'SPW')" :class="{disabledcursor:user.group == 'klient'}">
                            <span v-if="!elem.editable"> {{elem.SPW}}</span>
                            <input :id="elem.id+'SPW'" v-else  v-model="elem.SPW" @change="updateAuto(elem,'SPW')" @blur="elem.editable = false">
                        </td>
                
                        <!-- <td @click="elem.editable = true">
                            <span v-if="!elem.editable" >{{elem.x}}</span>
                            <input v-else type="text" v-model="elem.x" @change="updateAuto(elem,'x')" style="width:20px" placeholder="y">
                        </td>
                        <td @click="elem.editable = true">
                            <span v-if="!elem.editable" >{{elem.y}}</span>
                            <input v-else type="text" v-model="elem.y" @change="updateAuto(elem,'y')" style="width:20px" placeholder="y">
                        </td> -->
                        <td @click="elem.editable = true" :class="{disabledcursor:user.group == 'klient'}">
                            <span v-if="!elem.editable">{{elem.klasyfikacja}}</span>
                            <select name="" id="" v-model="elem.klasyfikacja"
                                @change="updateAuto(elem,'klasyfikacja')" style="width:95%" v-if="elem.editable" :disabled="user.group == 'klient'">
                                <option value="Gwarancyjna">Gwarancyjna</option>
                                <option value="W normie">W normie</option>
                                <option value="Odpłatna">Odpłatna</option>
                                <option value="Błędne zgłoszenie">Błędne zgłoszenie</option>
                            </select>

                        </td>
                        

                        <td>
                            {{elem.akcja}}
                        </td>
                        <td style="width:150px">
                            <div style="display:flex;flex-wrap:no-wrap">
                                <button class="btn-sm btn-danger" @click="usun(elem.id)" style="display:inline-block;margin-right:5px"><i class="bi bi-trash"></i></button>
                                <button @click="addExtra(elem.id)" style="display:inline-block;margin-right:5px" :disabled="user.group == 'klient'">+</button>
                                <!-- <button class="btn-sm btn-warning" @click="hide(elem.id)" style="display:inline-block;margin-right:5px" v-if="user.group != 'klient' && !elem.hidden"><i class="bi bi-eye-slash"></i></button> -->
                                <button class="btn-sm btn-warning" @click="hide(elem.id,true)" style="display:inline-block;margin-right:5px" v-if="user.group != 'klient' && elem.hidden"><i class="bi bi-eye"></i></button>
                                

                                
                            </div>
                        </td>


                    </tr>

                    <tr v-for="ext in extras.filter((el)=>el.usterka_id==elem.id)">
                        <!-- <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td> -->
                        <td class="clientside">
                            <a v-if="ext.link":href="ext.link" target="_blank">link</a>
                            <input :id="ext.id+'link'" v-if="!ext.link" type="text" v-model="ext.link" @change="updateAuto(elem,'link','extra')" style="width:100px" @blur.stop="ext.editable = false">

                        </td>
                        <td @click="handleChange(elem,'status')" :class="{disabledcursor:user.group == 'klient'}"> 
                            <span v-if="!elem.editable">{{ext.status}}</span>
                            <select name="" :id="ext.id+'status'"  v-model="ext.status" :disabled="user.group == 'klient'"
                                @change="updateAuto(ext,'status', 'extra')" style="width:95%" v-if="elem.editable">
                                <option value="Zgłoszona">Zgłoszona</option>
                                <option value="Wykonana">Wykonana</option>
                                <option value="Częściowo wykonana">Częściowo wykonana</option>
                                <option value="Zatwierdzona">Zatwierdzona</option>
                                <option value="Niezatwierdzona">Niezatwierdzona</option>
                                <option value="Rezygnacja">Rezygnacja</option>
                                <option value="Niezasadna">Niezasadna</option>
                        
                            </select>
                        </td>


                        <td @click="handleChange(elem,'komentarz_serwisu')" :class="{disabledcursor:user.group == 'klient'}" style="position:relative">
                            <span v-if="!elem.editable"> {{ext.komentarz_serwisu}}</span>
                            <textarea :id="ext.id+'komentarz_serwisu'" v-else  v-model="ext.komentarz_serwisu" :disabled="user.group == 'klient'" @change="updateAuto(ext,'komentarz_serwisu','extra')" style="width:95%" @blur.stop="elem.editable = false"></textarea>
                        </td>
                        <td>
                            {{ext.spw}}
                        </td>
                        <td>
                            {{ext.klasyfikacja}}
                        </td>
                        <td>
                            {{ext.akcja}}
                        </td>
                        <td>
                            
                                <button class="btn-sm btn-danger" @click="usunExtra(ext.id)" style="display:inline-block;margin-right:5px">Usuń</button>
                            

                        </td>
                    </tr>
                </template>
                <!-- DODAWANIE -->
                <tr id="addformtable" class="addrow">
                    <td><template v-if="crudmode == 'add' && form.lokal" > 
                            <button @click="save(false)" class="btn btn-primary" style="padding:2px" ><i class="bi bi-floppy"></i></button>
                            <button @click="save(true)" class="btn btn-primary" style="padding:2px;margin-top:2px" v-if="user.group == 'admin'"><i class="bi bi-floppy"></i><i class="bi bi-eye-slash"></i></button>

                        </template>
                            <span v-else>+</span>
                       
                    </td>
                    <td></td>
                    <td>
                        <input  type="text" v-model="form.lokal" style="width:90%">
                    </td>
                    <td>
                        <input  type="text" v-model="form.adres_admin">
                    </td>
                    <td>
                        <input  type="text" v-model="form.nr_admin" style="width:60px">
                    </td>
                    <td>
                        <textarea  type="text" v-model="form.kontakt_klient" style="width:200px"></textarea>
                    </td>
                    <td>
                        <input  type="date" v-model="form.data_klient">
                    </td>
                    <td>
                        <select name="" id="" v-model="form.typ_niezgodnosci" class="typusterkiselect">
                           <option value="">-</option>
                            <option value="Wada szyby">Wada szyby</option>
                            <option value="Uszkodzone / Wada powierzchni">Uszkodzone / Wada powierzchni</option>
                            <option value="Niezgodność asortymentowa">Niezgodność Asortymentowa</option>
                            <option value="Brak / niekompletność">Brak / niekompletność</option>
                            <option value="Wada funkcjonowania">Wada funkcjonowania</option>
                            <option value="Wada wymiarowa">Wada wymiarowa</option>
                        </select>
                    </td>
                    <td>
                        <textarea v-model="form.opis_niezgodnosci" style="width:250px"></textarea>
                    </td>
                    <td>
                        <textarea v-model="form.uwagi_inwestora" style="width:150px"></textarea>
                    </td>
                    <td class="clientside">

                    </td>
                    <td>
                        <select name="" id="" v-model="form.status" disabled>
                            <option value="Zgłoszona">Zgłoszona</option>
                            <option value="Wykonana">Wykonana</option>
                            <option value="Zatwierdzona">Zatwierdzona</option>
                            <option value="Niezatwierdzona">Niezatwierdzona</option>
                            <option value="Rezygnacja">Rezygnacja</option>
                            <option value="Niezasadna">Niezasadna</option>
                        </select>
                    </td>
                    <td>
                        <textarea style="width:400px"  v-model="form.komentarz_serwisu" :disabled="user.group == 'klient'">></textarea>
                    </td>
                    <td>
                        <input  v-model="form.nr_oferty" style="width:100px" :disabled="user.group == 'klient'">
                    </td>
                    <td>
                        <input  v-model="form.SPW" style="width:100px" :disabled="user.group == 'klient'">
                    </td>
                    <!-- <td>
                        <input v-model="form.x">
                    </td>
                    <td>
                        <input  v-model="form.y">
                    </td> -->
                    <td>
                        <select name="" id="" v-model="form.klasyfikacja" :disabled="user.group == 'klient'">>
                            <option value="Gwarancyjna">Gwarancyjna</option>
                            <option value="W normie">W normie</option>
                            <option value="Odpłatna">Odpłatna</option>
                            <option value="Błędne zgłoszenie">Błędne zgłoszenie</option>
                        </select>

                    </td>
                  



                </tr>
            </table>

        </div>




    </div>


    <div class="container" v-show="activesection == 'plany'">

        <button @click="generateMap">Generuj mapę</button>

        <div id="mapcontainer">
            <div id="map" style="height:600px;width:800px"></div>
        </div>

    </div>



    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.2/axios.min.js"></script>

    <script src="scriptproject.js">

    </script>
</body>

</html>


    <!-- <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script> -->