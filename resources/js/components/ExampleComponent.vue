<template>
    <div class="row">
        <div class="col-md-6 col-sm-12 upload-card__custom">
            <el-card :body-style="{ padding: '0px' }">
                <div class="col ">
                    <div class="card-body" v-if="!getLink">
                        <div class="col  align-items-center full-width mt-2">
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
                                    name="file[]"
                                    class="upload-demo"
                                    drag
                                    ref="upload"
                                    action="/api"
                                    :on-change="handleUploadChange"
                                    :on-remove="handleRemove"
                                    :on-success="handleSuccess"
                                    :headers="{ 'X-CSRF-TOKEN': csrf }"
                                    :file-list="fileList"
                                    :data="formInline"
                                    :on-progress="handleProgress"
                                    :auto-upload="false"
                                    :on-exceed="handleExceed"
                                    :limit="3"
                                    multiple>
                                    <i class="el-icon-upload"></i>
                                    <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                                    <div class="el-upload__tip text-center" slot="tip">
                                        Share file with password (max:1GB)
                                    </div>
                                </el-upload>
                            </div>
                            <div class="col">
                                <input class="form-control" type="password" v-model="formInline.password"
                                       :disabled="!actualFiles.length>0" placeholder="Password..."/>
                            </div>
                        </div>
                    </div>

                    <div class="card-body" v-else>
                        <div class="col  align-items-center full-width mt-2">
                            <div class="col ">
                                <el-upload
                                    name="file[]"
                                    class="upload-demo"
                                    drag
                                    ref="upload"
                                    action="/api"
                                    :on-change="handleUploadChange"
                                    :on-remove="handleRemove"
                                    :on-success="handleSuccess"
                                    :headers="{ 'X-CSRF-TOKEN': csrf }"
                                    :file-list="fileList"
                                    :data="getLinkData"
                                    :on-progress="handleProgress"
                                    :auto-upload="false"
                                    :on-exceed="handleExceed"
                                    :limit="3"
                                    multiple>
                                    <i class="el-icon-upload"></i>
                                    <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                                    <div class="el-upload__tip text-center" slot="tip">
                                        Share file with password (max:1GB)
                                    </div>
                                </el-upload>
                            </div>
                            <div class="col">
                                <input class="form-control" type="password" v-model="getLinkData.password"
                                       :disabled="!actualFiles.length>0" placeholder="Optional password..."/>
                            </div>
                        </div>
                    </div>


                    <div v-if="!getLink">
                        <el-button class="mb-3" type="success" :disabled="!formInline.title" style="width: 100%"
                                   @click="onSubmit">Share Here
                            <i class="el-icon-bottom"></i>
                        </el-button>
                        <a @click.prevent="getLinkClickedMethod">
                            Get link
                            <i class="el-icon-link"></i>
                        </a>
                    </div>

                    <div v-else>
                        <el-button class="mb-3" type="success" :disabled="!formInline.title" style="width: 100%"
                                   @click="onSubmit">Get Link
                            <i class="el-icon-link"></i>
                        </el-button>
                        <a @click.prevent="shareHereClickedMethod">
                            Share Here
                            <i class="el-icon-bottom"></i>
                        </a>
                    </div>
                </div>
            </el-card>
        </div>
        <div class="col-md-6 col-sm-12 upload-card__custom">
            <Slayt style="width: 2em; height: 2em; background-color: transparent; z-index: 80;
            bottom: 25px; position: absolute; border-width: 0 0.25em 0.25em 0; border-style: solid; border-color: antiquewhite; animation: scrolldown 1.2s ease-in-out infinite 0.15s;"></Slayt>
            <Slayt style="width: 2em; height: 2em; background-color: transparent; z-index: 80;
            bottom: 40px; position: absolute; border-width: 0 0.25em 0.25em 0; border-style: solid; border-color: antiquewhite; animation: scrolldown 1.2s ease-in-out infinite;"></Slayt>

        </div>


        <div class="col-12 d-none d-md-block mt-3  radius shadow en_dis_tabs" style="background-color:#155724">
            <b-tabs content-class="mt-3 py-2" class="radius shadow"  justified>
                <b-tab title="Notes" >
                <div class="chat_en_dis">
                    <chat :propsData="texts" ></chat>
                </div>
                </b-tab>
                <b-tab title="Files" >
                    <files :propsData="files"></files>
                </b-tab>
                <b-tab title="Player">
                    <links :propsData="links"></links>
                </b-tab>
            </b-tabs>
        </div>
        <div class="d-sm-block d-md-none bg-white">
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
    //TODO k*bootstrap tabı ile, dosyalar notlar ve müzkiler ayrı olacak. Yeni crm'de operasyon sayfasında kullanmıştık.
    //TODO k* mobilde slider olarak gözükecek.


    //TODO k* Chat kısmında desktopta ise buzdolabına yapıştırılan notlar gibi, mobilde ise mesajlaşma gibi .

    //TODO k* lokasyon bilgisini her bir karta bastırmaya calıs

    //TODO k* Odalara isim verirken emoji kullanılabilecek, js emojileri de olur

    const appUrl = process.env.MIX_API_URL;
    import axios from 'axios';
    import Chat from './components/Chat';
    import Links from './components/Links';
    import Files from './components/Files'
    import {TabsPlugin} from 'bootstrap-vue'

    export default {
        props: ['csrf'],
        components: {
            Chat, Files, Links, TabsPlugin
        },
        data() {
            return {
                getLink: false,
                formInline: {
                    title: null,
                    password: ''
                },
                getLinkData: {
                    password: null,
                    get_link: true
                },
                percentage: 0,
                fileList: [],
                url: '',
                id: '',
                asd: null,
                files: [],
                texts: [],
                links: [],
                actualFiles: [],
            }
        },
        mounted() {
            this.getDatas()
        },
        methods: {
            onSubmit() {
                if (this.actualFiles.length <= 0) {
                    axios.post(
                        appUrl,
                        {title: this.formInline.title},
                        {
                            headers: {'X-CSRF-TOKEN': this.csrf},
                            onUploadProgress: this.handleProgress
                        })
                        .then(resp => {
                            this.getDatas();
                            this.$refs.upload.clearFiles()
                        })
                        .catch(error => {
                            this.$notify({
                                title: 'Failed',
                                type: 'error',
                                message: error.message
                            });
                        });
                } else {
                    if (this.formInline.password) {
                        this.$refs.upload.submit();
                    } else {
                        this.$notify({
                            title: 'File Uploaded Without Password',
                            type: 'warning',
                            message: 'Sharing your files without password is blocked for security reasons. Please set a password.'
                        });
                    }
                }
            },
            handleProgress(progressEvent) {
                let pers = Math.floor((progressEvent.loaded * 100) / progressEvent.total);
                this.percentage = pers
            },
            handleExceed(files, fileList) {
                this.$notify({
                    title: 'Warning',
                    type: 'warning',
                    message: `The limit is 3, you selected ${files.length} files this time, add up to ${files.length + fileList.length} totally`
                });
            },
            handleUploadChange(file) {
                this.actualFiles.push(file);
            },
            handleSuccess(file, fileList) {
                this.getDatas()
                this.$refs.upload.clearFiles()
                this.$notify({
                    title: 'File Uploaded',
                    type: 'success',
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
                axios.get(appUrl,{params:{$sortType:'desc'}})
                    .then(resp => resp.data)
                    .then((response) => {
                        this.texts = response.texts;
                        this.links = response.texts;
                        this.files = response.files;
                        this.formInline.title = null
                        this.formInline.password = null
                    })
                    .catch(error => {
                        this.$notify({
                            title: 'Failed',
                            type: 'error',
                            message: error.message ?? 'Datas couldn\'t load'
                        });
                    });
            },

            getLinkClickedMethod() {
                this.getLink = true
            },
            shareHereClickedMethod() {
                this.getLink = false
            },
            ajax_password: function (event, id, password, key) {
                axios.post(`${appUrl}/${id}/password`, {id: id, password: password})
                    .then((response) => {
                        this.files[key].remove = true;
                        this.files[key].title = this.data[key].title + " ";
                        let path = response.data.download_link;
                        window.open(path, '_blank');
                    })
                    .catch(error => {
                        this.$notify({
                            title: 'Failed',
                            type: 'error',
                            message: error.message
                        });
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

<style>
    .nav-link{
        color:white
    }
    .nav-tabs .nav-link.active{
        color:black
    }
    .text {
        font-size: 14px;
    }

    .item {
        padding: 0px 0;
    }

    .box-card {
        min-height: 300px;
        width: 480px;
        padding: 10px !important
    }

    .el-card__body {
        padding: 0px !important
    }

    .upload-card__custom {
        min-width: 300px;
    }

    .el-upload .el-upload--text {
        max-width: 100px !important;
    }

    .el-upload-dragger {
        left: -10px;
    }

    .message-content {
        position: absolute;
        left: 2px;
        bottom: 5px;
    }

    .message-time {
        color: #28A745;
        position: absolute;
        right: 2px;
        bottom: 2px;
    }

    @media only screen and (max-width: 600px) {
        .el-upload-dragger {
            max-width: 84% !important;
            left: -30px;
        }
    }

    .file-dis {
        float: left;
        text-align: left;
        position: relative;
        left: 0
    }

    .message-div {
        height: 900px;
        overflow: hidden !important;
    }
    ::-webkit-scrollbar {
        display: none;
    }
    .message-div::-webkit-scrollbar {
        display: none; /* Safari and Chrome */
    }
    .en_dis_tabs{
        min-height: 600px;
        margin-bottom:50px;
        margin-right:20px!important

    }
    .chat_en_dis{
        border-bottom-color: white;
        border-left-color: white;
        border-right-color: white;
    }

</style>
