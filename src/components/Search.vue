
<template>
  <div class="card  search_list" style="margin-top: 20px;padding-bottom: 40px">
    <div class="card-header">
      <form class="form-inline">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="搜索歌曲/歌手名" v-model="searchInput">
          <span class="input-group-btn">
        <button class="btn btn-success" type="button" :disabled="searchInput==''" @click="getData(searchInput,1)">Go!</button>
      </span>
        </div><!-- /input-group -->
      </form>
    </div>

    <div class="card-block" v-if="!searchResultJson && !isSearching">
      <h5 align="center" style="margin-top: 50px" class="text-muted">输入关键词开始搜索</h5>
    </div>

    <div class="card-block" v-if="isSearching">
      <h5 align="center"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i> 正在搜索</h5>
    </div>

    <div class="card-block" v-if="!isSearching && sData.length!=0 ">
    <div class="media">
        <div class="media-left imgbox">
          <a  href="#" target="_blank">
            <img class="media-object rounded " :src="sData.img" style="margin-top: 5px;">
          </a>
        </div>
        <div class="media-body">
          <a href="#" target="_blank" class="nav-link"><h5><strong>{{sData.singername}}</strong></h5></a>
          <p class="singerinfo">{{sData.sinfo}}</p>

          <p class="text-muted" style="margin-bottom: 0px;">
            <a href="javascript:">
              <i class="fa fa-lg float-xs-right"
                 ></i></a>
            <span title="单曲数量" class="s1"><i class="fa fa-music"></i> <em>{{sData.song_count}}</em> 单曲 </span>
            <span title="专辑数量" class="s1"><i class="fa fa-soccer-ball-o"></i> <em>{{sData.album_count}}</em> 专辑</span>
            <span title="MV数量" class="s1"><i class="fa fa-video-camera"></i> <em>{{sData.mv_count}}</em> MV </span>
            <span  title="地区" class=" s2"> <i class="fa fa-map-marker"></i> {{sData.areaname}} </span></p>
          <p class="text-muted">
            <small class="text-muted s1">
            <a  target="_blank" class="nav-link" >别名:</a>
            <span >{{sData.othername}}</span> </small> </p>
        </div>

    </div>
 </div>

    <div class="card-block" style="padding-top:0"  v-if="!isSearching && searchResultJson">

      <h5 align="center" style="margin-top: 10px" class="text-muted text-xs-left">"<em>{{searchKey}}</em>" 搜索到{{total}}条结果，共{{total | maxPage}}页

      </h5>
      <button  @click="play(0)" class="btn btn-primary float-xs-right"><i class="fa fa-play"></i> 播放 </button>
    </div>


    <div class="card-block table-responsive text-muted"  v-if="!isSearching && searchResultJson">
      <table class="table table-hover">
          <thead style="background-color: #f8f8f8">
           <tr>
             <th>歌曲名</th>
             <th class="hidden-sm-down">专辑</th>
             <th></th>
             <th class="text-xs-right">时长</th>
           </tr>
          </thead>

        <tbody v-if="searchSongList.length!=0">
            <tr v-for="(item,index) in searchSongList">
              <td><a href="#" class="text-muted" v-html="item.FileName"> </a></td>
              <td  class="hidden-sm-down" v-html="item.AlbumName"></td>
              <td class="text-xs-right text-nowrap">
                <span class="fa" :class="{'text-danger fa-stop':item.isPlaying,'fa-play':!item.isPlaying}" @click="play(index)" style="margin-right: 10px;cursor: pointer"></span>
                <span class="fa" :class="{'fa-heart-o':!item.isSub,'fa-heart text-danger':item.isSub}" @click="subscribe(index)" style="cursor: pointer"></span>
              </td>
              <td class="text-xs-right">{{getTime(item.Duration)}}</td>
            </tr>

        </tbody>
      </table>

      <div>
        <nav aria-label="Page navigation">
          <ul class="pagination float-xs-right">
            <li class="page-item" :class="{'disabled':page-1<=1}" @click="getData(searchKey,page-1,true)">
              <a class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
              </a>
            </li>
            <li class="page-item"  @click="getData(searchKey,page-2,true)" v-if="page-2>=1"><a class="page-link">{{page-2}}</a></li>
            <li class="page-item"  @click="getData(searchKey,page-1,true)" v-if="page-1>=1"><a class="page-link">{{page-1}}</a></li>
            <li class="page-item active"><a class="page-link">{{page}}</a></li>
            <li class="page-item"  @click="getData(searchKey,page+1,true)" v-if="page+1<=maxPage"><a class="page-link">{{page+1}}</a></li>
            <li class="page-item" @click="getData(searchKey,page+2,true)" v-if="page+2<=maxPage"><a class="page-link">{{page+2}}</a></li>
            <li class="page-item" @click="getData(searchKey,page+1,true)" :class="{'disabled':page>=maxPage}">
              <a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>


    </div>

  </div>
