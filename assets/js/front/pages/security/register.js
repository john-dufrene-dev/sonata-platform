import Vue from 'vue';

// any CSS you import will output into a single css file (register.css in this case)
import '@@css/front/pages/security/register.scss';

import Header from '@@js/front/components/helpers/Header';
import Register from '@@js/front/components/pages/security/Register';

const app = new Vue({
    el: '#app',
    components: {
        'default-header': Header,
        'register': Register
    },
    //render: h => h(App), // if you want to just rand the element imported
});

export default app
