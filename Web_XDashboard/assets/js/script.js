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
