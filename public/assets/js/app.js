// Progress Tracker Game Dev - Main JavaScript

// Toggle Task Done
function toggleTask(taskId, isChecked) {
    // Disable checkbox while processing
    const checkbox = document.querySelector(`input[onchange*="${taskId}"]`);
    if (!checkbox) {
        console.error('Checkbox not found for task ID:', taskId);
        return;
    }
    checkbox.disabled = true;
    
    const formData = new FormData();
    formData.append('task_id', taskId);
    
    fetch('/tasks/' + taskId + '/update-status', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        checkbox.disabled = false;
        
        if (data.success) {
            // Update UI
            const taskItem = checkbox.closest('.task-item');
            if (taskItem) {
                if (data.is_done) {
                    taskItem.classList.add('task-done');
                } else {
                    taskItem.classList.remove('task-done');
                }
            }
            
            // Update progress if exists
            const progressFills = document.querySelectorAll('.progress-fill');
            progressFills.forEach(progressFill => {
                if (data.progress !== undefined) {
                    progressFill.style.width = data.progress + '%';
                }
            });
            
            const progressTexts = document.querySelectorAll('.progress-text');
            progressTexts.forEach(progressText => {
                if (data.progress !== undefined) {
                    progressText.textContent = Math.round(data.progress) + '%';
                }
            });
            
            const progressPercentages = document.querySelectorAll('.progress-percentage');
            progressPercentages.forEach(progressPercentage => {
                if (data.progress !== undefined) {
                    progressPercentage.textContent = Math.round(data.progress) + '% Complete';
                }
            });
        } else {
            // Revert checkbox if failed
            checkbox.checked = !isChecked;
            alert('Gagal update task: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        checkbox.disabled = false;
        checkbox.checked = !isChecked;
        alert('Error updating task. Please check console for details.');
    });
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Confirm delete actions
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this?')) {
                e.preventDefault();
            }
        });
    });
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Auto-save form data to localStorage (optional)
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const formId = form.getAttribute('id') || form.getAttribute('action');
        
        // Load saved data
        const savedData = localStorage.getItem(`form_${formId}`);
        if (savedData) {
            try {
                const data = JSON.parse(savedData);
                Object.keys(data).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input && !input.type || input.type === 'text' || input.type === 'textarea' || input.type === 'email') {
                        input.value = data[key];
                    }
                });
            } catch (e) {
                // Ignore parse errors
            }
        }
        
        // Save on input
        form.addEventListener('input', function() {
            const formData = new FormData(form);
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            localStorage.setItem(`form_${formId}`, JSON.stringify(data));
        });
        
        // Clear on submit
        form.addEventListener('submit', function() {
            localStorage.removeItem(`form_${formId}`);
        });
    });
});


