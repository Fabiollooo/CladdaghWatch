<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Edit Patrol Schedule</title>
    
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
        .badge-locked {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-published {
            background-color: #d1fae5;
            color: #065f46;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

<?php @require resource_path('views/templates/header.php');?>

<div class="container my-5">
    <div class="content-wrapper">
        <div class="mb-4">
            <a href="/patrolManagement" class="text-decoration-none text-secondary mb-3 d-inline-block">
                <i class="bi bi-arrow-left me-2"></i>Back to Schedule List
            </a>
            
            <div class="d-flex align-items-center gap-3 mt-3">
                <h1 class="h2 fw-bold" style="color: var(--color-primary);">Edit Patrol Schedule</h1>
                <span id="lockedBadge" class="badge-locked d-none">
                    <i class="bi bi-lock-fill me-1"></i>LOCKED
                </span>
                <span id="publishedBadge" class="badge-published d-none">
                    <i class="bi bi-check-circle-fill me-1"></i>PUBLISHED
                </span>
            </div>
            <p class="text-secondary mt-2">Modify existing patrol schedule details</p>
        </div>

        <div id="lockedWarning" class="alert alert-danger d-none" role="alert">
            <i class="bi bi-lock-fill me-2"></i>
            <strong>Restricted Access</strong> - This patrol is locked and can only be modified by Operations Managers.
        </div>

        <!-- Published Warning -->
        <div id="publishedWarning" class="alert alert-warning d-none" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Warning:</strong> This patrol is published. Changes will be visible immediately.
            <div class="mt-3">
                <button onclick="confirmPublishedEdit()" class="btn btn-warning btn-sm me-2">Yes, Save Changes</button>
                <button onclick="hidePublishedWarning()" class="btn btn-outline-warning btn-sm">Cancel</button>
            </div>
        </div>

        <!-- Form -->
        <form id="editForm">
            <input type="hidden" id="patrolId">
            
            <div class="mb-4">
                <label class="form-label">Patrol Number <span class="text-danger">*</span></label>
                <input type="text" id="patrolNumber" class="form-control">
                <div class="text-danger small mt-1 d-none" id="patrolNumberError"></div>
            </div>

            <div class="mb-4">
                <label class="form-label">Description <span class="text-danger">*</span></label>
                <textarea id="description" rows="4" class="form-control"></textarea>
                <div class="form-text text-muted small">Include patrol time in the description</div>
                <div class="text-danger small mt-1 d-none" id="descriptionError"></div>
            </div>

            <div class="mb-4">
                <label class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" id="date" class="form-control">
                <div class="text-danger small mt-1 d-none" id="dateError"></div>
            </div>

            <div class="alert alert-info bg-light border-info" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Edit Rules:</strong>
                <ul class="mt-2 mb-0 small">
                    <li>Only future patrols can be edited</li>
                    <li>Locked patrols require Operations Manager access</li>
                    <li>Published patrols update immediately</li>
                    <li>Patrol time should be included in the description</li>
                </ul>
            </div>
        </form>

        <!-- Buttons -->
        <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top">
            <a href="/patrolManagement" class="btn btn-outline-secondary px-4">Cancel</a>
            <button id="saveBtn" onclick="updatePatrol()" class="btn btn-primary px-4">Save Changes</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Updated sample data without time field
    const samplePatrols = {
        1: { id: 1, patrolNumber: 'PTR-2025-001', description: 'North District Morning Patrol at 08:00', date: '2026-02-15', status: 'published', isLocked: false },
        2: { id: 2, patrolNumber: 'PTR-2025-002', description: 'South District Evening Patrol at 18:00', date: '2026-02-20', status: 'draft', isLocked: false },
        3: { id: 3, patrolNumber: 'PTR-2025-003', description: 'Central District Night Watch at 22:00', date: '2026-01-10', status: 'published', isLocked: true }
    };

    let currentPatrol = null;

    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');
        
        if (!id) {
            alert('No patrol selected');
            window.location.href = '/patrolManagement';
            return;
        }
        
        loadPatrolData(id);
        document.getElementById('date').min = new Date().toISOString().split('T')[0];
        
        // Clear errors on input
        ['patrolNumber', 'description', 'date'].forEach(field => {
            document.getElementById(field).addEventListener('input', function() {
                document.getElementById(field + 'Error').classList.add('d-none');
                this.classList.remove('is-invalid');
            });
        });
    });

    function loadPatrolData(id) {
        const patrol = samplePatrols[id];
        if (!patrol) {
            alert('Patrol not found');
            window.location.href = '/patrolManagement';
            return;
        }
        
        currentPatrol = patrol;
        populateForm(patrol);
    }

    function populateForm(patrol) {
        document.getElementById('patrolId').value = patrol.id;
        document.getElementById('patrolNumber').value = patrol.patrolNumber;
        document.getElementById('description').value = patrol.description;
        document.getElementById('date').value = patrol.date;
        
        const isLocked = patrol.isLocked;
        const canEdit = !isLocked;
        
        document.getElementById('patrolNumber').disabled = !canEdit;
        document.getElementById('description').disabled = !canEdit;
        document.getElementById('date').disabled = !canEdit;
        document.getElementById('saveBtn').disabled = !canEdit;
        
        if (!canEdit) {
            document.getElementById('saveBtn').classList.remove('btn-primary');
            document.getElementById('saveBtn').classList.add('btn-secondary', 'disabled');
            document.getElementById('lockedBadge').classList.remove('d-none');
            document.getElementById('lockedWarning').classList.remove('d-none');
        }
        
        if (patrol.status === 'published') {
            document.getElementById('publishedBadge').classList.remove('d-none');
        }
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

    function updatePatrol() {
        if (!currentPatrol) return;
        
        if (currentPatrol.status === 'published' && document.getElementById('publishedWarning').classList.contains('d-none')) {
            document.getElementById('publishedWarning').classList.remove('d-none');
            return;
        }
        
        if (!validateForm()) return;
        
        const updatedData = {
            id: currentPatrol.id,
            patrolNumber: document.getElementById('patrolNumber').value.trim(),
            description: document.getElementById('description').value.trim(),
            date: document.getElementById('date').value,
            status: currentPatrol.status
        };
        
        console.log('Updating patrol:', updatedData);
        alert('Patrol updated successfully!');
        window.location.href = '/patrolManagement';
    }

    function confirmPublishedEdit() {
        document.getElementById('publishedWarning').classList.add('d-none');
        updatePatrol();
    }

    function hidePublishedWarning() {
        document.getElementById('publishedWarning').classList.add('d-none');
    }
</script>

</body>
</html>