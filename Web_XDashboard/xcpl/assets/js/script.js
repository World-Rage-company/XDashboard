document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.querySelector(".sidebar");
    const closeBtn = document.querySelector("#btn-sidebar");

    closeBtn.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        menuBtnChange();
    });

    function menuBtnChange() {
        const iconClass = sidebar.classList.contains("open") ? "bx-menu-alt-right" : "bx-menu";
        closeBtn.classList.replace(closeBtn.classList.item(1), iconClass);
    }

    function showSection(hash) {
        document.querySelectorAll('section').forEach(section => {
            section.classList.remove('active');
        });

        const targetSection = document.querySelector(hash);
        if (targetSection) {
            targetSection.classList.add('active');
        }
    }

    document.querySelectorAll('.nav-list a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const hash = this.getAttribute('href');
            window.location.hash = hash;
            showSection(hash);
        });
    });

    window.addEventListener('hashchange', () => {
        showSection(window.location.hash);
    });

    if (window.location.hash) {
        showSection(window.location.hash);
    } else {
        showSection('#dashboard');
    }
});
