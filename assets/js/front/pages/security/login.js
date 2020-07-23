import Vue from 'vue';

// any CSS you import will output into a single css file (login.css in this case)
import '@@css/front/pages/security/login.scss';

import Header from '@@js/front/components/helpers/Header';
import Login from '@@js/front/components/pages/security/Login';

const app = new Vue({
    el: '#app',
    components: {
        'default-header': Header,
        'login': Login
    },
    //render: h => h(App), // if you want to just rand the element imported
});

export default app
