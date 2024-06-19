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
    <title>Usterki lokatorskie</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/mybootstrap.css">
    <link rel="stylesheet" href="/css/lightbox.css">


    <meta name="robots" content="noindex">

    <!-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" /> -->

       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">


</head>

<body >

    <div v-cloak> 

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
                    <td colspan="13" style="background:lightgreen;text-align:center;" id="strefa1td">
                        <template style="display:flex;justify-content:space-between">
                            <span><i class="bi bi-funnel"></i> Wyfiltrowano: {{filtered.length}} / {{usterki.length}}  
                            
                                <span v-if="hiddenColumns.length > 0 " style="background:white">
                                <span ><b> Schowane kolumny:</b></span>
                                <span style="cursor:pointer" v-for="hid in hiddenColumns" @click="hideColumn(hid,true)">{{hid}} &nbsp;  </span>  
                                </span>
                            </span>
                            <span>STREFA KLIENTA</span>
                            <span></span>
                        </template>
                        
                    </td>
                    <td colspan="7" style="background:darksalmon;text-align:center" id="strefa2td">
                        STREFA SERWISU
                    </td>
                </tr>
                <tr style="cursor:pointer" id="rowheader">
                    <th @click="sortuj('id')">id</td>
                    <th style="width:140px" @click="sortuj('created_at')">
                        Data zgłoszenia
                    </th>
                  
                    <th style="width:20px;cursor:pointer" @click="sortuj('lokal')">
                        Nr budowlany
                    </th>
                    <th @click="sortuj('adres_admin')">
                        Adres administracyjny
                    </th>
                    <th @click="sortuj('nr_admin')">Nr admin.</th>
                    <th id="thkontakt_klient" @click="sortuj('kontakt_klient')" style="position:relative">Kontakt do klienta <div class="hover-element"><i class="bi bi-eye-slash" style="font-size:20px" @click.stop="hideColumn('kontakt_klient')"></i></div></th>
                    <th id="thdata_klient" @click="sortuj('data_klient')" >Data zgłoszenia przez klienta <div class="hover-element"><i class="bi bi-eye-slash" style="font-size:20px" @click.stop="hideColumn('data_klient')"></i></div></th>
                    <th @click="sortuj('typ_niezgodnosci')" title="Zgłoszony typ usterki">
                        <span>Zgł. typ usterki</span>
                    </th>
                    <th @click="sortuj('opis_niezgodnosci')">
                        <b>Zgłoszony opis usterki</b>
                    </th>
                    <th @click="sortuj('uwagi_inwestora')">
                        <b>Uwagi inwestora</b>
                    </th>
                 
                    <th @click="sortuj('nr_zlecenia')">
                        Nr zlecenia
                    </th>
                    <th @click="sortuj('nr_pozycji')">
                        Nr pozycji
                    </th>
                  
                    <th class="clientside" >
                        Załączniki
                    </th>
                    <th @click="sortuj('status')">
                        <b>Status</b>
                    </th>
                    <th @click="sortuj('typ_niezgodnosci_serwis')">
                        Typ usterki
                    </th>
                    <th @click="sortuj('opis_niezgodnosci_serwis')">
                        Opis usterki
                    </th>
                    <th style="width:300px" @click="sortuj('komentarz_serwisu')">
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
                    <th>
                        <input type="date" v-model="filtry.date_start">
                        <input type="date" v-model="filtry.date_end" v-if="filtry.date_start">
                    </th>
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
                  
                    <th>
                        <input type="text" v-model="filtry.nr_zlecenia" style="width:95%">
                    </th>
                    <th>
                        <input type="text" v-model="filtry.nr_pozycji" style="width:95%">
                    </th>
                   
                    <th class="clientside">

                    </th>
                    <th>
                        <select name="" id="" v-model="filtry.status" style="width:110px">
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
                        <select name="" id="" v-model="filtry.typ_niezgodnosci_serwis" class="typusterkiselect">
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
                        <input type="text" v-model="filtry.opis_niezgodnosci_serwis">
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
                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'lokal')" title="Nr budowlany">
                            <span v-if="!elem.editable"> {{elem.lokal}}</span>
                            <input @keyup.enter="elem.editable = false" :id="elem.id+'lokal'" v-else type="text" v-model="elem.lokal" @change="updateAuto(elem,'lokal')" style="width:90%" @blur.stop="elem.editable = false">
                        </td>

                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'adres_admin')" title="Adres administracyjny">
                            <span v-if="!elem.editable"> {{elem.adres_admin}}</span>
                            <input @keyup.enter="elem.editable = false" :id="elem.id+'adres_admin'"  v-else type="text" v-model="elem.adres_admin" @change="updateAuto(elem,'adres_admin')" @blur.stop="elem.editable = false">
                        </td>

                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'nr_admin')" title="Numer administracyjny">
                            <span v-if="!elem.editable"> {{elem.nr_admin}}</span>
                            <input :id="elem.id+'nr_admin'"  v-else type="text" v-model="elem.nr_admin" @change="updateAuto(elem,'nr_admin')" @blur.stop="elem.editable = false">
                        </td>

                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'kontakt_klient')" title="Kontakt do klienta">
                            <span v-if="!elem.editable"> {{elem.kontakt_klient}}</span>
                            <input :id="elem.id+'kontakt_klient'"  v-else type="text" v-model="elem.kontakt_klient" @change="updateAuto(elem,'kontakt_klient')" @blur.stop="elem.editable = false">
                        </td>

                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'data_klient')" title="Data zgłoszenia przez klienta">
                            <span v-if="!elem.editable"> {{elem.data_klient}}</span>
                            <input v-else :id="elem.id+'data_klient'" type="date" v-model="elem.data_klient" @blur.stop="updateAuto(elem,'data_klient')">
                        </td>
                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'typ_niezgodnosci')" title="Typ usterki">
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
                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'opis_niezgodnosci')" title="Opis usterki" class="disabledcursor">
                            <span v-if="!elem.editable">  {{elem.opis_niezgodnosci}}</span>
                            <textarea :id="elem.id+'opis_niezgodnosci'" v-else v-model="elem.opis_niezgodnosci" @change="updateAuto(elem,'opis_niezgodnosci')" @blur.stop="elem.editable = false" style="width:95%"></textarea>
                        </td>

                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'uwagi_inwestora')" title="Uwagi inwestora">
                            <span v-if="!elem.editable"> {{elem.uwagi_inwestora}}</span>
                            <input :id="elem.id+'uwagi_inwestora'" v-else  v-model="elem.uwagi_inwestora" @change="updateAuto(elem,'uwagi_inwestora')" @blur.stop="elem.editable = false">
                        </td>
                       

                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'nr_zlecenia')" title="Numer zlecenia">
                            <span v-if="!elem.editable"> {{elem.nr_zlecenia}}</span>
                            <input :id="elem.id+'nr_zlecenia'" v-else  v-model="elem.nr_zlecenia" @change="updateAuto(elem,'nr_zlecenia')" @blur.stop="elem.editable = false">
                        </td>

                        <td :rowspan="1 + extras.filter(el=>el.usterka_id == elem.id).length" @click="handleChange(elem,'nr_pozycji')" title="Numer pozycji">
                            <span v-if="!elem.editable"> {{elem.nr_pozycji}}</span>
                            <input :id="elem.id+'nr_pozycji'" v-else  v-model="elem.nr_pozycji" @change="updateAuto(elem,'nr_pozycji')" @blur.stop="elem.editable = false">
                        </td>

                    

                        <td class="clientside" title="załączniki">
                            
                            <!-- <a v-if="elem.link":href="elem.link" target="_blank">link</a>
                            <input :id="elem.id+'link'" v-if="!elem.link" type="text" v-model="elem.link" @change="updateAuto(elem,'link')" style="width:100px" @blur.stop="elem.editable = false"> -->

                            <div style="display:flex;justify-content:flex-between;width:100px">
                                <div title="Podpięty załącznik">
                                    <a v-for="file in files.filter((el)=>el.usterka_id == elem.id)" :class="{adminfile:file.group == 'admin' }"target="_blank" :href="/uploads/+file.filename"> <i style="font-size:20px" class="bi bi-file-arrow-down"></i> </a>
                                </div>
                                <i class="bi bi-cloud-arrow-up" style="display:inline-block;font-size:20px;margin-left:auto;cursor:pointer" @click="showAttachments(elem.id)" title="dodaj załącznik"></i>

                            </div>

                        </td>

                        <!-- SERWIS -->
                        <td  @click="handleChange(elem,'status')" :class="{disabledcursor:user.group == 'klient'}" title="status">
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

                        <td @click="handleChange(elem,'typ_niezgodnosci_serwis')" :class="{disabledcursor:user.group == 'klient'}" style="position:relative" title="komentarz serwisu">
                             <span v-if="!elem.typ_niezgodnosci_serwis" style="opacity:0.8">{{elem.typ_niezgodnosci}}</span>
                            <span v-if="!elem.editable && elem.typ_niezgodnosci_serwis"><i class="bi bi-exclamation-octagon" style="color:red"></i> {{elem.typ_niezgodnosci_serwis}}</span>

                            <select name="" :id="elem.id+'typ_niezgodnosci'" v-model="elem.typ_niezgodnosci_serwis" @change="updateAuto(elem,'typ_niezgodnosci_serwis')" style="width:95%" v-if="elem.editable" @blur.stop="elem.editable = false">
                            <option value="">-</option>
                                <option value="Wada szyby">Wada szyby</option>
                                <option value="Uszkodzone / Wada powierzchni">Uszkodzone / Wada powierzchni</option>
                                <option value="Niezgodność asortymentowa">Niezgodność Asortymentowa</option>
                                <option value="Brak / niekompletność">Brak / niekompletność</option>
                                <option value="Wada funkcjonowania">Wada funkcjonowania</option>
                                <option value="Wada wymiarowa">Wada wymiarowa</option>
                            </select>
                            

                        </td>

                       <td @click="handleChange(elem,'opis_niezgodnosci_serwis')" :class="{disabledcursor:user.group == 'klient'}" style="position:relative" title="komentarz serwisu">
                           <span v-if="!elem.opis_niezgodnosci_serwis" style="opacity:0.8">{{elem.opis_niezgodnosci}}</span>
                            <span v-if="!elem.editable && elem.opis_niezgodnosci_serwis"><i class="bi bi-exclamation-octagon" style="color:red"></i> {{elem.opis_niezgodnosci_serwis}}</span>
                            <textarea :id="elem.id+'opis_niezgodnosci_serwis'" v-if="elem.editable"  v-model="elem.opis_niezgodnosci_serwis" :disabled="user.group == 'klient'" @change="updateAuto(elem,'opis_niezgodnosci_serwis')" style="width:95%" @blur="elem.editable = false"></textarea>
                        </td>

                        <td @click="handleChange(elem,'komentarz_serwisu')" :class="{disabledcursor:user.group == 'klient'}" style="position:relative" title="komentarz serwisu">
                            <span v-if="!elem.editable">  <b v-if="extras.filter(el=>el.usterka_id == elem.id).length"> {{elem.usterka_numer}}.1</b> {{elem.komentarz_serwisu}}</span>
                            <textarea :id="elem.id+'komentarz_serwisu'" v-else  v-model="elem.komentarz_serwisu" :disabled="user.group == 'klient'" @change="updateAuto(elem,'komentarz_serwisu')" style="width:95%" @blur="elem.editable = false"></textarea>
                        </td>
                        <td @click="handleChange(elem,'nr_oferty')" :class="{disabledcursor:user.group == 'klient'}" title="Nr oferty">
                            <span v-if="!elem.editable"> {{elem.nr_oferty}}</span>
                            <input :id="elem.id+'nr_oferty'" v-else  v-model="elem.nr_oferty" @change="updateAuto(elem,'nr_oferty')" @blur="elem.editable = false">
                        </td>
                        <td @click="handleChange(elem,'SPW')" :class="{disabledcursor:user.group == 'klient'}" title="SPW">
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
                        <td @click="elem.editable = true" :class="{disabledcursor:user.group == 'klient'}" title="klasyfikacja">
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
                                <button class="btn-sm btn-danger" @click="deletedialogbool=true;activeusterka=elem.id" style="display:inline-block;margin-right:5px"><i class="bi bi-trash"></i></button>
                                <button @click="addExtra(elem.id)" style="display:inline-block;margin-right:5px" :disabled="user.group == 'klient'">+</button>
                                <button class="btn-sm btn-warning" @click="revealdialogbool = true;activeusterka = elem.id" style="display:inline-block;margin-right:5px" v-if="user.group != 'klient' && elem.hidden"><i class="bi bi-eye"></i></button>
                                <button @click="logdialogbool = true;activeusterka = elem.id" class="btn-sm btn-secondary" style="display:inline-block;margin-right:5px" :disabled="user.group == 'klient'"><i class="bi bi-newspaper"></i></button>
                                

                                
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
                            
                            <!-- <a v-if="ext.link":href="ext.link" target="_blank">link</a> -->
                            <!-- <input :id="ext.id+'link'" v-if="!ext.link" type="text" v-model="ext.link" @change="updateAuto(elem,'link','extra')" style="width:100px;" @blur.stop="ext.editable = false"> -->

                        </td>
                        <td @click="handleChangeExtra(ext,'status')" :class="{disabledcursor:user.group == 'klient'}"> 
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

                        <td @click.stop="handleChangeExtra(ext,'typ_niezgodnosci_serwisextra')" :class="{disabledcursor:user.group == 'klient'}" style="position:relative">
                            <span v-if="!ext.editable"> {{ext.typ_niezgodnosci_serwis}}</span>
                            <select name="" :id="ext.id+'typ_niezgodnosci'" v-model="ext.typ_niezgodnosci_serwis" @change="updateAuto(ext,'typ_niezgodnosci_serwis','extra')" style="width:95%" v-if="ext.editable" @blur.stop="ext.editable = false">
                            <option value="">-</option>
                                <option value="Wada szyby">Wada szyby</option>
                                <option value="Uszkodzone / Wada powierzchni">Uszkodzone / Wada powierzchni</option>
                                <option value="Niezgodność asortymentowa">Niezgodność Asortymentowa</option>
                                <option value="Brak / niekompletność">Brak / niekompletność</option>
                                <option value="Wada funkcjonowania">Wada funkcjonowania</option>
                                <option value="Wada wymiarowa">Wada wymiarowa</option>
                            </select>
                        
                        </td>

                        <td @click.stop="handleChangeExtra(ext,'opis_niezgodnosci_serwisextra')" :class="{disabledcursor:user.group == 'klient'}" style="position:relative">
                        <span v-if="!ext.editable"> {{ext.opis_niezgodnosci_serwis}}</span>
                            <textarea v-if="ext.editable" :id="ext.id+'opis_niezgodnosci_serwisextra'"  v-model="ext.opis_niezgodnosci_serwis" :disabled="user.group == 'klient'" @change="updateAuto(ext,'opis_niezgodnosci_serwis','extra')" style="width:95%" @blur.stop="ext.editable = false"></textarea>
                        </td>


                        <td @click.stop="handleChangeExtra(ext,'komentarz_serwisuextra')" :class="{disabledcursor:user.group == 'klient'}" style="position:relative">
                            <span v-if="!ext.editable"><b> {{usterki.find((el)=>el.id == ext.usterka_id).usterka_numer }}.{{ext.extra_numer}} </b>  {{ext.komentarz_serwisu}}</span>
                            <textarea :id="ext.id+'komentarz_serwisuextra'" v-else  v-model="ext.komentarz_serwisu" :disabled="user.group == 'klient'" @change="updateAuto(ext,'komentarz_serwisu','extra')" style="width:95%" @blur.stop="elem.editable = false"></textarea>
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
                            
                        </td>
                        <td>
                            
                        <button class="btn-sm btn-danger" @click="usunExtra(ext.id)" style="display:inline-block;margin-right:5px">Usuń</button>
                            

                        </td>
                    </tr>
                </template>
                <!-- DODAWANIE -->
                <tr id="addformtable" class="addrow">
                    <td><template v-if="crudmode == 'add' && form.opis_niezgodnosci" > 
                            <button title="zapisz" @click="save(false)" class="btn btn-primary" style="padding:2px" ><i class="bi bi-floppy"></i></button>
                            <button title="zapisz jako ukryte" @click="save(true)" class="btn btn-primary" style="padding:2px;margin-top:2px" v-if="user.group == 'admin'"><i class="bi bi-floppy"></i><i class="bi bi-eye-slash"></i></button>

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
                        <textarea  type="text" v-model="form.kontakt_klient" style="width:150px"></textarea>
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
                  
                    <td>
                        <input  v-model="form.nr_zlecenia" style="width:100px">
                    </td>
                    <td>
                        <input  v-model="form.nr_pozycji" style="width:100px">
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
                        <select name="" id="" v-model="form.typ_niezgodnosci_serwis" class="typusterkiselect">
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
                        <textarea  v-model="form.opis_niezgodnosci_serwis" :disabled="user.group == 'klient'">></textarea>
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

                    <td style="background:white" colspan="2" v-if="user.group == 'admin'">
                        <button class="btn btn-primary" @click="exportToExcel"><i class="bi bi-download"></i> <i class="bi bi-file-earmark-spreadsheet"></i></button>
                       
                    </td>
                  



                </tr>
            </table>

        </div>




    </div>

   <div class="lightbox" id="attachmentslightbox" :class="{active:attachmentsbool}">
        <div style="background:white;width:90%;border-radius:5px;padding:20px;">
            <p style="color:red" v-if="loadingfile">Ładowanie...</p>
            <label for=""> Dodaj plik:</label>
            <input type="file" name="file" id="fileToUpload">
            <button @click="upload()">Wyślij</button>

            <br> <br>
            
            <p v-for="message in attachmentmessages">{{message}}</p>

            <button @click="attachmentsbool = ! attachmentsbool" class="btn btn-danger">x Zamknij</button>
        </div>
    </div>

    <div class="lightbox" id="deletelightbox" :class="{active:deletedialogbool}">
        <div style="background:white;width:90%;border-radius:5px;padding:20px;">
           
            <p> Czy na pewno chcesz usunąć usterkę?</p>   
            <button class="btn-sm btn-danger" @click="usun(activeusterka)" style="display:inline-block;margin-right:5px">Usuń <i class="bi bi-trash"></i></button>

            <br><br>
            <button @click="deletedialogbool = !deletedialogbool" class="btn btn-danger">x Zamknij</button>
        </div>
    </div>

    <div class="lightbox" id="reveallightbox" :class="{active:revealdialogbool}">
        <div style="background:white;width:90%;border-radius:5px;padding:20px;">
           
            <p> Czy na pewno chcesz uwidocznić usterkę?</p>   
            <button class="btn-sm btn-warning" @click="hide(activeusterka,true)" style="display:inline-block;margin-right:5px">Uwidocznij <i class="bi bi-eye"></i></button>

            <br><br>
            <button @click="revealdialogbool = !revealdialogbool" class="btn btn-danger">x Zamknij</button>
        </div>
    </div>

    <div class="lightbox" id="reveallightbox" :class="{active:logdialogbool}">
        <div style="background:white;width:90%;border-radius:5px;padding:20px;">
        <p><b>Historia usterki:</b></p>
        <table>
            <tr>
                <td>
                    Login
                </td>
                 
                <td>
                    Data
                </td>
                 <td>
                    Rodzaj akcji
                </td>
                <td>
                    Kolumna
                </td>
              
            </tr>
            <tr v-for="elem in logs.filter((el)=>el.usterka_id == activeusterka )">
                <td>
                    {{elem.login}}
                </td>
                <td>
                    {{elem.created_at}}
                </td>
                <td>
                    <span v-if="elem.action == 'add'">Dodano usterkę</span>
                    <span v-if="elem.action == 'update'">Zmiana</span>

                </td>
                <td>
                    <span v-if="elem.action == 'add'">-</span>
                    <span v-else>{{elem.kolumna}}</span>


                    
                </td>
            </tr>
        </table>

            <br>
            <button @click="logdialogbool = !logdialogbool" class="btn btn-danger">x Zamknij</button>
        </div>
    </div>


    <br><br>

   


    <div class="container" v-show="activesection == 'plany'">

        <button @click="generateMap">Generuj mapę</button>

        <div id="mapcontainer">
            <div id="map" style="height:600px;width:800px"></div>
        </div>

    </div>

    <button class="btn btn-success" @click="excelbulkbool = !excelbulkbool" style="margin:10px"> <i class="bi bi-upload"></i> Import excel

    </button>

    <div v-if="excelbulkbool" style="margin:10px">
        <button style="display:block" class="btn btn-danger" v-if="excelinput" @click="loadExcelBulk"><i class="bi bi-floppy"></i> Wczytaj dane</button>

        <textarea name="" id="" rows="10" cols="150" v-model="excelinput" placeholder="wklej wiersze z excela"></textarea><br>
     </div>



      </div>



    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.2/axios.min.js"></script>

    <script src="scriptproject.js" type="module">

    </script>

  
</body>

</html>


    <!-- <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script> -->