$(function () {
    const passphrase = $('#passphrase');

    $(document).on('click', '#login', () => {
        $.post(URL + "/login", {passphrase: passphrase.val()})
            .done(res => {
                res = JSON.parse(res || {});
                if (typeof res?.result === 'string' && res?.result === 'success') {
                    window.location.href = URL;
                }
            });
    });
});