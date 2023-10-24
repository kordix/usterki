Vue.createApp({
    data() {
        return {
            projekty:[],
            activeproject: null,
            messages: [],
            searchbufor: '',
            sororder: null,
            formbool:false,
            form:{
                nazwa_projektu:'',
                adres:''
            }
        }
    },
    mounted() {
        let self = this;
        axios.get('api/projects.php').then((res) => self.projekty = res.data)
    },
    methods:{
        test(){
            console.log('test');
        },
        save(){
            axios.post('api/projectadd.php', this.form).then((res) => location.reload());
        },
        deleteproject(){
            axios.get('api/projectdelete.php?id=' + this.activeproject).then((res) => location.reload())
        },
        preview(){
            
        }

    },
    computed: {
    
        filtered: function () {
            let sortorder = this.sortorder;
            let self = this;
            var filterKey = this.searchbufor && this.searchbufor.toLowerCase()
            var order = 1;
            var filtered = this.bufor;
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

            return filtered
        },
    }
}).mount('#app')