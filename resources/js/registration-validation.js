// Registration Form Validation
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');
    
    if (!registerForm) return;
    
    // Validation functions
    const errorCounts = {};

    function showError(field, message) {
        const errorElement = field.parentNode.querySelector('.error-message');
        if (errorElement) {
            if (message.trim()) {
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            } else {
                errorElement.style.display = 'none';
            }
            field.classList.add('border-red-500', 'bg-red-50');
        }
    }

    function hideError(field) {
        const errorElement = field.parentNode.querySelector('.error-message');
        if (errorElement) {
            errorElement.style.display = 'none';
            field.classList.remove('border-red-500', 'bg-red-50');
            field.classList.add('border-green-500', 'bg-green-50');
        }
    }

    function validateField(field, validationFn) {
        const value = field.value.trim();
        const error = validationFn(value, field.id);
        if (error) {
            errorCounts[field.id] = (errorCounts[field.id] || 0) + 1;
            showError(field, error);
            return false;
        } else {
            errorCounts[field.id] = 0;
            hideError(field);
            return true;
        }
    }
    
    // Validation rules
    const rules = {
        registerFirstName: {
            validate: (value) => {
                if (!value) return 'First name is required.';
                if (value.length < 2) return 'First name must be at least 2 characters.';
                if (/\d/.test(value)) return 'Invalid number.';
                if (/[^A-Za-z_]/.test(value)) return 'Invalid special characters.';
                if (!/^[A-Z][a-z]+(_[A-Z][a-z]+)?$/.test(value)) return 'Start with capital.';
                return null;
            }
        },
        registerSurname: {
            validate: (value) => {
                if (!value) return 'Surname is required.';
                if (value.length < 2) return 'Surname must be at least 2 characters.';
                if (/\d/.test(value)) return 'Invalid number.';
                if (/[^A-Za-z_]/.test(value)) return 'Invalid special characters.';
                if (!/^[A-Z][a-z]+(_[A-Z][a-z]+)?$/.test(value)) return 'Start with capital.';
                return null;
            }
        },
        registerEmail: {
            validate: (value, fieldId) => {
                if (!value) return 'Email is required.';
                if (!/^(?!.*\.\.)[a-zA-Z0-9_+][a-zA-Z0-9._+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value)) {
                    const count = errorCounts[fieldId] || 0;
                    if (count === 0) return 'Your email address contains invalid characters.';
                    return 'Please enter a valid email like name@example.com';
                }
                return null;
            }
        },
        registerRole: {
            validate: (value) => {
                if (!value) return 'Role is required.';
                return null;
            }
        },
        registerPhone: {
            validate: (value, fieldId) => {
                if (!value) return 'Phone is required.';
                if (!/^(0\d{10}|\+63\d{10})$/.test(value)) {
                    const count = errorCounts[fieldId] || 0;
                    if (count === 0) return 'Your mobile number contains invalid characters.';
                    return 'Invalid phone number.';
                }
                return null;
            }
        },
        registerPassword: {
            validate: (value, fieldId) => {
                if (!value) return 'Password is required.';
                if (/\s/.test(value)) return 'Password cannot contain spaces.';
                if (value.length < 8 || !/[A-Z]/.test(value) || !/[a-z]/.test(value) || !/\d/.test(value) || !/[!@#$%^&*]/.test(value)) {
                    const count = errorCounts[fieldId] || 0;
                    if (count === 0) return 'Your password does not meet security requirements.';
                    return 'Password must be at least 8 characters with uppercase, lowercase, number, and special character.';
                }
                return null;
            }
        },
        registerPasswordConfirmation: {
            validate: (value) => {
                if (!value) return 'Confirm password.';
                if (value !== document.getElementById('registerPassword')?.value) return 'Passwords do not match.';
                return null;
            }
        },
        terms: {
            validate: () => {
                if (!document.getElementById('terms')?.checked) return '';
                return null;
            }
        }
    };
    
    // Add error message elements
    function addErrorMessages() {
        const fields = ['registerFirstName', 'registerSurname', 'registerEmail', 'registerRole', 'registerPhone', 'registerPassword', 'registerPasswordConfirmation', 'terms'];
        
        fields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                const errorDiv = document.createElement('p');
                errorDiv.className = 'error-message text-red-600 text-sm mt-1';
                errorDiv.style.display = 'none';
                field.parentNode.appendChild(errorDiv);
            }
        });
    }
    
    // Setup validation
    function setupValidation() {
        addErrorMessages();
        
        Object.keys(rules).forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('blur', () => {
                    validateField(field, rules[fieldId].validate, rules[fieldId].message);
                });
                
                field.addEventListener('input', () => {
                    const errorElement = field.parentNode.querySelector('.error-message');
                    if (errorElement && errorElement.style.display === 'block') {
                        validateField(field, rules[fieldId].validate, rules[fieldId].message);
                    }
                });
            }
        });
    }
    
    // Form submission validation
    function validateForm(form) {
        let isValid = true;
        const fields = form.querySelectorAll('input[required], select[required]');
        
        fields.forEach(field => {
            const fieldId = field.id;
            if (rules[fieldId]) {
                if (!validateField(field, rules[fieldId].validate, rules[fieldId].message)) {
                    isValid = false;
                }
            }
        });
        
        return isValid;
    }
    
    // Setup form handlers
    if (registerForm) {
        setupValidation();
        
        registerForm.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                // Focus first error
                const firstError = this.querySelector('.error-message[style="display: block;"]');
                if (firstError) {
                    firstError.previousElementSibling?.focus();
                }
            }
        });
    }
    
    if (loginForm) {
        const loginFields = ['loginEmail', 'loginPassword'];
        loginFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                const errorDiv = document.createElement('p');
                errorDiv.className = 'error-message text-red-600 text-sm mt-1';
                errorDiv.style.display = 'none';
                field.parentNode.appendChild(errorDiv);
                
                field.addEventListener('blur', () => {
                    validateField(field, rules[fieldId]?.validate || (() => field.value.trim() !== ''), rules[fieldId]?.message || 'This field is required.');
                });
            }
        });
        
        loginForm.addEventListener('submit', function(e) {
            const emailField = document.getElementById('loginEmail');
            const passwordField = document.getElementById('loginPassword');
            
            let isValid = true;
            
            if (!validateField(emailField, rules.loginEmail?.validate || (() => emailField.value.trim() !== ''), rules.loginEmail?.message || 'Email is required.')) {
                isValid = false;
            }
            
            if (!validateField(passwordField, rules.loginPassword?.validate || (() => passwordField.value.trim() !== ''), rules.loginPassword?.message || 'Password is required.')) {
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});
