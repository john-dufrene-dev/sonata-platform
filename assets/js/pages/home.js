// any CSS you import will output into a single css file (app.css in this case)
import '../../css/pages/home.scss';

import Vue from 'vue';
import Home from '../components/pages/Home';

const home = new Vue({
    el: '#home',
    components: {Home},
    //render: h => h(Home), // if you want to just rand the element imported
});
  
export default home