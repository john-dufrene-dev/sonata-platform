// any CSS you import will output into a single css file (account.css in this case)
import '@@css/front/pages/account.scss';

import Vue from 'vue';
import Account from '@@js/front/components/pages/Account';

Vue.component('account', Account);
