let map;

import writeExcelFile from 'https://cdn.jsdelivr.net/npm/write-excel-file@2.0.2/+esm';


Vue.createApp({
    data() {
        return {
            user: {},
            sortkey: '',
            filtry: {
                id: null,
                opis_niezgodnosci: null,
                kontakt_klient: null,
                date_start: null,
                date_end: null
            },
            mapwidth: 800,
            mapheight: 600,
            points: [
                { x: 0.25, y: 0.25 },

            ],
            scale: 1,

            activesection: 'usterki',
            project: {},
            usterki: [],
            form: {

            },
            activeproject: null,
            messages: [],
            searchbufor: '',
            sortorder: 1,
            formbool: false,
            form: {
                id: 0,
                x: 0,
                y: 0,
                opis_niezgodnosci: '',
                adres: '',
                status: 'Zgłoszona',
                termin_zgloszenia: 'Lokatorska'
            },
            crudmode: 'add',
            extras: [
                {
                    usterka_id: 1,
                    status: 'Zgłoszona',
                    komentarz_serwisu: 'nowa rzecz',
                    spw: '',
                    klasyfikacja: ''

                }
            ],
            files: [],
            attachmentsbool: false,
            activeusterka: null,
            attachmentmessages: [],
            loadingfile: false,
            excelinput: '',
            deletedialogbool: false,
            revealdialogbool: false

        }
    },
    async mounted() {
        const self = this;
        const id = document.querySelector('#projectid').innerHTML;
        await axios.get('api/project.php?id=' + id).then((res) => self.project = res.data[0]);
        await axios.get('api/usterki.php?id=' + id).then((res) => self.usterki = res.data);
        await axios.get('api/extras.php?id=' + id).then((res) => self.extras = res.data);

        await axios.get('api/getuser.php').then((res) => self.user = res.data);
        await axios.get('api/files.php?projectid=' + id).then((res) => self.files = res.data);

        document.onkeydown = function (evt) {
            evt = evt || window.event;

            if (evt.key === "Escape" || evt.key === "Esc") {
                evt.preventDefault();
                if (self.attachmentsbool) {
                    self.attachmentsbool = false;
                }
                else if (self.deletedialogbool) {
                    self.deletedialogbool = false;
                }
            }
        }

        this.filtry.date_end = new Date().toISOString().split('T')[0];





    },
    methods: {
        shouldAddSeparator(index) {
            // return false;
            // console.log('dupa');
            if (this.sortkey == 'lokal') {
                if (index === 0) return false; // Nie dodaj separatora przed pierwszym elementem
                if (this.usterki[index].lokal !== this.usterki[index - 1].lokal) {
                    console.log('DUPA');
                }
                return this.usterki[index].lokal !== this.usterki[index - 1].lokal;
            } else {
                return false;
            }
        },

        setPlany() {
            let self = this;
            this.activesection = 'plany';
            setTimeout(() => {
                self.generateMap();
            }, 150)
        },
        handleChange(elem, kolumna, tabela) {
            console.log(elem);
            if (['opis_niezgodnosci'].indexOf(kolumna) >= 0) {
                return;
            }
            if (this.user.group == 'klient') {
                if (['status', 'komentarz_serwisu', 'SPW', 'termin_zgloszenia', 'klasyfikacja'].indexOf(kolumna) > -1) {
                    return
                }
            }

            if (this.user.group == 'admin') {
                if (['typ_niezgodnosci', 'opis_niezgodnosci', 'adres_admin', 'nr_admin', 'kontakt_klient', 'data_klient', 'uwagi_inwestora'].indexOf(kolumna) > -1) {
                    return
                }
            }

            elem.editable = true;
            console.log(elem.id + kolumna);
            setTimeout(() => {
                // if (kolumna != 'komentarz_serwisu') {
                if (document.getElementById(elem.id + kolumna)) {
                    document.getElementById(elem.id + kolumna).focus();
                }
                // }
            }, 100);

        },
        handleChangeExtra(elem, kolumna) {
            console.log('handleChangeExtra');
            console.log(elem);
            elem.editable = true;
            setTimeout(() => {
                // if (kolumna != 'komentarz_serwisu') {
                if (document.getElementById(elem.id + kolumna)) {
                    document.getElementById(elem.id + kolumna).focus();
                }
                // }
            }, 100);

        },
        editelem(elem) {
            elem.editable = true;
        },
        edit(id) {
            this.crudmode
            this.form = this.usterki.find((el) => el.id == id);
            this.formbool = true;
        },
        deleteusterka() {
            axios.get('api/usterkadelete.php?id=' + this.form.id).then((res) => location.reload())
        },
        hideUsterka() {
            axios.get('api/usterkahide.php?id=' + this.form.id).then((res) => location.reload())
        },
        generateMap() {
            let self = this;
            map = L.map('map', {
                crs: L.CRS.Simple
            });

            let mapheight = this.mapheight;
            let mapwidth = this.mapwidth;
            let bounds = [[0, 0], [mapheight, mapwidth]];
            L.imageOverlay('alvarium_B_p2.png', bounds).addTo(map);

            map.fitBounds(bounds);
            // map.setView([mapheight, 0], 0);

            map.on('click', self.onMapClick);

            this.generatePoints();



        },
        async updateAuto(elem, column, tabela) {
            let self = this;
            let formlocal = {}
            formlocal[column] = elem[column];
            formlocal.kolumna = column;


            let endpoint = 'api/usterkaupdate.php';

            if (tabela === 'extra') {
                endpoint = 'api/extraupdate.php';
            }

            await fetch(endpoint, { method: 'POST', body: JSON.stringify({ dane: formlocal, id: elem.id }) }).then((res) => self.messages.push('zapisano zmiany'));
            elem.editable = false;
        },
        generatePoints() {
            let mapheight = this.mapheight;
            let mapwidth = this.mapwidth;
            for (let i = 0; i < this.usterki.length; i++) {
                let elem = this.usterki[i];
                if (elem.x) {
                    let color = 'red';
                    console.log(elem.status);
                    if (elem.status == 'wykonana') {
                        color = 'green';
                    }
                    if (elem.status == 'zgloszona') {

                    }
                    var circle = L.circleMarker([mapheight * elem.y, mapwidth * elem.x], {
                        radius: 10,
                        color: color, // Kolor koła
                        fillColor: color,
                        fillOpacity: 1
                    }).addTo(map);

                }
            }
        },
        onMapClick(e) {
            let self = this;
            this.form.y = (e.latlng.lat / self.mapheight);
            this.form.x = (e.latlng.lng / self.mapwidth);

            alert(e.latlng.lat + ' ' + e.latlng.lng)

            // var circle = L.circleMarker([e.latlng.lat, e.latlng.lng], {
            //     radius: 10,
            //     color: 'red', // Kolor koła
            //     fillColor: '#fff',
            //     fillOpacity: 0.5
            // }).addTo(map);

        },

        test() {
            console.log('test');
        },
        async save(hidden) {
            let self = this;
            const id = document.querySelector('#projectid').innerHTML;
            this.form.project_id = id;
            console.log(hidden);
            if (hidden === true) {
                this.form.hidden = 1;
            } else {
                this.form.hidden = '';
            }
            await axios.post('api/usterkaadd.php', this.form).then((res) => console.log('fads'));
            location.reload();
        },
        async addExtra(id) {
            const project_id = document.querySelector('#projectid').innerHTML;

            let extraform = {
                status: 'Zgłoszona',
                project_id: project_id,
                usterka_id: id,
                komentarz_serwisu: 'NOWA RZECZ'
            };
            await axios.post('api/extraadd.php', extraform).then((res) => location.reload());


        },
        update(id) {
            let self = this;

            axios.post('api/usterkaupdate.php', { dane: this.form, id: this.form.id })
        },
        updateone(kolumna) {
            let daneobject = {};
            daneobject[kolumna] = this.form[kolumna];
            axios.post('api/usterkaupdate.php', { dane: daneobject, id: this.form.id })

        },
        async usun(id) {
            await axios.get('api/usterkadelete.php?id=' + id);
            location.reload();
        },
        async hide(id, reveal) {
            const projectid = document.querySelector('#projectid').innerHTML;
            if (reveal) {
                await axios.get('api/usterkareveal.php?id=' + id + '&projectid=' + projectid);
            } else {
                await axios.get('api/usterkahide.php?id=' + id);
            }
            location.reload();
        },
        async usunExtra(id) {
            await axios.get('api/usterkaextradelete.php?id=' + id);
            location.reload();
        },
        copyvalues(elem) {
            this.form.adres = elem.adres;
            this.form.nr_admin = elem.nr_admin;
            this.form.adres_admin = elem.adres_admin;

            this.form.lokal = elem.lokal;
            this.form.kontakt_klient = elem.kontakt_klient;
        },
        sortuj(key) {
            console.log(key);
            if (key == this.sortkey) {
                console.log('widzi to samo');
                if (this.sortorder == 1) {
                    this.sortorder = -1;
                } else if (this.sortorder == -1) {
                    this.sortorder = 1;
                }
            } else {
                this.sortkey = key;
            }

        },
        async upload() {
            this.loadingfile = true;
            this.attachmentmessages = [];
            this.attachmentprompt = false;
            let self = this;
            const id = document.querySelector('#projectid').innerHTML;

            const formData = new FormData;
            formData.append('file', document.querySelector('#fileToUpload').files[0]);
            formData.append('description', '');
            formData.append('usterka_id', this.activeusterka);


            await fetch('/api/upload3.php', { method: 'POST', body: formData }).then((res) => res.json()).then((res) => this.attachmentmessages.push(res.message));

            this.loadingfile = false;


            await axios.get('api/files.php?projectid=' + id).then((res) => self.files = res.data);

        },
        showAttachments(id) {
            this.attachmentmessages = [];
            this.attachmentsbool = true;
            this.activeusterka = id;
        },
        loadExcel() {
            // let processedarray = []
            // processedarray = this.excelinput.split('\n');

            let processed = this.excelinput.split('\t');

            this.form.lokal = processed[0];
            this.form.nr_admin = processed[1];
            this.form.adres_admin = processed[2];
            this.form.kontakt_klient = processed[3];
            this.form.data_klient = processed[4];
            this.form.typ_niezgodnosci = processed[5];
            this.form.opis_niezgodnosci = processed[6];
            this.form.uwagi_inwestora = processed[7];
            this.form.nr_zlecenia = processed[8];
            this.form.nr_pozycji = processed[9];



            console.log(processedarray);

        },
        async exportToExcel() {
            const data = [
                // [{ value: 'Name' }, { value: 'Age' }],
                // [{ value: 'John Doe' }, { value: 29 }],
                // [{ value: 'Jane Smith' }, { value: 34 }],
            ];

            let localarrh = []
            for (let key in this.filtered[0]) {

                localarrh.push({ value: key });
            }

            data.push(localarrh);


            for (let i = 0; i < this.filtered.length; i++) {
                let localarr = []
                let elem = this.filtered[i];



                for (let key in this.filtered[i]) {
                    localarr.push({ value: this.filtered[i][key] });
                }

                console.log(localarr);
                data.push(localarr);

            }

            console.log(data);




            try {
                await writeExcelFile(data, { fileName: 'example.xlsx' });
                alert('Excel file has been exported successfully!');
            } catch (error) {
                console.error('Error exporting Excel file:', error);
            }
        },




    },
    computed: {
        filtered: function () {
            let sortorder = this.sortorder;
            let self = this;
            var filterKey = this.searchbufor && this.searchbufor.toLowerCase()
            var order = 1;
            var filtered = this.usterki;
            if (filterKey) {
                filtered = filtered.filter(function (row) {
                    return Object.keys(row).some(function (key) {
                        return String(row[key]).toLowerCase().indexOf(filterKey) > -1
                    })
                })
            }


            if (this.filtry.opis_niezgodnosci) {
                filtered = filtered.filter((el) => el.opis_niezgodnosci.toLowerCase().indexOf(self.filtry.opis_niezgodnosci.toLowerCase()) > -1)
            }

            if (this.filtry.id) {
                filtered = filtered.filter((el) => el.id == self.filtry.id)
            }

            if (this.filtry.typ_niezgodnosci) {
                filtered = filtered.filter((el) => el.typ_niezgodnosci == self.filtry.typ_niezgodnosci)
            }

            if (this.filtry.komentarz_serwisu) {
                filtered = filtered.filter((el) => el.komentarz_serwisu.toLowerCase().indexOf(self.filtry.komentarz_serwisu) > -1)
            }

            if (this.filtry.SPW) {
                if (this.filtry.SPW === '-') {
                    filtered = filtered.filter((el) => el.SPW == '' || el.editable)
                } else {
                    filtered = filtered.filter((el) => el.SPW?.toLowerCase().indexOf(self.filtry.SPW) > -1)
                }
            }

            if (this.filtry.klasyfikacja) {
                filtered = filtered.filter((el) => el.klasyfikacja?.toLowerCase().indexOf(self.filtry.klasyfikacja.toLowerCase()) > -1)
            }

            if (this.filtry.lokal) {
                filtered = filtered.filter((el) => el.lokal?.toLowerCase().indexOf(self.filtry.lokal) > -1)
            }

            if (this.filtry.adres_admin) {
                filtered = filtered.filter((el) => el.adres_admin?.toLowerCase().indexOf(self.filtry.adres_admin.toLowerCase()) > -1)
            }

            if (this.filtry.nr_admin) {
                filtered = filtered.filter((el) => el.nr_admin?.toLowerCase().indexOf(self.filtry.nr_admin.toLowerCase()) > -1)
            }

            if (this.filtry.kontakt_klient) {
                filtered = filtered.filter((el) => el.kontakt_klient?.toLowerCase().indexOf(self.filtry.kontakt_klient.toLowerCase()) > -1)
            }

            if (this.filtry.nr_oferty) {
                filtered = filtered.filter((el) => el.nr_oferty?.toLowerCase().indexOf(self.filtry.nr_oferty.toLowerCase()) > -1)
            }

            if (this.filtry.uwagi_inwestora) {
                filtered = filtered.filter((el) => el.uwagi_inwestora?.toLowerCase().indexOf(self.filtry.uwagi_inwestora.toLowerCase()) > -1)
            }


            if (this.filtry.status) {
                filtered = filtered.filter((el) => el.status?.toLowerCase().indexOf(self.filtry.status.toLowerCase()) > -1)
            }

            if (this.filtry.date_start && this.filtry.date_end) {
                const startDate = new Date(this.filtry.date_start);
                const endDate = new Date(this.filtry.date_end);
                endDate.setHours(23, 59, 59, 999);

                filtered = filtered.filter((el) => {
                    const createdAtDate = new Date(el.created_at);
                    if (createdAtDate <= endDate) {
                        console.log(createdAtDate, endDate);
                    }
                    return createdAtDate >= startDate && createdAtDate <= endDate;
                });
            }


            if (this.user.group != 'admin') {
                filtered = filtered.filter((el) => !el.hidden);
            }

            if (this.sortkey) {
                filtered = filtered.sort(function (a, b) {
                    console.log(self.sortkey);

                    var a = a[self.sortkey];
                    var b = b[self.sortkey];

                    // Compare the 2 dates
                    return (a === b ? 0 : a > b ? 1 : -1) * sortorder;
                })
            }








            return filtered
        },
    }
}).mount('body')