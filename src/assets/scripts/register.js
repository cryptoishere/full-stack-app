let generate = document.getElementById('generatePassphrase');
let submit = document.getElementById('registerAccount');
let address = document.getElementById('username');
let passphrase = document.getElementById('passphrase');
let pubkey = document.getElementById('pubkey');

generate.addEventListener('click', function () {
    function reqListener () {
        data = JSON.parse(this.response);

        address.defaultValue = data[0].address;
        passphrase.defaultValue = data[0].pass;
        pubkey.defaultValue = data[0].pubkey;
    }
      
    let xhr = new XMLHttpRequest();
    xhr.addEventListener("load", reqListener);
    xhr.open("GET", URL + ":3000/api/mainnet/1");
    xhr.setRequestHeader('Content-Type', 'text/plain');
    xhr.send();
});

submit.addEventListener('click', function (e) {
    let xhr = new XMLHttpRequest();
    let pubkey = document.getElementById('pubkey');
    let address = document.getElementById('username');

    xhr.open("POST", window.location.origin + '/register', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() { // Call a function when the state changes.
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
            let res = JSON.parse(this.response || {});

            if (res['result'] === 'success') {
                location.href = window.location.origin + '/login';
            }
        }
    }
    xhr.send(`pubkey=${pubkey.defaultValue}&address=${address.defaultValue}`);
}, false);