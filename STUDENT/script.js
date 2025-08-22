document.addEventListener('DOMContentLoaded', function () {
    const registrationForm = document.getElementById('registrationForm');
    const loginForm = document.getElementById('loginForm');
    const profileForm = document.getElementById('profileForm');
    const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
    const messageModalBody = document.getElementById('messageModalBody');

    // Function to reset all invalid classes
    function resetValidation(form) {
        if (form) {
            Array.from(form.elements).forEach(element => {
                if (element.classList.contains('is-invalid')) {
                    element.classList.remove('is-invalid');
                }
            });
        }
    }

    // Function to show the modal with a given message
    function showModal(message, isSuccess = false) {
        if (messageModalBody) {
            messageModalBody.innerHTML = `<p>${message}</p>`;
            // Optional: Change modal styling based on success/error
            const modalHeader = messageModalBody.parentElement.querySelector('.modal-header');
            if (isSuccess) {
                modalHeader.style.backgroundColor = '#d4edda';
                modalHeader.style.color = '#155724';
            } else {
                modalHeader.style.backgroundColor = '#f8d7da';
                modalHeader.style.color = '#721c24';
            }
        }
        messageModal.show();
    }

    // Registration Form Validation (remains the same)
    if (registrationForm) {
        registrationForm.addEventListener('submit', function (event) {
            let isValid = true;
            resetValidation(registrationForm);

            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirmPassword');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(email.value.trim())) {
                email.classList.add('is-invalid');
                isValid = false;
            }

            if (password.value.trim() === '' || password.value.trim().length < 6) {
                password.classList.add('is-invalid');
                isValid = false;
            }

            if (confirmPassword.value.trim() === '' || confirmPassword.value !== password.value) {
                confirmPassword.classList.add('is-invalid');
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
                const firstInvalid = document.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }

    // Login Form Validation (now submits directly to PHP)
    if (loginForm) {
        loginForm.addEventListener('submit', function (event) {
            // Simple client-side validation
            let isValid = true;
            resetValidation(loginForm);
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(email.value.trim())) {
                email.classList.add('is-invalid');
                isValid = false;
            }

            if (password.value.trim() === '') {
                password.classList.add('is-invalid');
                isValid = false;
            }
            
            if (!isValid) {
                event.preventDefault(); // Prevent submission if validation fails
            }
        });
    }
    
    // Profile Form Validation and Submission
    if (profileForm) {
        profileForm.addEventListener('submit', function (event) {
            event.preventDefault();

            let isValid = true;
            resetValidation(profileForm);

            // Client-side validation for all fields
            const fullName = document.getElementById('fullName');
            if (fullName.value.trim() === '') {
                fullName.classList.add('is-invalid');
                isValid = false;
            }

            const age = document.getElementById('age');
            const ageValue = parseInt(age.value.trim());
            if (isNaN(ageValue) || ageValue < 16 || ageValue > 99) {
                age.classList.add('is-invalid');
                isValid = false;
            }

            const nationality = document.getElementById('nationality');
            if (nationality.value.trim() === '') {
                nationality.classList.add('is-invalid');
                isValid = false;
            }

            const gender = document.getElementById('gender');
            if (gender.value === '') {
                gender.classList.add('is-invalid');
                isValid = false;
            }

            const mobileNumber = document.getElementById('mobileNumber');
            const mobileNumberPattern = /^\d{10}$/;
            if (!mobileNumberPattern.test(mobileNumber.value.trim())) {
                mobileNumber.classList.add('is-invalid');
                isValid = false;
            }

            const address = document.getElementById('address');
            if (address.value.trim() === '') {
                address.classList.add('is-invalid');
                isValid = false;
            }

            const university = document.getElementById('university');
            if (university.value.trim() === '') {
                university.classList.add('is-invalid');
                isValid = false;
            }

            const program = document.getElementById('program');
            if (program.value.trim() === '') {
                program.classList.add('is-invalid');
                isValid = false;
            }

            const universityID = document.getElementById('universityID');
            if (universityID.value.trim() === '') {
                universityID.classList.add('is-invalid');
                isValid = false;
            }

            if (isValid) {
                const formData = new FormData(profileForm);
                fetch('handle_profile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = 'submission.php'; // Redirect on successful server response
                    } else {
                        showModal('Error saving profile. Please try again.', false);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showModal('An unexpected error occurred while saving. Please try again.', false);
                });
            }
        });
    }
});
