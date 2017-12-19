<template>
    <div class="player">

                  <div class="controls">
                      <div class="btns hidden-sm-down">
                        <div class="btng next" @click="next" >
                          <i class="fa  fa-step-forward"></i>
                        </div>
                        <div class="btng play" @click="pauses">
                          <i class="fa fa-play" :class="{'fa-play':!isPlaying,'fa-pause':isPlaying}"></i>
                        </div>
                        <div class="btng prev" @click="prev">
                          <i class="fa  fa-step-backward"></i>
                        </div>

                      </div>

                    <div class="btns" style="min-width: 330px;width: 65%;max-width: 500px">
                      <div class="media offset-xs-1 offset-sm-2 offset-md-1">
                          <div class="media-left pbox" style="width: 90px;height: 80px;">
                            <span class="fa hidden-sm-up" @click="pauses" :class="{'fa-play':!isPlaying,'fa-pause':isPlaying}"></span>
                            <a ><img style="margin-top: 5px" height="70" :src="imgUrl" alt=""></a>
                          </div>
                        <div class="media-body">
                          <h6 style="margin-top: 5px"><span v-html="songname"></span><span class="text-muted float-xs-right">{{getTime(currentTime)}}/{{getTime(duration)}}</span></h6>
                          <progress class="progress" :value="progress" max="100" style="margin-top: 25px">
                            <div class="progress" style="margin-top: 25px">
                              <span class="progress-bar" :style="'width:'+progress+'%;'">{{progress}}%</span>
                            </div>
                          </progress>
                        </div>

                      </div>

                    </div>
                    <div class="btns hidden-sm-down">
                      <i class="fa vol" @click="vols" :class="{'fa-volume-off':!vol,'fa-volume-up':vol}"></i>
                      <a :href="playsrc" target="_blank" style="color: white"><i class="fa fa-download dload"></i></a>
                    </div>
                  </div>


    </div>
</template>

