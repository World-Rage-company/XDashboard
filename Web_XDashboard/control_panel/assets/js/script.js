let notifications = document.querySelector('.notifications');

function createToast(type, icon, title, text) {
    let newToast = document.createElement('div');
    newToast.innerHTML = `
        <div class="toast ${type}">
            <i class="bx ${icon}"></i>
            <div class="content">
                <div class="title">${title}</div>
                <span>${text}</span>
            </div>
            <i class="bx bx-x close" onclick="(this.parentElement).remove()"></i>
        </div>`;

    notifications.appendChild(newToast);
    setTimeout(() => newToast.remove(), 5000);
}

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
                    createToast('success', 'bx-check-circle', 'Success', res.message);
                } else {
                    createToast('error', 'bx-x-circle', 'Error', res.message);
                }
            },
            error: function(xhr, status, error) {
                createToast('error', 'bx-x-circle', 'Error', 'Error updating access state.');
                console.error('Error updating access state:', error);
            }
        });
    });
});
