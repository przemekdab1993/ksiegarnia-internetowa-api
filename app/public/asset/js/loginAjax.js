$(function() {
    const $containerVote = $('.login-form');
    const $errorBlock = $('#error-message');
    const $userInfo = $('#user-status-login-true');


    if (window.user !== null) {
        $userInfo.find('span.user-name').text(window.user.userName);
        $userInfo.removeClass('d-none');
        $userInfo.next().addClass('d-none');
    }

    $containerVote.find('form').on('submit', function(event) {
        event.preventDefault();

        let dataSet = {
            email : event.target.email.value,
            password : event.target.password.value
        }

        $.ajax({
            url: `/login`,
            method: 'POST',
            headers: {
                "Content-Type": "application/json"
            },
            data: JSON.stringify({
                email: dataSet.email,
                password: dataSet.password,
            }),

            success: function (data, textStatus, request) {
                console.log('Submission was successful.');

                $.ajax({
                    url: request.getResponseHeader('location'),
                    method: 'GET',
                    success: function (data, textStatus, request) {
                        $userInfo.find('span.user-name').text(data.userName);
                        $userInfo.removeClass('d-none');
                        $userInfo.next().addClass('d-none');
                    }
                });
            },
            error: function(jqXHR, textStatus, errorMessage) {
                console.log('An error occurred.');
                if (jqXHR.responseJSON) {
                    $errorBlock.text(jqXHR.responseJSON.error);
                    $errorBlock.removeClass('d-none');
                } else {
                    $errorBlock.text('Unknown error');
                    $errorBlock.removeClass('d-none');
                }
            },
        }).then(function(data) {
            //console.log(data.);
        });
    });
});