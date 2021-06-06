<template>
   <div class="row" >
       <div class="col" v-for="item in propsData">
           <div v-if="item.title.includes('www.youtube.com') || item.title.includes('m.youtube.com') || item.title.includes('youtu.be') || item.title.includes('open.spotify.com') ">
               <iframe  class="en-dis" :src="youtube_link(item.title)" frameborder="0"
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
           </div>
       </div>
   </div>
</template>

<script>
    export default {
        name: "Links",
        props:['propsData'],
        methods:{
            youtube_link: function (url) {
                if (url.includes('youtu.be')) {
                    return url.replace('youtu.be/', 'www.youtube.com/embed/');
                } else if (url.includes('list')) {
                    return 'https://www.youtube.com/embed/' + url.substring(url.indexOf('watch?v=') + 8, url.indexOf('&list'));
                } else if (url.includes('open.spotify.com')) {
                    return url.replace('spotify.com/', 'spotify.com/embed/');
                }
                return 'https://www.youtube.com/embed/' + url.substring(url.indexOf('watch?v=') + 8);
            },
        }
    }
</script>

<style scoped>
    .en-dis{
        background-color:#155724 ;
        border-radius: 20px;
        height: 140px;
        width: 250px;
        margin: 20px 0;
        padding-top:15px;
    }
</style>
