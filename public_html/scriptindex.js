Vue.createApp({
    data() {
        return {
            useradd:'',
            user:{},
            projekty:[],
            activeproject: null,
            messages: [],
            searchbufor: '',
            sororder: null,
            formbool:false,
            form:{
                nazwa_projektu:'',
                adres:''
            },
            rights:[],
            users:[],
            crudmode:'add',
            headers:[],
            headeradd:'',
            activeright:{}

        }
    },
    async mounted() {
        let self = this;
        axios.get('/api/projects.php').then((res) => self.projekty = res.data);
        await axios.get('/api/getuser.php').then((res) => self.user = res.data);
        await axios.get('/api/rights.php').then((res) => self.rights = res.data);
        await axios.get('/api/users.php').then((res) => self.users = res.data);
        await axios.get('/api/headersall.php').then((res) => self.headers = res.data);




       

    },
    methods:{
        test(){
            console.log('test');
        },
        save(){
            axios.post('/api/projectadd.php', this.form).then((res) => location.reload());
        },
        update(){
            axios.post('/api/projectupdate.php', { dane: this.form, id: this.form.id }).then((res) => location.reload());;
        },
        deleteproject(){
            axios.get('/api/projectdelete.php?id=' + this.activeproject).then((res) => location.reload())
        },
        preview(){
            
        },
        addright(){
            axios.get('/api/rightadd.php?userid='+this.useradd+'&projectid='+this.activeproject).then((res)=>{
                location.reload()
            })
        },
        edit(elem){
            this.activeright = {};
            this.formbool = true;
            this.crudmode = 'edit';

            this.form = elem;
        },
        async addheader(){
            let self = this;
            await axios.post('/api/headeradd.php', { header: this.headeradd, project_id:this.activeproject});
            this.headeradd = '';

            await axios.get('/api/headersall.php').then((res) => self.headers = res.data);

        },
        async setTemplateHeaders(){
            let self = this;
            await axios.post('/api/headerstemplate.php', { project_id: this.activeproject });
            await axios.get('/api/headersall.php').then((res) => self.headers = res.data);

        },
        async removeright(id){
            await axios.get('/api/rightdelete.php?id='+id).then((res)=>location.reload())
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