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
    const newTicketContainer = document.querySelector('.new-ticket-container');
    const supportHeader = document.querySelector('.support-header');
    const supportContent = document.querySelector('.support-content');
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');

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
                    // Clear input fields
                    titleField.value = '';
                    descriptionField.value = '';
                    priorityField.value = 'medium'; // Reset to default priority
                    setTimeout(() => {
                        newTicketContainer.style.display = 'none';
                        supportHeader.style.display = 'flex';
                        supportContent.style.display = 'block';
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
        supportContent.style.display = 'block';
    });

    function displaySuccessMessage(message) {
        successMessage.textContent = message;
        successMessage.style.display = 'block';
        setTimeout(() => {
            successMessage.style.display = 'none';
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
