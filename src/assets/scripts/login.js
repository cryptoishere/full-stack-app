$(function () {
    const passphrase = $('#passphrase');

    $(document).on('click', '#login', () => {
        $.post(URL + "/login", {passphrase: passphrase.val()})
            .done(res => console.log(res));
    });
});