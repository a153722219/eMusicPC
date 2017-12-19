<template>
  <div class="card"  style="margin-top: 20px" >
    <div v-if="is_login" class="card-header" align="center">
      <img :src="avatar" class="avatar img-circle img-responsive">
      <p><strong>{{username}}</strong></p>
      <p class="card-title">9小时前听过</p>
      <p>
        <button class="btn btn-success btn-sm" @click="upData" :disabled="is_working || is_loading">
          <i class="fa" :class="{ 'fa-spinner fa-spin' :is_working ,'fa-cloud-upload':!is_working}"></i>{{is_working?'上传中':'上传'}}
        </button>
        <button class="btn btn-primary btn-sm" @click="downData" :disabled="is_loading || is_working">
          <i class="fa" :class="{ 'fa-spinner fa-spin' :is_loading ,' fa-cloud-download':!is_loading}"></i>{{is_loading?'下载中':'下载'}}
        </button>
        <button class="btn btn-danger btn-sm" @click="logout"><i class="fa fa-sign-out"></i>注销</button>
      </p>
      <p>云端共有{{dCount}}首歌曲</p>

    </div>
    <div v-else class="card-header" align="center">
      <form class="form" @submit.prevent>
        <div class="form-group">
          <input class="form-control"  v-model="username"  name="username" placeholder="必须大于四个字符"
                 required />
          <p class="text-muted"><small v-html="msg1" :class="{'text-danger':e1}"></small></p>
        </div>
        <div class="form-group">
          <input class="form-control"  v-model="password" name= "password" type="password" placeholder="密码"
                 required pattern="\w{6,}"/>
          <p class="text-muted"><small v-html="msg2" :class="{'text-danger':e2}"></small></p>
        </div>
        <div class="form-group clearfix">
          <input type="submit"  class="btn btn-danger float-md-left"
                 value="注册"  @click="vailData"/>
          <input type="submit" @click="login" class="btn btn-success float-md-right"
                 value="登录" />
        </div>
      </form>
      <a href="javascript:" class="card-title nav-link" @click="getSubscription()">订阅列表</a>
    </div>

    <div class="card-block pd80">
      <p v-if="subList.length==0">
        您还没有收藏任何歌曲哦!
      </p>
      <p v-for="(item,index) in subList"  class="remove" style="font-size: 14px;">
        <!--<a :href="item.encGzhUrl" target="_blank">-->
        <a @click="getArt(index)"  style="cursor:pointer">
          <img :src="item.img" class="mpavatar img-circle img-responsive">
          {{item.audio_name}} <span class="tag tag-danger tag-pill">{{item.pnum==128?'HQ':'SQ'}}</span>
        </a>
        <a @click="removeSub(item.hash)" style="cursor: pointer">
          <i class="fa fa-remove float-xs-right"></i>
        </a>
      </p>

      <!--<p>-->
      <!--<a href="#">-->
      <!--<img src="../assets/30.jpg" class="mpavatar img-circle img-responsive">-->
      <!--科技每日推送 <span class="tag tag-danger tag-pill">3</span>-->
      <!--</a>-->
      <!--</p>-->
    </div>

  </div>


</template>

