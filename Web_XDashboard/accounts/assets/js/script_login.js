document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("loginForm");
    const button = document.getElementById("login-button");
    const errorTxt = document.getElementById("error-message");
    const successTxt = document.getElementById("success-message");
    const loading = document.querySelector(".loading_widget");

    function clearMessages() {
        errorTxt.textContent = "";
        errorTxt.style.display = "none";
        successTxt.textContent = "";
        successTxt.style.display = "none";
    }

    function handleFormSubmit(event) {
        event.preventDefault();

        const username = form.querySelector("#username").value;
        const password = form.querySelector("#password").value;

        if (!username || !password) {
            errorTxt.textContent = "Please enter both username and password.";
            errorTxt.style.display = "block";
            return;
        }

        loading.style.display = "block";
        clearMessages();

        const formData = new FormData(form);
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                loading.style.display = "none";
                if (xhr.status === 200) {
                    const data = xhr.responseText;
                    if (data === "success") {
                        successTxt.textContent = "Login successful!";
                        successTxt.style.display = "block";
                        window.location.href = "../index.php";
                    } else {
                        errorTxt.textContent = data;
                        errorTxt.style.display = "block";
                    }
                } else {
                    console.error("Error:", xhr.statusText);
                    errorTxt.textContent = "Something went wrong. Please try again.";
                    errorTxt.style.display = "block";
                }
            }
        };
        xhr.open("POST", "assets/php/login_handler.php");
        xhr.send(formData);
    }

    button.addEventListener("click", handleFormSubmit);

    form.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            handleFormSubmit(event);
        }
    });
});
