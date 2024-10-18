document.getElementById('registrationForm').addEventListener('submit', event => {
    event.preventDefault(); // Prevent the default form submission

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    if (password.length !== 8) {
        alert('Password must be exactly 8 characters long.');
        return; // Stop execution if validation fails
    } else {
        const data = { username, password };

        fetch('register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert("Registration Successful");
                window.location.href = 'login.html'; // Redirect to login page
            } else {
                alert("Registration failed: " + result.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred during registration.");
        });
    }
});
