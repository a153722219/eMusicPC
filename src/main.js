import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import App from './App'
import 'bootstrap/dist/css/bootstrap.css'
import 'font-awesome/css/font-awesome.css'
import Home from './components/Home'
import Search from './components/Search'
import user from './components/user';
import './assets/css/my.css';
import "babel-polyfill";
window.Comm=new Vue();

Vue.use(VueRouter);
Vue.use(VueResource);


const routes = [{
  path: '/',
  component: Home
},{
  path: '/home',
  component: Home
},
  {
    path: '/search',
    component: Search
  },
  {
    path: '/user',
    component: user
  },
  {
  path: '/search/:key1',
  component: Search
},
  {
    path:'*',
    component:Home
  }
];
const router = new VueRouter( {
  routes
});

new Vue({
  router,
  ...App
}).$mount('#app');

