import Vue from 'vue';

// any CSS you import will output into a single css file (account.css in this case)
import '@@css/front/pages/account.scss';

import Header from '@@js/front/components/helpers/Header';
import Account from '@@js/front/components/pages/Account';

const app = new Vue({
    el: '#app',
    components: {
        'default-header': Header,
        'account': Account
    },
    //render: h => h(App), // if you want to just rand the element imported
});

export default app

