import { api } from './main';

interface JobFormData {
    title: string;
    company: string;
    location: string;
    salary?: string;
    employmentType: string;
    field: string;
    email: string;
    startDate?: string;
    description: string;
    requirements?: string;
    benefits?: string;
}

export function initJobPoster() {
    const form = document.getElementById('jobPostForm') as HTMLFormElement;
    if (form) {
        form.addEventListener('submit', handleSubmit);
        setupValidation();
    }
}

function setupValidation() {
    // Real-time validation for required fields
    const requiredFields = ['title', 'company', 'location', 'employmentType', 'field', 'email', 'description'];
    
    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName) as HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement;
        if (field) {
            field.addEventListener('blur', () => validateField(field));
            field.addEventListener('input', () => clearFieldError(field));
        }
    });
    
    // Email validation
    const emailField = document.getElementById('email') as HTMLInputElement;
    if (emailField) {
        emailField.addEventListener('blur', () => validateEmail(emailField));
    }
}

async function handleSubmit(e: Event) {
    e.preventDefault();
    
    const form = e.target as HTMLFormElement;
    const submitButton = document.getElementById('submitButton') as HTMLButtonElement;
    
    // Clear previous errors
    clearAllErrors();
    
    // Validate form
    if (!validateForm()) {
        return;
    }
    
    // Get form data
    const formData = new FormData(form);
    const data: JobFormData = {
        title: formData.get('title') as string,
        company: formData.get('company') as string,
        location: formData.get('location') as string,
        salary: formData.get('salary') as string || undefined,
        employmentType: formData.get('employmentType') as string,
        field: formData.get('field') as string,
        email: formData.get('email') as string,
        startDate: formData.get('startDate') as string || undefined,
        description: formData.get('description') as string,
        requirements: formData.get('requirements') as string || undefined,
        benefits: formData.get('benefits') as string || undefined,
    };
    
    // Remove empty optional fields
    if (!data.salary) delete data.salary;
    if (!data.startDate) delete data.startDate;
    if (!data.requirements) delete data.requirements;
    if (!data.benefits) delete data.benefits;
    
    // Submit form
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
    
    try {
        const response = await api.post('/jobs', data);
        
        showAlert('success', 'Job posted successfully! It will be reviewed by our administrators and published once approved.');
        form.reset();
        
        // Redirect to home page after a delay
        setTimeout(() => {
            window.location.href = '/';
        }, 3000);
        
    } catch (error) {
        console.error('Error submitting job:', error);
        showAlert('danger', 'Failed to submit job. Please check your information and try again.');
    } finally {
        submitButton.disabled = false;
        submitButton.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit Job Post';
    }
}

function validateForm(): boolean {
    let isValid = true;
    
    // Required fields validation
    const requiredFields = [
        { id: 'title', message: 'Job title is required' },
        { id: 'company', message: 'Company name is required' },
        { id: 'location', message: 'Location is required' },
        { id: 'employmentType', message: 'Employment type is required' },
        { id: 'field', message: 'Field is required' },
        { id: 'email', message: 'Contact email is required' },
        { id: 'description', message: 'Job description is required' }
    ];
    
    requiredFields.forEach(({ id, message }) => {
        const field = document.getElementById(id) as HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement;
        if (field && !field.value.trim()) {
            showFieldError(field, message);
            isValid = false;
        }
    });
    
    // Email validation
    const emailField = document.getElementById('email') as HTMLInputElement;
    if (emailField && emailField.value && !isValidEmail(emailField.value)) {
        showFieldError(emailField, 'Please enter a valid email address');
        isValid = false;
    }
    
    // Description length validation
    const descriptionField = document.getElementById('description') as HTMLTextAreaElement;
    if (descriptionField && descriptionField.value.trim().length < 10) {
        showFieldError(descriptionField, 'Description must be at least 10 characters long');
        isValid = false;
    }
    
    return isValid;
}

function validateField(field: HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement): boolean {
    if (!field.value.trim()) {
        showFieldError(field, 'This field is required');
        return false;
    }
    
    clearFieldError(field);
    return true;
}

function validateEmail(emailField: HTMLInputElement): boolean {
    const email = emailField.value.trim();
    if (!email) {
        showFieldError(emailField, 'Email is required');
        return false;
    }
    
    if (!isValidEmail(email)) {
        showFieldError(emailField, 'Please enter a valid email address');
        return false;
    }
    
    clearFieldError(emailField);
    return true;
}

function isValidEmail(email: string): boolean {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showFieldError(field: HTMLElement, message: string) {
    field.classList.add('is-invalid');
    
    const feedback = field.parentElement?.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.textContent = message;
    }
}

function clearFieldError(field: HTMLElement) {
    field.classList.remove('is-invalid');
    
    const feedback = field.parentElement?.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.textContent = '';
    }
}

function clearAllErrors() {
    document.querySelectorAll('.is-invalid').forEach(field => {
        field.classList.remove('is-invalid');
    });
    
    document.querySelectorAll('.invalid-feedback').forEach(feedback => {
        feedback.textContent = '';
    });
    
    const alertContainer = document.getElementById('alertContainer');
    if (alertContainer) {
        alertContainer.innerHTML = '';
    }
}

function showAlert(type: 'success' | 'danger', message: string) {
    const alertContainer = document.getElementById('alertContainer');
    if (!alertContainer) return;
    
    alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Scroll to alert
    alertContainer.scrollIntoView({ behavior: 'smooth' });
}