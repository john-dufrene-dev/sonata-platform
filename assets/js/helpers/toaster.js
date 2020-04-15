import toastr from 'toastr';

import '@@css/helpers/toaster.scss';

toastr.options.closeButton = true;
toastr.options.timeOut = 10000;
toastr.options.timeOut = 10000; // How long the toast will display without user interaction
toastr.options.extendedTimeOut = 6000; // How long the toast will display after a user hovers over it

document.addEventListener("DOMContentLoaded", () => {
    alertToastr();
});

// add your current ip to maintenance mode
function alertToastr() {
    let get_own_toastr = document.getElementById('get_own_toaster')

    if (undefined != get_own_toastr) {

        let type = get_own_toastr.dataset.typeToastr
        let message = JSON.parse(get_own_toastr.dataset.messagesToastr)
        // let close = get_own_toastr.dataset.closeToastr // not use for the moement

        switch (type) {
            case 'success':
                toastr.success(message);
                break;
            case 'warning':
                toastr.warning(message);
                break;
            case 'error':
                toastr.error(message);
                break;
            case 'danger':
                toastr.error(message);
                break;
            case 'info':
                toastr.info(message);
                break;
            default:
                toastr.info(message);
        }
    }
}