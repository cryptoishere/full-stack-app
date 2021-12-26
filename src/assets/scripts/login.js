$(function () {
    const passphrase = $('#passphrase');

    $(document).on('click', '#login', () => {
        $.ajax({
            url:URL + ":3000/api/pass",
            type: "POST",
            data: {passphrase: passphrase.val()},
            headers: {
                // 'Accept': 'application/json',
                // 'Accept-Encoding': 'gzip, deflate, sdch, br',
                // 'Content-Type': 'text/plain',
                // 'Accept': '*/*'
            },
            contentType: "application/x-www-form-urlencoded",
            dataType: 'json',
            success: (result,status,xhr) => console.log(result),
        }).done(res => console.log(res));
    });
});