$(document).ready(function() {
    $('.toggle-switch').click(function() {
        $(this).toggleClass('on');
        let userId = $(this).data('userid');
        let newAccess = $(this).hasClass('on') ? 1 : 0;

        $.ajax({
            type: 'POST',
            url: 'assets/php/access_user_handler.php',
            data: {
                userId: userId,
                newAccess: newAccess
            },
            success: function(response) {
                let res = JSON.parse(response);
                if (res.status === 'success') {
                    $('#success-message').text(res.message).show();
                    $('#error-message').hide();
                    setTimeout(() => {
                        $('#success-message').fadeOut();
                    }, 2000);
                } else {
                    $('#error-message').text(res.message).show();
                    $('#success-message').hide();
                    setTimeout(() => {
                        $('#error-message').fadeOut();
                    }, 2000);
                }
            },
            error: function(xhr, status, error) {
                $('#error-message').text('Error updating access state.').show();
                $('#success-message').hide();
                setTimeout(() => {
                    $('#error-message').fadeOut();
                }, 2000);
                console.error('Error updating access state:', error);
            }
        });
    });
});

function toggleLanguageMenu() {
    document.querySelector('.language-menu').classList.toggle('active');
}

document.querySelector('.toggle-switch-mode').addEventListener('click', () => {
    document.body.classList.toggle('dark');
});
