// import axios
// import axios from 'axios';

// import app.css
import '../css/app.scss';

// import jQuery
import $ from 'jquery';

// import boostrap
require('bootstrap');

// Generate global components Vuejs
import Vue from 'vue';
import Default from './components/Default';

Vue.component('default', Default);

// let token = '';                     // get token from response
 
// // Axios
// axios.interceptors.request.use(config => {
//       config.headers.Authorization = `Bearer ${token}`
//       return config;
// });

// axios.defaults.headers.common['Bearer'] = token;
 
// // jQuery,
// $.ajaxSetup({
//     beforeSend: function(xhr) {
//         xhr.setRequestHeader('Authorization', `Bearer ${token}`);
//     }
// });
 
// // Native javascript
// XMLHttpRequest.prototype.realSend = XMLHttpRequest.prototype.send;
// var newSend = function(vData) {
//     this.setRequestHeader('Authorization', 'Bearer ' + token);
//     this.realSend(vData);
// };
// XMLHttpRequest.prototype.send = newSend;