<script>
    export default {
        name : 'player',
        data: function () {
            return {
              progress:0,//进度条
              currentTime:0,
              duration:0,//总时长
              songname:"简 听",//当前音乐名称
              hash:"",//hash
              imgUrl:'/static/avatar.jpg',//图片地址
              isPlaying:false,//是否正在播放
              index:0,//播放歌曲的索引号
              list:[],//播放列表
              player:null,//播放器对象,
              playsrc:'',
              timer:'',
              vol:true
            }
        },
        methods: {
          escape2Html(str) {
            let arrEntities={'lt':'<','gt':'>','nbsp':' ','amp':'&','quot':'"'};
            return str.replace(/&(lt|gt|nbsp|amp|quot);/ig,function(all,t){return arrEntities[t];});
          },
          getZero(x){
            if(x>9){
              return x;
            }else{
              return "0"+x;
            }
          },
          getTime(time){

            var H=this.getZero(parseInt(time/3600));
            var M=this.getZero(parseInt((time%3600)/60));
            var S=this.getZero(parseInt(time%60));
            if(time>3600){
              return H+":"+M+":"+S;
            }else{
              return M+":"+S;
            }

          },


          plays(index,list){
            this.index=index;

            if(this.player){
              this.player.currentTime=0;
              this.player.pause();
            }
            this.player = new Audio();
            if(list){
              this.list=list;
            }
            let song=this.list[index];
            this.hash=song.FileHash || song.hash;


            let xmlhttp;
            if (window.XMLHttpRequest)
            {// code for IE7+, Firefox, Chrome, Opera, Safari
              xmlhttp=new XMLHttpRequest();
            }
            else
            {// code for IE6, IE5
              xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.open("GET",'http://127.0.0.1/RSS/getMusic.php?hash='+this.hash,false);

            xmlhttp.onreadystatechange=()=>{
              if (xmlhttp.readyState==4 && xmlhttp.status==200)
              {
                let mysong =JSON.parse(xmlhttp.responseText).data;
                console.log(mysong);
               // this.songname=song.FileName;
                this.songname=mysong.audio_name;
                //this.duration=song.Duration;
                this.duration=parseInt(mysong.timelength/1000);
                this.imgUrl=mysong.img;

                this.player.src=mysong.play_url;

                this.playsrc=mysong.play_url;

                //this.player.src="http://localhost/RSS/test.mp3";
                this.isPlaying=true;

                this.player.load();

                this.player.oncanplay =()=>{
                  window.dd = this.player.duration;
                  this.player.play();
                  if(!this.player.ontimeupdate){
                    this.player.addEventListener('timeupdate', ()=>{

                      this.currentTime=this.player.currentTime;
                      this.progress= parseInt(this.currentTime/this.duration*100);
                      if(this.player.ended){
                        this.currentTime=0;
                        this.progress=0;
                        this.next();
                      }
                    });
                  }
                };

              }
            };


            xmlhttp.send();

            },
          next(){
            if(this.index!=this.list.length-1){
              this.plays(this.index+1);
              this.index++;

              let hash =this.list[this.index].FileHash;
              sessionStorage.hash = hash;
              sessionStorage.isPlaying=true;
              Comm.$emit('songChange',{hash:hash});
            }
          },


          vols(){
            if(this.vol){
              this.player.muted=true;
              this.vol=false;
            }else{
              this.player.muted=false;
              this.vol=true;
            }

          },
          prev(){
            if(this.index!=0){
              this.plays(this.index-1);
              this.index--;
              let hash =this.list[this.index].FileHash;
              sessionStorage.hash = hash;
              sessionStorage.isPlaying=true;
              Comm.$emit('songChange',{hash:hash});
            }

          },
          pauses(){
            if(this.isPlaying){
              this.player.pause();
              this.isPlaying=false;
            }else{
              this.player.play();
              this.isPlaying=true;
              sessionStorage.isPlaying=true;
            }
            let hash =this.list[this.index].FileHash;
            sessionStorage.hash = hash;

            Comm.$emit('songChange',{hash:hash});
          }

        },
      mounted:function(){

          Comm.$on('playing',data=> {

              this.list=data.list;
              this.index=data.index;
              this.plays(this.index);
          });
          Comm.$on('spause',()=>{
              this.player.pause();
              this.isPlaying=false;
              this.currentTime=0;
              this.player.currentTime=0;

          });

          Comm.$on('playbyhash',(data)=>{
              this.plays(data.index,data.list);
          });

      },
      filters:{//过滤器
        format:function(date){
          date=new Date(parseInt(date*1000));
          let y = date.getFullYear();
          let M = date.getMonth()+1;

          let d = date.getDate();

          let h = date.getHours();

          let m = date.getMinutes();
          m<10&&(m="0"+m);

          let s = date.getSeconds();
          s<10&&(s="0"+s);

          return y+"/"+M+"/"+d+" "+h+":"+m+":"+s;
        }},
      destroyed:function () {
        console.log(1);
        sessionStorage.isPlaying=undefined;

        sessionStorage.hash = undefined;
      }

    }
</script>
<style>
  .pbox{
    position: relative;
  }
  .pbox span{
    position: absolute;
    top: 40%;
    font-size: 27px;
    left: 30%;
    opacity:0.8;
    color: #14a9ff;
  }

  em{
    font-style: normal;
  }

  .vol,.dload{
    position: relative;
    top: 25px;
    left:28px;
    font-size: 19px;
    cursor: pointer;
  }
  .dload{
    left: 38px;
  }


  .controls{
    color: white;
    min-height: 20px;
  }
  .btns{
    width: 20%;
    float: left;
    min-width: 220px;
  }

  .btng{
    cursor: pointer;
    border: 2px solid #fff;
    border-radius: 50%;
    display: inline-block;
    position: relative;

    font-size:15px;
    text-align: center;
  }
  .btng:hover{
    box-shadow: white 0 0 15px 3px;
    text-shadow: 0px 0px 15px #fff;
  }

    .next{
      margin-right: 15px;
    }
  .prev,.next{
    width: 35px;
    height: 35px;
    line-height: 35px;
    float: right;
    top: 21px;
  }
  .play{
    float: right;
    width: 50px;
    height: 50px;
    line-height: 45px;
    font-size:18px;
    top: 15px;
    margin-left:8px;
    margin-right: 8px;
  }

  audio{
    width: 0;
    height: 0;
    opacity: 0;
  }
  .player{
    width: 100%;
    height: 80px;
    background-color: rgba(0,0,0,0.8);
    position: fixed;
    left: 0;
    bottom: 0px;
  }

  .form-inline .wide {
    width: 80%;
  }
  .imgbox {
    width: 120px;
    height: 140px;
    overflow: hidden;
  }
  .imgbox img{
    max-width: 120px;
    /*max-height: 120px;*/
  }
  .s1 {
    margin-right: 20px;
  }
  .s2 {
    margin-left: 20px;
  }
  body{margin:0; padding:0;}
  .myframe{
    width: 100%;
    height: 1000px;

  }

</style>
