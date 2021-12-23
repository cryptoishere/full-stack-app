// import * as $ from 'jquery'
// let $ = require('jquery')


// require('../css/app.css');
console.log('register');

let generate = document.getElementById('generatePassphrase');
let submit = document.getElementById('registerAccount');
let address = document.getElementById('address');
let passphrase = document.getElementById('passphrase');
let pubkey = document.getElementById('pubkey');

generate.addEventListener('click', function () {
    function reqListener () {
        data = JSON.parse(this.response);

        address.value = data[0].address;
        passphrase.value = data[0].pass;
        pubkey.value = data[0].pubkey;
    }
      
    let oReq = new XMLHttpRequest();
    oReq.addEventListener("load", reqListener);
    oReq.open("GET", "http://localhost:3000/api/mainnet/1");
    oReq.setRequestHeader('Content-Type', 'text/plain')
    oReq.send();
});

submit.addEventListener('click', function (e) {
    let xhr = new XMLHttpRequest();

    xhr.open("POST", 'http://localhost/register', true);

    //Send the proper header information along with the request
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() { // Call a function when the state changes.
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            // Request finished. Do processing here.
            let res = JSON.parse(this.response || {});

            if (res['result'] === 'success') {
                location.href = 'http://localhost/login';
            }
        }
    }
    xhr.send(`pubkey=${document.getElementById('pubkey').value}&address=${document.getElementById('address').value}`);
    // xhr.send(new Int8Array());
    // xhr.send(document);
}, false);