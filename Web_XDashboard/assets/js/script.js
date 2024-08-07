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
    const addTicketForm = document.querySelector('.new-ticket-content');
    const ticketSubmitBtn = document.getElementById('ticket-submit');
    const ticketCancelBtn = document.getElementById('ticket-cancel');
    const addTicketBtn = document.getElementById('add-ticket-btn');
    const closeopticket = document.getElementById('btn-close-op-ticket');
    const newTicketContainer = document.querySelector('.new-ticket-container');
    const supportHeader = document.querySelector('.support-header');
    const supportContent = document.querySelector('.support-content');
    const openticketcontainer = document.querySelector('.op-ticket-container');
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');

    const ticketRows = document.querySelectorAll('tbody tr');

    ticketSubmitBtn.addEventListener('click', async () => {
        const titleField = document.getElementById('title');
        const descriptionField = document.getElementById('description');
        const priorityField = document.getElementById('priority');

        const title = titleField.value;
        const description = descriptionField.value;
        const priority = priorityField.value;

        if (!title || !description || !priority) {
            displayErrorMessage("All fields are required.");
            return;
        }

        try {
            const response = await fetch('assets/php/addticket_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ title, description, priority })
            });

            const result = await response.json();

            if (response.ok) {
                if (result.status === 'success') {
                    displaySuccessMessage(result.message);
                    titleField.value = '';
                    descriptionField.value = '';
                    priorityField.value = 'medium';
                    setTimeout(() => {
                        newTicketContainer.style.display = 'none';
                        supportHeader.style.display = 'flex';
                        supportContent.style.display = 'flex';
                    }, 4000);
                } else {
                    displayErrorMessage(result.message);
                }
            } else {
                displayErrorMessage('Error submitting ticket: ' + response.statusText);
                console.error('Error submitting ticket:', response.statusText);
            }
        } catch (error) {
            displayErrorMessage('Error: ' + error.message);
            console.error('Error:', error);
        }
    });

    addTicketBtn.addEventListener('click', () => {
        newTicketContainer.style.display = 'block';
        supportHeader.style.display = 'none';
        supportContent.style.display = 'none';
    });

    ticketCancelBtn.addEventListener('click', () => {
        newTicketContainer.style.display = 'none';
        supportHeader.style.display = 'flex';
        supportContent.style.display = 'flex';
    });

    // ticketRows.forEach(row => {
    //     row.addEventListener('click', () => {
    //         newTicketContainer.style.display = 'none';
    //         supportHeader.style.display = 'none';
    //         supportContent.style.display = 'none';
    //         openticketcontainer.style.display = 'flex';
    //     });
    // });

    closeopticket.addEventListener('click', () => {
        newTicketContainer.style.display = 'none';
        openticketcontainer.style.display = 'none';
        supportHeader.style.display = 'flex';
        supportContent.style.display = 'flex';
    });

    function displaySuccessMessage(message) {
        successMessage.textContent = message;
        successMessage.style.display = 'block';
        setTimeout(() => {
            successMessage.style.display = 'none';
            location.reload();
        }, 2000);
    }

    function displayErrorMessage(message) {
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 3000);
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const ticketRows = document.querySelectorAll('tbody tr');
    const newTicketContainer = document.querySelector('.new-ticket-container');
    const supportHeader = document.querySelector('.support-header');
    const supportContent = document.querySelector('.support-content');
    const openticketcontainer = document.querySelector('.op-ticket-container');

    ticketRows.forEach(row => {
        row.addEventListener('click', async () => {
            const ticketId = row.getAttribute('data-ticket-id');

            try {
                const response = await fetch('assets/php/get_ticket_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: ticketId })
                });

                const result = await response.json();

                if (response.ok) {
                    if (result.status === 'success') {
                        displayTicketInfo(result.ticket);
                        newTicketContainer.style.display = 'none';
                        supportHeader.style.display = 'none';
                        supportContent.style.display = 'none';
                        openticketcontainer.style.display = 'flex';
                    } else {
                        displayErrorMessage(result.message);
                    }
                } else {
                    displayErrorMessage('Error fetching ticket: ' + response.statusText);
                    console.error('Error fetching ticket:', response.statusText);
                }
            } catch (error) {
                displayErrorMessage('Error: ' + error.message);
                console.error('Error:', error);
            }
        });
    });

    function displayTicketInfo(ticket) {
        document.querySelector('.title-ticket').textContent = ticket.title;
        document.querySelector('.description-ticket').textContent = ticket.description;
        document.querySelector('.status-ticket').textContent = `Status: ${ticket.status}`;
        document.querySelector('.priority-ticket').textContent = `Priority: ${ticket.priority}`;
        document.querySelector('.creation-ticket').textContent = `Created At: ${ticket.created_at}`;

        const responsesList = document.querySelector('.responses-list');
        responsesList.innerHTML = '';

        if (ticket.responses && ticket.responses.length > 0) {
            ticket.responses.forEach(response => {
                const li = document.createElement('li');
                li.textContent = `${response.response} - Responder ID: ${response.responder_id}, Responded At: ${response.responded_at}`;
                responsesList.appendChild(li);
            });
        } else {
            const li = document.createElement('li');
            li.textContent = 'No responses yet.';
            responsesList.appendChild(li);
        }

        const openticketcontainer = document.querySelector('.op-ticket-container');
        openticketcontainer.style.display = 'flex';
    }

    function displayErrorMessage(message) {
        const errorMessage = document.getElementById('error-message');
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 3000);
    }
});
