document.addEventListener("DOMContentLoaded", () => {
    const body = document.body,
        sidebar = body.querySelector('nav'),
        toggle = body.querySelector(".toggle"),
        modeSwitch = body.querySelector(".toggle-switch"),
        modeText = body.querySelector(".mode-text"),
        logoutLink = body.querySelector(".logout-link");

    toggle.addEventListener("click", () => {
        sidebar.classList.toggle("close");
    });

    modeSwitch.addEventListener("click", () => {
        const isDarkMode = body.classList.toggle("dark");
        modeText.innerText = isDarkMode ? "Light mode" : "Dark mode";
    });

    logoutLink.addEventListener("click", async (e) => {
        e.preventDefault();
        try {
            const response = await fetch('assets/php/logout.php', { method: 'POST' });
            if (response.ok) {
                window.location.href = '../../accounts/login.php';
            } else {
                console.error('Logout failed');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});

function showSection(section) {
    document.querySelector('.dashboard').style.display = 'none';
    document.querySelector('.support').style.display = 'none';

    if (section === 'dashboard') {
        document.querySelector('.dashboard').style.display = 'block';
    } else if (section === 'support') {
        document.querySelector('.support').style.display = 'block';
    }
}
