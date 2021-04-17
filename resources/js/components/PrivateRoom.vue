<template>
    <div class="d-flex flex-wrap text-center">
        <div class="p-4 " v-for="(item,key) in data" :key="key" style="width:430px;height:350px;margin-left: auto;margin-right: auto;">
            <div class="card card-small card-post mb-4" name="kart" style=" width: 100%;height:100%;border-radius: 20px">
                <div class="card-header " style="background-color: #28A745;border-radius:20px 20px 0px 0px ">

                </div>
                <div class="card-body row text-center " style="height:10px;">
                    <div v-if="item.title.includes('www.youtube.com') || item.title.includes('m.youtube.com') || item.title.includes('youtu.be')"
                         class="iframe-container text-center align-self-center "  >
                        <iframe width="1024" height="768" :src="youtube_link(item.title)" frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                    <div v-else-if="item.title.includes('open.spotify.com')" class="iframe-container text-center align-self-center " >
                        <iframe :src="spotify(item.title)" frameborder="0"  allowtransparency="true" allow="encrypted-media">
                        </iframe>
                    </div>
                    <h5 v-else class=" col-12 text-center" >{{item.title}}</h5>
                    <div v-if="item.password !== null" class="text-center" style="margin-left: auto;margin-right: auto;">
                        <input style="width: auto;margin-left: auto;margin-right: auto;" v-model="item.new_password" class=" align-self-center form-control mx-sm-3" type="password"
                               placeholder="password..." name="file_password_confirm"> <br><br>
                        <span  v-on:click="ajax_password(this,item.id,item.new_password)"
                               class="btn btn-success">Download</span>
                        <br>
                    </div>
                    <div v-else-if="item.file_name !== null" class="text-center full-width">
                        <a :href="'/storage/private_room_files/'+item.file_name" target="_blank" class="card-text    text-center"
                           style="text-align: center;">
                            Download File
                        </a>
                    </div>
                </div>

                <div class="card-footer border-top"   style="background-color: #28A745;border-radius:0px 0px 20px 20px">
                    <div style="text-align: right">
                        <small class="text-white">{{moment().diff(item.created_at,"minutes") }} minutes ago</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    const appUrl = process.env.MIX_API_URL;
    export default {
        props:['number'],
        data() {
            return {
                id:'',
                password: '',
                data: []
            }
        },
        mounted() {
            axios.get(appUrl+this.number)
            .then((response)=>{
                this.data = response.data;
                console.log("ahoj");
            });
            window.Echo.channel(`room-channel.`+this.number)
                .listen('.RoomEvent', (data) => {
                    this.data = data.data;
                    console.log("hayır ben");
                });
            for (var i = 0; i<this.data.length; i++){
                this.data[i].push({
                    "new_password":""
                });
            }
        },
        methods: {
            ajax_password: function (event, id, password) {
                axios.post(appUrl,{id:id,password: password})
                    .then((response) => {
                        if(response.status===200){
                            let path = response.data.download_link;
                            window.open(path,'_blank');
                        }
                        this.password="";
                    });

            },
            youtube_link:function(url){
                if (url.includes('youtu.be')){
                    return  url.replace('youtu.be/','www.youtube.com/embed/');
                }
                else if (url.includes('list')){
                    return   'https://www.youtube.com/embed/'+url.substring(url.indexOf('watch?v=')+8,url.indexOf('&list'));
                }
                return 'https://www.youtube.com/embed/'+url.substring(url.indexOf('watch?v=')+8);
            },
            spotify:function (url) {
                return url.replace('spotify.com/','spotify.com/embed/');
            }
        }

    }
</script>