<script>

  export default {
    data: function () {
      return {
        is_working:false,
        is_loading:false,
        is_login:false,
        username:"",
        password:"",
        msg1:'用户名为中文/字母/下划线',
        msg2:'密码至少6位，字母数字下划线',
        e1:false,
        e2:false,
        dCount:0,
        subList:[],
        avatar:"/static/avatar.jpg"
      }
    },
    methods: {
      removeSub(hash){
        let list = localStorage.getItem('subList');
        if(list){
          list = JSON.parse(list);
          for(let i=0;i<list.length;i++){
            if(list[i].hash===hash){
              list.splice(i,1);
              break;
            }
          }
          localStorage.subList=JSON.stringify(list);
          this.getSub();
          Comm.$emit('subChange',hash);
        }

      },
      vailData(){
        let PassReg =/^[0-9a-zA-Z_]{1,}$/;
        if(this.username.length>=4){
          this.$http.post('http://127.0.0.1/RSS/vail.php',
            "username="+this.username,
            //options
            {
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              withCredentials:true
            }).then(data=>{
            if(data.body.code==1){
              this.e1=false;
              this.msg1="验证通过";
              if(PassReg.test(this.password)){
                this.e2=false;
                this.msg2="验证通过";
                this.$http.post('http://127.0.0.1/RSS/reg.php',
                  "username="+this.username+"&password="+this.password,
                  //options
                  {
                    headers: {
                      'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    withCredentials:true
                  }).then(data=>{
                  if(data.body.code==1){
                    alert('注册成功');
                    this.msg1='用户名为中文/字母/下划线';
                    this.msg2='密码至少6位，字母数字下划线';
                    this.username="";
                    this.password="";
                  }else{
                    alert('注册失败');
                  }
                });
              }else{
                this.e2=true;
                this.msg2="密码格式不正确";
              }
            }else{
              this.e1=true;
              this.msg1="用户名已经存在";
            }
          });
        }else{
          this.e1=true;
          this.msg1="用户名必须大于4个字符";
        }

      },
      login(){
        this.$http.post('http://127.0.0.1/RSS/login.php',
          "username="+this.username+"&password="+this.password,
          //options
          {
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            withCredentials:true
          }).then(data=>{
          if(data.body.code==-1){
            alert("登陆失败");
          }else{
            alert("登陆成功");
            this.getCount();
            this.is_login=true;
            this.password="";
          }
        });
      },
      logout(){
        this.$http.get('http://127.0.0.1/RSS/logout.php',{withCredentials:true}).then(data=>{
          alert('注销成功');
          this.is_login=false;
          this.username="";
          this.dCount=0;
        });
      },
      getSub(){
        let list = localStorage.getItem('subList');
        if(list){
          list = JSON.parse(list);
          this.subList=list;
        }
      },
      getArt(index){
        let list = localStorage.getItem('subList');
        if(list){
          list = JSON.parse(list);
        }
        Comm.$emit('playbyhash',{index:index,list:list});
      },

      upData(){
        this.is_working=true;
        let jStr = localStorage.subList;
        this.$http.post('http://127.0.0.1/RSS/upData.php',"jsonStr="+jStr,
          //options
          {
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            withCredentials:true
          }).then(data=>{
          setTimeout( ()=>{
            this.is_working=false;
          },1500)
        });

        //false
      },

      getCount(){
        this.$http.get('http://127.0.0.1/RSS/getCount.php',{withCredentials:true}).then(data=>{
          this.dCount = data.body.count;
          console.log(data.body);
        });
      },

      downData(){
        this.is_loading=true;
        this.$http.get('http://127.0.0.1/RSS/downData.php',{withCredentials:true}).then(data=>{
          localStorage.subList=JSON.stringify(data.body);
          setTimeout( ()=>{
            this.is_loading=false;
            this.getSub();
          },1500);
        });

      }

    },
    created:function () {

    },
    mounted:function(){
      this.$http.get('http://127.0.0.1/RSS/isLogin.php',{withCredentials:true}).then(data=>{
        if(data.body.code==1){
          this.is_login=true;
          this.username=data.body.username;
          this.avatar=data.body.avatar;
          this.getCount();
        }
      });
      this.getSub();

      Comm.$on('getSub',this.getSub);
    }

  }
</script>


<style>
  a:hover{
    text-decoration: none;
  }

  .avatar {
    height: 75px;
    margin: 0 auto;
    margin-top: 10px;
    margin-bottom: 10px;
  }
  .mpavatar {
    height: 30px;
    margin: 0 auto;
    margin-top: 2px;
    margin-bottom: 2px;
  }
  .img-circle {
    border-radius: 50%;
  }
  .remove .fa-remove{
    display: none;
  }
  .remove:hover .fa-remove{
    display: block;
  }
  .pd80{
    padding-bottom: 80px;
  }
</style>
