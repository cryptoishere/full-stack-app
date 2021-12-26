let generate = document.getElementById('generatePassphrase');
let submit = document.getElementById('registerAccount');
let address = document.getElementById('username');
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
    oReq.open("GET", URL + ":3000/api/mainnet/1");
    oReq.setRequestHeader('Content-Type', 'text/plain');
    oReq.send();
});

submit.addEventListener('click', function (e) {
    let xhr = new XMLHttpRequest();

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
    xhr.send(`pubkey=${document.getElementById('pubkey').value}&address=${document.getElementById('address').value}`);
}, false);