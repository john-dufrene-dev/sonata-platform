import Vue from 'vue';

// import axios
// import axios from 'axios';

// import app.css
import '@@css/app.scss';

// import jQuery
import $ from 'jquery';

// import boostrap
require('bootstrap');

import '@@js/config/vue';
import '@@js/config/security';

const app = new Vue({
    el: '#app',
    // components: {App},
    //render: h => h(App), // if you want to just rand the element imported
});
  
export default app
