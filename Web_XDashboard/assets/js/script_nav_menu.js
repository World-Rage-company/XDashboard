document.addEventListener("DOMContentLoaded", () => {
    const body = document.body,
        sidebar = body.querySelector('nav'),
        toggle = body.querySelector(".toggle"),
        modeSwitch = body.querySelector(".toggle-switch"),
        modeText = body.querySelector(".mode-text"),
        logoutLink = body.querySelector(".logout-link"),
        languageDropdown = body.querySelector('.language-dropdown'),
        languageMenu = languageDropdown.querySelector('.language-menu'),
        dropdownToggle = languageDropdown.querySelector('.dropdown-toggle');

    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        body.classList.toggle("dark", savedTheme === 'dark');
        modeText.innerText = savedTheme === 'dark' ? "Light mode" : "Dark mode";
    }

    const savedLanguage = localStorage.getItem('language') || 'en';
    setLanguage(savedLanguage);

    toggle.addEventListener("click", () => {
        sidebar.classList.toggle("close");
    });

    modeSwitch.addEventListener("click", () => {
        const isDarkMode = body.classList.toggle("dark");
        const newTheme = isDarkMode ? 'dark' : 'light';
        localStorage.setItem('theme', newTheme);
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

    function toggleLanguageMenu() {
        languageMenu.classList.toggle('active');
    }

    document.addEventListener('click', function(e) {
        if (!languageDropdown.contains(e.target)) {
            languageMenu.classList.remove('active');
        }
    });

    dropdownToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        if (sidebar.classList.contains('close')) {
            sidebar.classList.remove('close');
            setTimeout(toggleLanguageMenu, 300);
        } else {
            toggleLanguageMenu();
        }
    });

    document.querySelectorAll('.language-menu a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const lang = this.getAttribute('data-lang');
            setLanguage(lang);
        });
    });

    function setLanguage(lang) {
        localStorage.setItem('language', lang);
        document.querySelectorAll('.language-menu li').forEach(li => {
            li.classList.remove('active');
        });
        document.querySelector(`.language-menu a[data-lang="${lang}"]`).parentElement.classList.add('active');

        const elementsToTranslate = {
            '.nav-text[data-key="dashboard"]': translations[lang].dashboard,
            '.nav-text[data-key="training"]': translations[lang].training,
            '.nav-text[data-key="support"]': translations[lang].support,
            '.nav-text[data-key="logout"]': translations[lang].logout,
            '.nav-text[data-key="language"]': translations[lang].language,
            '.card-title[data-key="userInfo"]': translations[lang].userInfo,
            'p strong[data-key="email"]': translations[lang].email,
            'p strong[data-key="mobile"]': translations[lang].mobile,
            'p strong[data-key="status"]': translations[lang].status,
            '.card-title[data-key="subscription"]': translations[lang].subscription,
            'p strong[data-key="startDate"]': translations[lang].startDate,
            'p strong[data-key="endDate"]': translations[lang].endDate,
            'p strong[data-key="daysRemaining"]': translations[lang].daysRemaining,
            '.card-title[data-key="traffic"]': translations[lang].traffic,
            'p strong[data-key="download"]': translations[lang].download,
            'p strong[data-key="upload"]': translations[lang].upload,
            'p strong[data-key="total"]': translations[lang].total,
            'p strong[data-key="remainingData"]': translations[lang].remainingData,
            'p strong[data-key="unlimited"]': translations[lang].unlimited,
            '.card-title[data-key="additionalInfo"]': translations[lang].additionalInfo,
            'p strong[data-key="referral"]': translations[lang].referral,
            'p strong[data-key="description"]': translations[lang].description
        };

        for (const key in elementsToTranslate) {
            const element = document.querySelector(key);
            if (element) {
                element.textContent = elementsToTranslate[key];
            }
        }

        console.log(`Language set to: ${lang}`);
    }
});

function showSection(section) {
    document.querySelector('.dashboard').style.display = 'none';
    document.querySelector('.training').style.display = 'none';
    document.querySelector('.support').style.display = 'none';

    if (section === 'dashboard') {
        document.querySelector('.dashboard').style.display = 'block';
    } else if (section === 'training') {
        document.querySelector('.training').style.display = 'block';
    } else if (section === 'support') {
        document.querySelector('.support').style.display = 'block';
    }
}
