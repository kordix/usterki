let map;

Vue.createApp({
    data() {
        return {
            user: {},
            sortkey: null,
            filtry: {
                id: null,
                opis_niezgodnosci: null
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
            sortorder: null,
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
            crudmode: 'add'
        }
    },
    async mounted() {
        const self = this;
        const id = document.querySelector('#projectid').innerHTML;
        await axios.get('api/project.php?id=' + id).then((res) => self.project = res.data[0]);
        await axios.get('api/usterki.php?id=' + id).then((res) => self.usterki = res.data);
        await axios.get('api/getuser.php').then((res) => self.user = res.data);


    },
    methods: {
        setPlany() {
            let self = this;
            this.activesection = 'plany';
            setTimeout(() => {
                self.generateMap();
            }, 150)
        },
        handleChange(elem, kolumna) {
            if (this.user.group == 'klient') {
                if (['status', 'komentarz_serwisu', 'SPW', 'termin_zgloszenia', 'klasyfikacja'].indexOf(kolumna) > -1) {
                    return
                }
            }
            elem.editable = true;
            console.log(elem.id + kolumna);
            setTimeout(() => {
                if (document.getElementById(elem.id + kolumna)) {
                    document.getElementById(elem.id + kolumna).focus();
                }
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
        async updateAuto(elem, column) {
            let self = this;
            let formlocal = {}
            formlocal[column] = elem[column];

            await fetch('api/usterkaupdate.php', { method: 'POST', body: JSON.stringify({ dane: formlocal, id: elem.id }) }).then((res) => self.messages.push('zapisano zmiany'));
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
        async save() {
            let self = this;
            const id = document.querySelector('#projectid').innerHTML;
            this.form.project_id = id;
            await axios.post('api/usterkaadd.php', this.form).then((res) => console.log('fads'));
            location.reload();
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
        }
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
            if (this.sortkey) {

                filtered = filtered.sort(function (a, b) {
                    console.log(self.sortkey);

                    var a = a[self.sortkey];
                    var b = b[self.sortkey];

                    // Compare the 2 dates
                    return (a === b ? 0 : a > b ? 1 : -1) * sortorder;
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
                    filtered = filtered.filter((el) => el.SPW.toLowerCase().indexOf(self.filtry.SPW) > -1)
                }
            }

            if (this.filtry.klasyfikacja) {
                filtered = filtered.filter((el) => el.klasyfikacja.toLowerCase().indexOf(self.filtry.klasyfikacja) > -1)
            }

            if (this.filtry.lokal) {
                filtered = filtered.filter((el) => el.lokal.toLowerCase().indexOf(self.filtry.lokal) > -1)
            }

            if (this.filtry.status) {
                filtered = filtered.filter((el) => el.status.toLowerCase().indexOf(self.filtry.status.toLowerCase()) > -1)
            }








            return filtered
        },
    }
}).mount('body')