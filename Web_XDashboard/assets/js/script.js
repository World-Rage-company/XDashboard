// ==== training script ==== //
document.addEventListener('DOMContentLoaded', function () {
    const containers = document.querySelectorAll('.training-container');

    containers.forEach(container => {
        const header = container.querySelector('.training-header');
        header.addEventListener('click', function () {
            containers.forEach(item => {
                if (item !== container) {
                    item.classList.remove('active');
                    item.querySelector('.toggle-icon').classList.replace('bx-chevron-up', 'bx-chevron-down');
                }
            });
            container.classList.toggle('active');
            const icon = container.querySelector('.toggle-icon');
            if (container.classList.contains('active')) {
                icon.classList.replace('bx-chevron-down', 'bx-chevron-up');
            } else {
                icon.classList.replace('bx-chevron-up', 'bx-chevron-down');
            }
        });
    });
});

// ==== support script ==== //
document.addEventListener("DOMContentLoaded", () => {
    const addTicketBtn = document.getElementById('add-ticket-btn');
    const ticketCancelBtn = document.getElementById('ticket-cancel');
    const newTicketContainer = document.querySelector('.new-ticket-container');
    const supportHeader = document.querySelector('.support-header');
    const supportContent = document.querySelector('.support-content');

    addTicketBtn.addEventListener('click', () => {
        newTicketContainer.style.display = 'block';
        supportHeader.style.display = 'none';
        supportContent.style.display = 'none';
    });

    ticketCancelBtn.addEventListener('click', () => {
        newTicketContainer.style.display = 'none';
        supportHeader.style.display = 'flex';
        supportContent.style.display = 'block';
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const addTicketForm = document.querySelector('.new-ticket-content');
    const ticketSubmitBtn = document.getElementById('ticket-submit');

    ticketSubmitBtn.addEventListener('click', async () => {
        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;
        const priority = document.getElementById('priority').value;

        try {
            const response = await fetch('assets/php/addticket_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ title, description, priority })
            });

            if (response.ok) {
                console.log('Ticket submitted successfully!');
            } else {
                console.error('Error submitting ticket:', response.statusText);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});
