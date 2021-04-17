<template>
        <div class="container">

            <div v-for="(item,key) in textComputed" :key="key" >
                <div class="en-dis col">
                    <div class="message-div col">
                        <a v-if="isUrl(item.title)" target="_blank" :href="item.title">{{item.title}}</a>
                       <span v-else>{{item.title}}</span>
                    </div>
                    <div class="message-footer col">
                        <div class="right">
                            <small class="text-white">{{moment.duration(moment().diff(item.created_at)).humanize()}} ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
    export default {
        name: "Chat",
        props:['propsData'],
        data(){
            return{
            }
        },
        computed:{
            textComputed(){
              return this.propsData
            }
        },
        methods:{
            isUrl: function (text) {
                var res = text.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
                return (res !== null)
            },
        }
    }
</script>

<style scoped>
    .en-dis{
        background-color:#636b6f ;
        border-radius: 20px;
        height: 80px;
        width: auto;
        margin: 20px 0;
        padding-top:15px;
    }
    .message-div{
        overflow: scroll!important;
        font-size: 15px;
        height: 40px;
        color: white;
        text-align: left;
    }
    .message-div::-webkit-scrollbar {
        display: none;  /* Safari and Chrome */
    }
    .message-footer{
        font-size: 13px;
        position: relative;
        text-align:right;
        bottom: 0;
    }
    .right{
        height:20px!important;
    }
    @media only screen and (max-width: 600px) {
        .en-dis{
            width: 100%;
            height: 60px;
        }
        .message-div{
            height:300px;
            width: 40%;
            overflow: scroll!important;
        }
    }
</style>
