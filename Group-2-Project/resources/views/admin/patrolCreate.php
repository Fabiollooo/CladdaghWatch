<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Patrol Schedule</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="icon" href="<?php echo asset('images/favicon.ico'); ?>">
    
    <style>
        body {
            background: linear-gradient(135deg, var(--color-primary) 0%, #2563eb 60%);
            min-height: 100vh;
        }
        .content-wrapper {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 500;
            color: #333;
        }
    </style>
</head>
<body>

<?php @require resource_path('views/templates/admin/adminheader.php');?>

<div class="container my-5">
    <div class="content-wrapper">
        <div class="mb-4">
            <a href="/patrolManagement" class="text-decoration-none text-secondary mb-3 d-inline-block">
                <i class="bi bi-arrow-left me-2"></i>Back to Schedule List
            </a>
            <h1 class="h2 fw-bold mt-3" style="color: var(--color-primary);">Create Patrol Schedule</h1>
            <p class="text-secondary mt-2">Schedule a new patrol date</p>
        </div>

        <form id="createForm">
            <div class="mb-4">
                <label class="form-label">Patrol Number <span class="text-danger">*</span></label>
                <input type="text" id="patrolNumber" class="form-control" placeholder="PTR-2026-001">
                <div class="form-text text-muted small">Auto-generated, but you can modify if needed</div>
                <div class="text-danger small mt-1 d-none" id="patrolNumberError"></div>
            </div>

            <div class="mb-4">
                <label class="form-label">Description <span class="text-danger">*</span></label>
                <textarea id="description" rows="4" class="form-control" placeholder="Enter patrol description including time (e.g., 'North District Morning Patrol at 08:00')"></textarea>
                <div class="form-text text-muted small">Please include the patrol time in the description</div>
                <div class="text-danger small mt-1 d-none" id="descriptionError"></div>
            </div>

            <div class="mb-4">
                <label class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" id="date" class="form-control">
                <div class="text-danger small mt-1 d-none" id="dateError"></div>
            </div>

            <div class="alert alert-info bg-light border-info" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Important Notes:</strong>
                <ul class="mt-2 mb-0 small">
                    <li>Only future dates can be selected</li>
                    <li>Patrol number must be unique</li>
                    <li>Include the patrol time in the description (e.g., "Morning Patrol at 08:00")</li>
                    <li>Save as Draft to review later, or Publish to make it live immediately</li>
                </ul>
            </div>
        </form>

        <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top">
            <a href="/patrolManagement" class="btn btn-outline-secondary px-4">Cancel</a>
            <button onclick="savePatrol('draft')" class="btn btn-outline-primary px-4">Save as Draft</button>
            <button onclick="savePatrol('published')" class="btn btn-primary px-4">Save & Publish</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date').min = today;
        generatePatrolNumber();
        
        ['patrolNumber', 'description', 'date'].forEach(id => {
            document.getElementById(id).addEventListener('input', function() {
                document.getElementById(id + 'Error').classList.add('d-none');
                this.classList.remove('is-invalid');
            });
        });
    });

    function generatePatrolNumber() {
        const num = Math.floor(Math.random() * 999) + 1;
        document.getElementById('patrolNumber').value = `PTR-2026-${String(num).padStart(3, '0')}`;
    }

    function validateForm() {
        let isValid = true;
        const fields = ['patrolNumber', 'description', 'date'];
        
        fields.forEach(field => {
            const input = document.getElementById(field);
            const error = document.getElementById(field + 'Error');
            const value = input.value.trim();
            
            if (!value) {
                error.textContent = `${field.charAt(0).toUpperCase() + field.slice(1)} is required`;
                error.classList.remove('d-none');
                input.classList.add('is-invalid');
                isValid = false;
            } else if (field === 'date' && new Date(value) < new Date().setHours(0,0,0,0)) {
                error.textContent = 'Date must be in the future';
                error.classList.remove('d-none');
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                error.classList.add('d-none');
                input.classList.remove('is-invalid');
            }
        });
        
        return isValid;
    }

    function savePatrol(status) {
        if (!validateForm()) return;
        
        const patrolData = {
            patrolNumber: document.getElementById('patrolNumber').value.trim(),
            description: document.getElementById('description').value.trim(),
            date: document.getElementById('date').value,
            status: status
        };
        
        console.log('Saving patrol:', patrolData);
        alert(`Patrol saved as ${status} successfully!`);
        window.location.href = '/patrolManagement';
    }
</script>

</body>
</html>