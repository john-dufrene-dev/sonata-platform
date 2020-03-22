document.addEventListener("DOMContentLoaded", () => {
    addCurrentIp();
});

// add your current ip to maintenance mode
function addCurrentIp() {
    let selector_ip = document.getElementById('add_ip_address')
    if (undefined != selector_ip) {
        selector_ip.addEventListener("click", (e) => {
            e.preventDefault()
            let currentIp = document.getElementById('myIP')
            let allIps = document.getElementById("configuration_CONF_MAINTENANCE_IP_VALID");
            let tips = (undefined == allIps.value 
                || '' == allIps.value 
                || null == allIps.value) ? '' : ',';
            console.log(allIps.value)
            allIps.value = allIps.value  + tips + currentIp.dataset.myIp
            // console.log(allIps)
        });
    }
}