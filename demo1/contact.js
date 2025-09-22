// Contact form functionality for Demo1
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');

    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(contactForm);
            const data = Object.fromEntries(formData);

            // Show loading state
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Sending...';
            submitBtn.disabled = true;

            // Simulate form submission (in real app, this would be an API call)
            setTimeout(() => {
                // Show success message
                showNotification('Message sent successfully! We\'ll get back to you within 24 hours.', 'success');

                // Reset form
                contactForm.reset();

                // Reset button
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;

                // Log to database simulation
                logContactSubmission(data);

            }, 1500);
        });
    }

    // Form validation
    const requiredFields = contactForm.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', validateField);
        field.addEventListener('input', clearValidationError);
    });

    function validateField(e) {
        const field = e.target;
        const value = field.value.trim();

        // Remove existing error styling
        field.classList.remove('error');

        if (!value) {
            showFieldError(field, 'This field is required');
            return false;
        }

        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                showFieldError(field, 'Please enter a valid email address');
                return false;
            }
        }

        return true;
    }

    function clearValidationError(e) {
        const field = e.target;
        field.classList.remove('error');
        const errorMsg = field.parentNode.querySelector('.error-message');
        if (errorMsg) {
            errorMsg.remove();
        }
    }

    function showFieldError(field, message) {
        field.classList.add('error');

        // Remove existing error message
        const existingError = field.parentNode.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }

        // Add new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        errorDiv.style.color = '#ff6b6b';
        errorDiv.style.fontSize = '0.8rem';
        errorDiv.style.marginTop = '0.25rem';

        field.parentNode.appendChild(errorDiv);
    }

    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-icon">${type === 'success' ? '✅' : 'ℹ️'}</span>
                <span class="notification-message">${message}</span>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;

        // Style the notification
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? 'rgba(76, 175, 80, 0.9)' : 'rgba(33, 150, 243, 0.9)'};
            color: white;
            padding: 1rem;
            border-radius: 8px;
            backdrop-filter: blur(10px);
            z-index: 1000;
            max-width: 400px;
            animation: slideInRight 0.3s ease;
        `;

        // Add to page
        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    }

    function logContactSubmission(data) {
        // Simulate database logging
        const timestamp = new Date().toISOString();
        const logEntry = {
            timestamp,
            site: 'demo1',
            type: 'contact_form',
            data: {
                name: data.name,
                email: data.email,
                company: data.company || 'Not provided',
                subject: data.subject,
                newsletter: data.newsletter ? 'Yes' : 'No'
            }
        };

        console.log('📝 Contact form submission logged:', logEntry);

        // In a real application, this would be sent to the PostgreSQL database
        // Example: fetch('/api/contact', { method: 'POST', body: JSON.stringify(logEntry) })
    }

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .notification-content {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .notification-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            margin-left: auto;
            padding: 0 0.25rem;
        }

        .notification-close:hover {
            opacity: 0.7;
        }

        .form-group input.error,
        .form-group select.error,
        .form-group textarea.error {
            border-color: #ff6b6b;
            background: rgba(255, 107, 107, 0.1);
        }
    `;
    document.head.appendChild(style);
});

// Database connection simulator
function simulateDBConnection() {
    const dbStatus = document.querySelector('.db-status');
    if (dbStatus) {
        // Simulate connection check
        setTimeout(() => {
            const statusIndicator = document.createElement('div');
            statusIndicator.innerHTML = '🟢 Database Connected - PostgreSQL 15';
            statusIndicator.style.cssText = `
                background: rgba(76, 175, 80, 0.2);
                padding: 0.5rem;
                border-radius: 5px;
                margin-top: 0.5rem;
                font-size: 0.9rem;
            `;
            dbStatus.appendChild(statusIndicator);
        }, 1000);
    }
}

// Initialize database connection check
simulateDBConnection();