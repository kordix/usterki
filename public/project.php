<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>localhost:8003</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/mybootstrap.css">

    <meta name="robots" content="noindex">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">


</head>

<body>



    <p id="projectid" style="display:none">
        <?php echo $_GET['id'] ?>
    </p>

    <div id="navbar">
        <a href="/"><i style="color:black" class="bi bi-arrow-left"></i></a>
        <span>{{project.nazwa_projektu}} {{project.adres}} </span>
        <span></span>
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
                    <th>id</td>
                    <th style="width:140px">
                        Data zgłoszenia
                    </th>
                  
                    <th style="width:20px">
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
                    <th class="clientside">
                        <b>Uwagi inwestora</b>
                    </th>
                     <th>
                        <b>Status</b>
                    </th>
                    <th style="width:300px">
                        <b>Komentarz serwisu</b>
                    </th>
                   
                    <th>
                        SPW
                    </th>
                   
                    <th>
                        <span style="opacity:0.8">Termin zgłoszenia</span>
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
                    <th class="clientside">
                        <input type="text" v-model="filtry.uwagi_inwestora" style="width:95%">
                    </th>
                    <th>
                        <select name="" id="" v-model="filtry.status" >
                            <option value="">-</option>
                            <option value="Zgłoszona">Zgłoszona</option>
                            <option value="Wykonana">Wykonana</option>
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
                        <input type="text" v-model="filtry.SPW">
                    </th>

               
                    <th>
                        <select name="" id="" v-model="filtry.termin_zgloszenia" style="width:95%">
                            <option value="">-</option>
                            <option value="Pomontażowa">Pomontażowa</option>
                            <option value="Odbiorowa">Odbiorowa</option>
                            <option value="Lokatorska">Lokatorska</option>
                        </select>
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
                <tr v-for="(elem,index) in filtered" :class="{'wykonana':elem.status == 'wykonana'}">
                    <td>#{{elem.id}}</td>
                    <td><span style="width:100px;display:block">  {{elem.created_at}}</span></td>
                    <td @click="handleChange(elem,'lokal')">
                        <span v-if="!elem.editable"> {{elem.lokal}}</span>
                        <input :id="elem.id+'lokal'" v-else type="text" v-model="elem.lokal" @change="updateAuto(elem,'lokal')" style="width:90%" @blur.stop="elem.editable = false">
                    </td>

                    <td @click="handleChange(elem,'adres_admin')">
                        <span v-if="!elem.editable"> {{elem.adres_admin}}</span>
                        <input :id="elem.id+'adres_admin'"  v-else type="text" v-model="elem.adres_admin" @change="updateAuto(elem,'adres_admin')" @blur.stop="elem.editable = false">
                    </td>

                    <td @click="handleChange(elem,'nr_admin')" >
                        <span v-if="!elem.editable"> {{elem.nr_admin}}</span>
                        <input :id="elem.id+'nr_admin'"  v-else type="text" v-model="elem.nr_admin" @change="updateAuto(elem,'nr_admin')" @blur.stop="elem.editable = false">
                    </td>

                    <td @click="handleChange(elem,'kontakt_klient')" >
                        <span v-if="!elem.editable"> {{elem.kontakt_klient}}</span>
                        <input :id="elem.id+'kontakt_klient'"  v-else type="text" v-model="elem.kontakt_klient" @change="updateAuto(elem,'kontakt_klient')" @blur.stop="elem.editable = false">
                    </td>

                    <td @click="handleChange(elem,'data_klient')">
                        <span v-if="!elem.editable"> {{elem.data_klient}}</span>
                        <input v-else :id="elem.id+'data_klient'" type="date" v-model="elem.data_klient" @blur.stop="updateAuto(elem,'data_klient')">
                    </td>
                    <td @click="handleChange(elem,'typ_niezgodnosci')">
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
                    <td @click="handleChange(elem,'opis_niezgodnosci')">
                        <span v-if="!elem.editable"> {{elem.opis_niezgodnosci}}</span>
                        <textarea :id="elem.id+'opis_niezgodnosci'" v-else v-model="elem.opis_niezgodnosci" @change="updateAuto(elem,'opis_niezgodnosci')" @blur.stop="elem.editable = false"></textarea>
                    </td>
                    <td @click="handleChange(elem,'uwagi_inwestora')" class="clientside">
                        <span v-if="!elem.editable"> {{elem.uwagi_inwestora}}</span>
                        <input :id="elem.id+'uwagi_inwestora'" v-else  v-model="elem.uwagi_inwestora" @change="updateAuto(elem,'uwagi_inwestora')">
                    </td>

                    <td  @click="handleChange(elem,'status')" >
                        <span v-if="!elem.editable">{{elem.status}}</span>
                        <select name="" :id="elem.id+'status'"  v-model="elem.status"
                            @change="updateAuto(elem,'status')" style="width:95%" v-if="elem.editable">
                            <option value="Zgłoszona">Zgłoszona</option>
                            <option value="Wykonana">Wykonana</option>
                            <option value="Zatwierdzona">Zatwierdzona</option>
                            <option value="Niezatwierdzona">Niezatwierdzona</option>
                            <option value="Rezygnacja">Rezygnacja</option>
                            <option value="Niezasadna">Niezasadna</option>
                    
                        </select>

                    </td>

                    <td @click="handleChange(elem,'komentarz_serwisu')" >
                        <span v-if="!elem.editable"> {{elem.komentarz_serwisu}}</span>
                        <textarea :id="elem.id+'komentarz_serwisu'" v-else  v-model="elem.komentarz_serwisu" @change="updateAuto(elem,'komentarz_serwisu')" style="width:95%"></textarea>
                    </td>
                    <td @click="handleChange(elem,'SPW')">
                        <span v-if="!elem.editable"> {{elem.SPW}}</span>
                        <input :id="elem.id+'SPW'" v-else  v-model="elem.SPW" @change="updateAuto(elem,'SPW')" @blur="elem.editable = false">
                    </td>
                    <td @click="handleChange(elem,'termin_zgloszenia')">
                        <span v-if="!elem.editable"> {{elem.termin_zgloszenia}}</span>
                        <select v-else name="" :id="elem.id+'termin_zgloszenia'" v-model="elem.termin_zgloszenia" @change="updateAuto(elem,'termin_zgloszenia')" style="width:95%" @blur="elem.editable = false">
                            <option value="Pomontażowa">Pomontażowa</option>
                            <option value="Odbiorowa">Odbiorowa</option>
                            <option value="Lokatorska">Lokatorska</option>
                        </select>

                       
                    </td>
                     <!-- <td @click="elem.editable = true">
                        <span v-if="!elem.editable" >{{elem.x}}</span>
                        <input v-else type="text" v-model="elem.x" @change="updateAuto(elem,'x')" style="width:20px" placeholder="y">
                    </td>
                    <td @click="elem.editable = true">
                        <span v-if="!elem.editable" >{{elem.y}}</span>
                        <input v-else type="text" v-model="elem.y" @change="updateAuto(elem,'y')" style="width:20px" placeholder="y">
                    </td> -->
                    <td @click="elem.editable = true">
                        <span v-if="!elem.editable">{{elem.klasyfikacja}}</span>
                        <select name="" id="" v-model="elem.klasyfikacja"
                            @change="updateAuto(elem,'klasyfikacja')" style="width:95%" v-if="elem.editable">
                            <option value="Gwarancyjna">Gwarancyjna</option>
                            <option value="W normie">W normie</option>
                            <option value="Odpłatna">Odpłatna</option>
                            <option value="Błędne zgłoszenie">Błędne zgłoszenie</option>
                        </select>

                    </td>
                    


                    <td><button class="btn-sm btn-danger" @click="usun(elem.id)">Usuń</button></td>


                </tr>
                <!-- DODAWANIE -->
                <tr id="addformtable" class="addrow">
                    <td><button class="btn btn-primary" style="padding:2px" @click="save"
                            v-if="crudmode == 'add' && form.lokal" ><i class="bi bi-floppy"></i></button><span v-else>+</span>
                       
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
                    <td class="clientside">
                        <textarea v-model="form.uwagi_inwestora" style="width:150px"></textarea>
                    </td>
                    <td>
                        <select name="" id="" v-model="form.status">
                            <option value="Zgłoszona">Zgłoszona</option>
                            <option value="Wykonana">Wykonana</option>
                            <option value="Zatwierdzona">Zatwierdzona</option>
                            <option value="Niezatwierdzona">Niezatwierdzona</option>
                            <option value="Rezygnacja">Rezygnacja</option>
                            <option value="Niezasadna">Niezasadna</option>
                        </select>
                    </td>
                    <td>
                        <textarea style="width:200px"  v-model="form.komentarz_serwisu"></textarea>
                    </td>
                    <td>
                        <input  v-model="form.SPW" style="width:100px">
                    </td>
                    <td>
                        <select name="" id="" v-model="form.termin_zgloszenia" disabled>
                            <option value="Pomontażowa">Pomontażowa</option>
                            <option value="Odbiorowa">Odbiorowa</option>
                            <option value="Lokatorska">Lokatorska</option>
                        </select>  
                    </td>
                    <!-- <td>
                        <input v-model="form.x">
                    </td>
                    <td>
                        <input  v-model="form.y">
                    </td> -->
                    <td>
                        <select name="" id="" v-model="form.klasyfikacja">
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


    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.2/axios.min.js"></script>

    <script src="scriptproject.js">

    </script>
</body>

</html>