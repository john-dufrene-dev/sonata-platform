// IN PROGRESS

// let token = '';                     // get token from response
 
// // Axios
// axios.interceptors.request.use(config => {
//       config.headers.Authorization = `Bearer ${token}`
//       return config;
// });

// axios.defaults.headers.common['Bearer'] = token;
 
// // jQuery,
// $.ajaxSetup({
//     beforeSend: function(xhr) {
//         xhr.setRequestHeader('Authorization', `Bearer ${token}`);
//     }
// });
 
// // Native javascript
// XMLHttpRequest.prototype.realSend = XMLHttpRequest.prototype.send;
// var newSend = function(vData) {
//     this.setRequestHeader('Authorization', 'Bearer ' + token);
//     this.realSend(vData);
// };
// XMLHttpRequest.prototype.send = newSend;