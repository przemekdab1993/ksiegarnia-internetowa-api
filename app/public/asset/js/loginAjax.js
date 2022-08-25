$(function() {
    const $containerVote = $('.login-form');
    const $errorBlock = $('#error-message');

    $containerVote.find('form').on('submit', function(event) {
        event.preventDefault();
        const form = $(this);


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

            success: function (data) {
                console.log('Submission was successful.');

                console.log(data);

            },
            error: function(jqXHR, textStatus, errorMessage) {
                console.log('An error occurred.');
                //console.log(errorMessage); // Optional
                console.log(jqXHR.responseJSON); // Optional
                if(jqXHR.responseJSON) {

                    $errorBlock.text(jqXHR.responseJSON.error);
                    $errorBlock.removeClass('d-none');
                }
            },
        }).then(function(data) {

            //console.log(data);
        });
    });
});