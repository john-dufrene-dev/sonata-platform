// any CSS you import will output into a single css file (app.css in this case)
import '../../css/pages/account.scss';

import Vue from 'vue';
import Account from '../components/pages/Account';

const account = new Vue({
    el: '#account',
    components: {Account},
    //render: h => h(Account), // if you want to just rand the element imported
});
  
export default account