</template>

<style>
  .input-group>*{
    z-index: 0;
  }

  .search_list em{
    color: #14a9ff;
    font-style: normal;

  }
  .singerinfo{
    margin-bottom: 0px;
    max-height: 4.5rem;
    overflow: hidden;
    text-overflow:ellipsis;
    -webkit-line-clamp: 3; /* 设置超出多少行隐藏 */
    -webkit-box-orient: vertical;
    /* 设置 display 为 -webkit-box 或者 -webkit-inline-box 时为隐藏状态 */
    display: -webkit-inline-box;
  }

  a{
    color:#373a3c;
  }

  .form-inline .wide {
    width: 80%;
  }
  .imgbox {
    width: 100px;
    height: 120px;
    overflow: hidden;
  }
  .form-inline .input-group{
    width: 100%!important;
  }
  .imgbox img{
    max-width: 100px;
    /*max-height: 120px;*/
  }
  .page-item{
    cursor: pointer;
    color: #687077;
  }

  .s1 {
    margin-right: 20px;
  }
  .s2 {
    margin-left: 20px;
  }
  .pr0{
    position: absolute;
    right: 0;
  }
  .page-link{
    z-index: 0!important;
  }

</style>


<script>

  export default {
    props:['key1'],
    name : 'SearchResult',
    data() {
      return {
        searchKey: '',
        searchInput: '',    // 输入框的值
        searchResultJson: '',
        sData:[],
        isSearching: false,
        page: 1,
        hash:"",
        isPlaying:false,
        maxPage:1,
        searchSongList:[],
        singerInfo:{},
        total:0,
      }
    },
    methods:{
     getData:function(key,pno,togglePage){
       if(!togglePage){
         this.searchKey=this.searchInput;
       }
       this.isSearching=true;

        this.$http.get(
          'http://127.0.0.1/RSS/getMList.php?key='+key+'&page='+pno,
          { withCredentials:true}
        ).then(data=>{
            this.searchResultJson = data.body.data;
            this.sData = data.body.sData;

            this.total=this.searchResultJson.total;
            this.maxPage=this.maxPage1(this.total);
            this.page=this.searchResultJson.page;

            this.searchSongList=this.searchResultJson.lists;

            for(let i =0;i<this.searchSongList.length;i++){
              if(this.searchSongList[i].FileHash===this.hash && this.isPlaying || this.searchSongList[i].FileHash===sessionStorage.hash && sessionStorage.isPlaying){
                this.$set(this.searchSongList[i], 'isPlaying', true);
              }
              let oList = localStorage.subList;
              if(oList!==undefined){
                oList=JSON.parse(oList);
                for(let z =0;z<oList.length;z++){
                  if(this.searchSongList[i].FileHash===oList[z].hash){
                    this.$set(this.searchSongList[i], 'isSub', true);
                    break;
                  }
                }
              }

            }
            this.isSearching=false;
        },function (res) {
          alert('哎呀 网络好像出错了!');
        });
    },



      maxPage1(total){
        return Math.ceil(total/8);
      },

      handleSub(hash){
       if(this.searchSongList.length){
         for(let i =0;i<this.searchSongList.length;i++){
           if(this.searchSongList[i].FileHash===hash){
             this.$set(this.searchSongList[i],'isSub',false);
           }
         }
       }

      },

      subscribe(index){//收藏

        let hash = this.searchSongList[index].FileHash;
        let list = localStorage.subList;
        if(!this.searchSongList[index].isSub){
         this.$http.get('http://127.0.0.1/RSS/getMusic.php?hash='+hash,{withCredentials:true}).then(data=>{
           let item = data.body.data;
           console.log(item);
           if(!list){
             list=[];
             list.push(item);
             localStorage.subList=JSON.stringify(list);
         }else{
             let oldList = JSON.parse(list);
             oldList.push(item);
             localStorage.subList=JSON.stringify(oldList);
         }
           this.$set(this.searchSongList[index],'isSub',true);
           Comm.$emit('getSub');
         });

        }else{
          console.log(2);
          if(list){
            let oList  = JSON.parse(list);
            for(let i=0;i<oList.length;i++){
              if(oList[i].hash===hash){
                oList.splice(i,1);
                break;
              }
            }
            localStorage.subList=JSON.stringify(oList);
            this.$set(this.searchSongList[index],'isSub',false);
            Comm.$emit('getSub');
          }

        }

      },

       getZero(x){
        if(x>9){
          return x;
        }else{
          return "0"+x;
        }
      },
      getTime(time){

        let H=this.getZero(parseInt(time/3600));
        let M=this.getZero(parseInt((time%3600)/60));
        let S=this.getZero(parseInt(time%60));
        if(time>3600){
          return H+":"+M+":"+S;
        }else{
          return M+":"+S;
        }

      },

      play(index){
        if(this.searchSongList[index].isPlaying==true){
          this.$set(this.searchSongList[index], 'isPlaying', false);//数组更新
          Comm.$emit('spause');
          this.hash="";
          sessionStorage.hash=undefined;
          this.isPlaying=false;
          sessionStorage.isPlaying=false;
          return;
        }

        for(let i=0;i<this.searchSongList.length;i++){
          this.$set(this.searchSongList[i], 'isPlaying', false);//数组更新
        }

        this.$set(this.searchSongList[index], 'isPlaying', true);//数组更新

        this.hash=this.searchSongList[index].FileHash;
        sessionStorage.hash=this.searchSongList[index].FileHash;
        this.isPlaying=true;
        sessionStorage.isPlaying=true;
        Comm.$emit('playing',{"list":this.searchSongList,index:index});
      },
      songChange(data){
        let hash = data.hash;
        console.log(hash);
         this.hash=hash;
         if(sessionStorage.isPlaying!==null){
            this.isPlaying=sessionStorage.isPlaying;
         }
            for(let i =0;i<this.searchSongList.length;i++){
              this.$set(this.searchSongList[i], 'isPlaying', false);
              if(this.searchSongList[i].FileHash===this.hash && this.isPlaying){
                this.$set(this.searchSongList[i], 'isPlaying', true);
              }
            }
      }


    },
    mounted:function(){


      if(sessionStorage.isPlaying){
        this.isPlaying=sessionStorage.isPlaying;
      }
      if(sessionStorage.hash){
        this.hash=sessionStorage.hash;
        this.songChange(this.hash);
      }

        if(!this.$route.params.key1){
          console.log('参数为空');
        }else{
          this.searchKey=this.$route.params.key1;
          this.searchInput=this.$route.params.key1;
          this.getData(this.searchKey,this.page);
        }
        Comm.$on('songChange',this.songChange);
        Comm.$on('subChange',this.handleSub);
    },


    filters:{

      maxPage(total){
        return Math.ceil(total/8);
      }

    }

  }
</script>
