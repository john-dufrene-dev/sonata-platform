// import app.css
import '@@css/admin/pages/maintenance.scss';

document.addEventListener("DOMContentLoaded", () => {

    let selector_ip = document.getElementById('add_ip_address')
    let currentIp = document.getElementById('myIP')
    let allIps = document.getElementById("configuration_CONF_MAINTENANCE_IP_VALID");

    if (undefined != selector_ip) {
        addCurrentIp(selector_ip, currentIp, allIps);
    }
    
});

// add your current ip to maintenance mode
function addCurrentIp(selector, own, all) {
    selector.addEventListener("click", (e) => {
        e.preventDefault()
        let tips = (undefined == all.value 
            || '' == all.value 
            || null == all.value) ? '' : ',';
        all.value = all.value  + tips + own.dataset.myIp
    });
}