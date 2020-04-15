import Vue from 'vue';

// import axios
// import axios from 'axios';

// import app.css
import '@@css/front/app.scss';

// import jQuery
import $ from 'jquery';

// import boostrap
require('bootstrap');

import '@@js/front/config/vue';
import '@@js/front/config/security';

const app = new Vue({
    el: '#app',
    // components: {App},
    //render: h => h(App), // if you want to just rand the element imported
});
  
export default app
