import routes from '@@js/helpers/components/router/routes.json';

import Routing from '@@vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

export default Routing

// How to use in js file :

// import Routing from '@@js/front/config/router.js';
// console.log(Routing.generate('your_name_route'));

