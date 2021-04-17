<template>
    <div class="  " style="overflow-x: scroll;overflow-y:hidden;position: relative">
        <div class="row"  >
            <div v-if="index<4" class="col"   v-for="(item,index) in data" style="overflow-x: scroll;display:table" >
                <div class="card card-small card-post mb-2"  name="kart"  style="width: 225px" >
                    <div class="card-header   " style="background-color: #152632;color:white;font-size: 15px;text-align: center;">
                            {{item.question_title}}
                    </div>
                    <div class="card-body col  " >
                        <div v-if="item.option_one !==undefined" class="row border mb-2 border-success rounded-25 busacmabirdeneme" @click="clickAnswer(item.id,item.correct_answer,item.option_one)" style="border-radius: 7px;"    >
                            {{item.option_one}}
                        </div>
                        <div v-if="item.option_two !==undefined"  class="row border mb-2 border-success rounded-25 busacmabirdeneme " @click="clickAnswer(item.id,item.correct_answer,item.option_two)"  style="border-radius: 7px;" >
                            {{item.option_two}}
                        </div>
                        <div v-if="item.option_three !==undefined"  class="row border mb-2 border-success rounded-25 busacmabirdeneme" @click="clickAnswer(item.id,item.correct_answer,item.option_three)" style="border-radius: 7px; text-align: center" >
                            {{item.option_three}}
                        </div>
                        <div v-if="item.option_four !==undefined"  class="row border mb-2 border-success rounded-25 busacmabirdeneme"   @click="clickAnswer(item.id,item.correct_answer,item.option_four)"  style="border-radius: 7px;" >
                            {{item.option_four}}
                        </div>
                    </div>
                </div>
            </div>
           <!-- <div >
                <button  @click="moreSurvey" class="btn wrapper mr-3 " style="height:97%;align-text:center">
                    >>
                </button>
            </div>-->
        </div>
    </div>
</template>

<script>

    export default {
        name: "quiz",
        data() {
            return {
                backgroundColor: "white",
                textColor: "black",
                overBackGroundColor:"green",
                overTextColor:"white",
                data:[],
                color:'white'
            }
        },
        methods:{
            clickAnswer: function(question_id,correct_answer,user_answer){
                console.log(correct_answer==user_answer);
            },
            moreSurvey:function () {
                alert("more")
            }
        },
        mounted () {
            axios
                .get('http://127.0.0.1:8000/api/survey')
                .then( (response)=>{
                    this.data = response.data.data;
                    let i;
                    for(i=0;i<5;i++){

                    }
                });
            console.log(this.data);
        }
    }
</script>

<style scoped>
</style>
