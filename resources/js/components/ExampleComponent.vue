<template>
    <div>
        <div class="col-md-5 col-sm-12 upload-card__custom">
            <el-card :body-style="{ padding: '0px' }">
                <div class="col ">
                    <div class="card-body">
                        <div class="col  align-items-center full-width mt-2"  >
                            <div class="col">
                                <el-input
                                    type="textarea"
                                    :rows="3"
                                    placeholder="Share a youtube link or any text..."
                                    v-model="formInline.title">
                                </el-input>
                            </div>
                            <br>
                           <div class="col ">
                               <el-upload
                                   class="upload-demo"
                                   drag
                                   ref="upload"
                                   action="/create_global"
                                   :on-change="handleUploadChange"
                                   :on-remove="handleRemove"
                                   :on-success="handleSuccess"
                                   :headers="{ 'X-CSRF-TOKEN': csrf }"
                                   :file-list="fileList"
                                   :data="formInline"
                                   :on-progress="handleProgress"
                                   :auto-upload="false"
                                   multiple>
                                   <i class="el-icon-upload"></i>
                                   <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                                   <div class="el-upload__tip text-center" slot="tip">
                                       Share file with password (max:1GB)
                                   </div>
                               </el-upload>
                           </div>
                            <div class="col">
                                <input class="form-control" type="password" v-model="formInline.password"  :disabled="!actualFiles.length>0" placeholder="Password..." />
                            </div>
                        </div>
                    </div>
                    <div >
                        <el-button class="mb-3 col"  type="success" :disabled="!formInline.title"  style="width: 100%" @click="onSubmit">Share Here
                            <i class="el-icon-bottom"></i>
                        </el-button>
                    </div>
                </div>
            </el-card>

        </div>
        <div class="d-none d-md-block">
            <div class="row">
                <div class="col-md-3 col-sm-12 message-div">
                    <chat :propsData="texts"></chat>
                </div>
                <div class="col-md-6 col-sm-12 p-0 message-div">
                    <files :propsData="files"></files>
                </div>
                <div class="col-md-3 col-sm-12">
                    <links :propsData="links"></links>
                </div>
            </div>
        </div>
        <div class="d-sm-block d-md-none">

                <div class=" col p-0 message-div">
                    <files :propsData="files"></files>
                </div>
            <div class="col-md-3 col-sm-12">
                <links :propsData="links"></links>
            </div>
            <div class="col message-div">
                <chat :propsData="texts"></chat>
            </div>
            </div>
    </div>
</template>

<script>
    const appUrl = 'http://127.0.0.1:8000';
    import axios from 'axios';
    import Chat from './components/Chat';
    import Links from './components/Links';
    import Files from './components/Files'
    export default {
        props:['csrf'],
        components:{
          Chat,Files,Links
        },
        data() {
            return {
                formInline: {
                    title: null,
                    password:''
                },
                percentage:0,
                fileList:[],
                url: '',
                id: '',
                asd: null,
                files: [],
                texts: [],
                links:[],
                actualFiles:[],
            }
        },
        mounted() {
            this.getDatas()
        },
        methods: {
           onSubmit(){
               if(this.actualFiles.length <=0){
                   axios.post(
                       appUrl+'/create_global',
                       {title:this.formInline.title},
                       {headers:{'X-CSRF-TOKEN': this.csrf },
                           onUploadProgress: this.handleProgress})
                       .then(resp=>{
                           this.getDatas();
                           this.$refs.upload.clearFiles()

                       })
               }else{
                   if(this.formInline.password){
                       this.$refs.upload.submit();
                   }else{
                       this.$notify({
                           title: 'File Uploaded Without Password',
                           type:'warning',
                           message: 'Sharing your files without password is blocked for security reasons. Please set a password.'
                       });
                   }
               }
           },
            handleProgress(progressEvent){
                    let pers= Math.floor((progressEvent.loaded * 100) / progressEvent.total);
                    this.percentage =pers
                    console.log('asd',pers)
            },
            handleUploadChange(file) {
                this.actualFiles.push(file);
            },
            handleSuccess(file, fileList) {
                this.getDatas()
                this.$refs.upload.clearFiles()
                this.$notify({
                    title: 'File Uploaded',
                    type:'success',
                    message: 'Check the files below'
                });
            },
            handleRemove(file, fileList) {
                this.actualFiles.pop();
            },
            fileAdded(files) {
                console.log(files)
            },
            getDatas() {
                axios.get(appUrl + '/api/')
                    .then(resp=>resp.data)
                    .then((response) => {
                        this.texts = response.texts;
                        this.links = response.texts;
                        this.files = response.files;
                        this.formInline.title = null
                        this.formInline.password = null
                    });
            },


            ajax_password: function (event, id, password, key) {
                axios.post(appUrl + '/ajaxdeneme', {id: id, password: password})
                    .then((response) => {
                        if (response.data.status === 200) {
                            this.files[key].remove = true
                            this.files[key].title = this.data[key].title + " ";
                            let path = response.data.download_link;
                            window.open(path, '_blank');
                        }
                        if (response.data.status === 400) {
                            alert('nt')
                        }
                    });
            },

            maxTitleLenght: function (text, key) {
                if (text.length > 210) {
                    let last_version = text.substring(0, 198) + "...";

                    return last_version;
                }
                return text;
            },
        },

    }
</script>

<style >
    .text {
        font-size: 14px;
    }

    .item {
        padding: 0px 0;
    }

    .box-card {
        min-height:300px;
        width: 480px;
        padding:10px!important
    }
    .el-card__body{
        padding:0px!important
    }
    .upload-card__custom{
        min-width:300px
    }
    .el-upload .el-upload--text{
        max-width:100px!important;
    }
    .el-upload-dragger{
        left:-10px;
    }
    .message-content{
        position:absolute;
        left:2px;
        bottom:5px;
    }
    .message-time{
        color: #28A745;
        position:absolute;
        right:2px;
        bottom:2px;
    }
    @media only screen and (max-width: 600px) {
        .el-upload-dragger{
            max-width:84%!important;
            left:-30px;
        }
    }
    .file-dis{
        float:left;
        text-align:left;
        position:relative;
        left:0
    }
    .message-div{
        height:900px;
        overflow: scroll!important;
    }
    .message-div::-webkit-scrollbar {
        display: none;  /* Safari and Chrome */
    }

</style>
