<template>
  <div id="app">
    <div class="container">
      <nav class="navbar navbar-dark bg-inverse navbar-fixed-top">
        <div class="container-fluid">

        <div class="row">
        <div class="col-xs-12 col-md-6">
        <router-link to="/" class="navbar-brand text-success"> 简 听</router-link>
        <ul class="nav navbar-nav">
          <li class="nav-item">
            <router-link to="/home" class="nav-link"><i class="fa fa-home"></i> Home</router-link>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-flag"></i> Hot</a>
          </li>
        </ul>
        </div>
          <div class="col-md-6 col-xs-12">
            <form class="form-inline ">
              <input class="form-control" style="width: 50%;display: inline-block;" v-model="key1" type="text" placeholder="搜索歌曲名/歌手">
              <a><i class="fa fa-search btn btn-outline-success" @click="jumptoSearch"></i></a>
              <i class="fa fa-user-o btn btn-outline-success hidden-sm-up" @click="jumptoUser"></i>
            </form>
        </div>
        </div>
        </div>
      </nav>

      </div>

    <div class="container" style="margin-top: 80px">
      <div class="row">
        <div class="col-xs-12 col-md-3 push-md-9 col-xl-3 push-xl-9">
          <sidebar></sidebar>
        </div>
        <div class="col-xs-12 col-md-9 pull-md-3 col-xl-9 pull-xl-3">
          <router-view></router-view>
        </div>
      </div>

    </div>
    <player></player>
  </div>


  </div>
</template>

<script>
  import Siderbar from './components/Sidebar.vue'
  import Comm from './components/comm.service.js';
  import player from './components/player.vue';
  export default {
    components: {
      'sidebar': Siderbar,
      'player':player
    },
    data:function(){
      return {
        key1:""
      };
    },
    methods: {
      jumptoSearch(){
          this.$router.push('/search/'+this.key1);
      },
      jumptoUser(){
        this.$router.push('/user');
      }
    }
  }
</script>
