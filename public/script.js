// =============================================
// SECTION SHOW/HIDE LOGIC
// =============================================

// Show the selected section and hide all others
// Per requirements:
// Create -> shows 'create', hides 'home', 'read', 'update', 'delete'
// Read   -> shows 'read',   hides 'home', 'create', 'update', 'delete'
// Update -> shows 'update', hides 'home', 'create', 'read', 'delete'
// Delete -> shows 'delete', hides 'home', 'create', 'read', 'update'

function showSection(sectionID) {
    // Hide all content sections
    const sections = document.querySelectorAll('.content');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    // Hide home section
    const homeSection = document.getElementById('home');
    if (homeSection) {
        homeSection.style.display = 'none';
    }

    // Show the selected section
    const activeSection = document.getElementById(sectionID);
    if (activeSection) {
        activeSection.style.display = 'block';
    }

    // Update active button state
    const buttons = document.querySelectorAll('.navbarbuttons');
    buttons.forEach(btn => btn.classList.remove('active'));
    // Find the button whose text matches the sectionID
    buttons.forEach(btn => {
        if (btn.textContent.trim().toLowerCase() === sectionID) {
            btn.classList.add('active');
        }
    });

    // If showing read section, auto-load students
    if (sectionID === 'read') {
        loadStudents();
    }
}

// =============================================
// LOGO CLICK - Hide all content sections
// =============================================

document.addEventListener('DOMContentLoaded', function () {
    // Clicking logo or the entire nav-brand area goes home
    const logoContainer = document.getElementById('logo-container');
    const logo = document.getElementById('logo');
    
    function goHome() {
        // Hide all sections with class 'content'
        const sections = document.querySelectorAll('.content');
        sections.forEach(section => {
            section.style.display = 'none';
        });

        // Show home section
        const homeSection = document.getElementById('home');
        if (homeSection) {
            homeSection.style.display = 'block';
        }

        // Remove active state from all nav buttons
        const buttons = document.querySelectorAll('.navbarbuttons');
        buttons.forEach(btn => btn.classList.remove('active'));
    }

    if (logoContainer) {
        logoContainer.addEventListener('click', goHome);
    }
    if (logo) {
        logo.addEventListener('click', goHome);
    }
});

// =============================================
// INPUT VALIDATION - Real-time blocking
// =============================================

// Name fields: block numbers (allow letters, spaces, hyphens, apostrophes)
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('name-field')) {
        e.target.value = e.target.value.replace(/[0-9]/g, '');
    }
});

// Contact fields: block letters (allow only digits), max 20
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('contact-field')) {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
        if (e.target.value.length > 20) {
            e.target.value = e.target.value.slice(0, 20);
        }
    }
});

// =============================================
// CLEAR FIELDS FUNCTION
// =============================================

function clearFields() {
    // Clear all text and number inputs in the create form
    const createSection = document.getElementById('create');
    if (createSection) {
        const inputs = createSection.querySelectorAll('input[type="text"], input[type="number"]');
        inputs.forEach(input => {
            input.value = '';
        });
    }
}

function clearUpdateFields() {
    const updateForm = document.getElementById('update-form');
    if (updateForm) {
        const inputs = updateForm.querySelectorAll('input[type="text"], input[type="number"]');
        inputs.forEach(input => {
            input.value = '';
        });
    }
}

// =============================================
// READ - Load all students into table
// =============================================

function loadStudents() {
    fetch('includes/read_data.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('students-tbody');
            const noDataMsg = document.getElementById('no-data-msg');
            tbody.innerHTML = '';

            if (data.error) {
                noDataMsg.textContent = 'Error: ' + data.error;
                noDataMsg.style.display = 'block';
                return;
            }

            if (data.length === 0) {
                noDataMsg.style.display = 'block';
            } else {
                noDataMsg.style.display = 'none';
                data.forEach(student => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${student.id}</td>
                        <td>${student.surname}</td>
                        <td>${student.name}</td>
                        <td>${student.middlename || ''}</td>
                        <td>${student.address || ''}</td>
                        <td>${student.contact_number || ''}</td>
                    `;
                    tbody.appendChild(row);
                });
            }
        })
        .catch(err => {
            console.error('Error loading students:', err);
        });
}

// =============================================
// UPDATE - Fetch student by ID and populate form
// =============================================

function fetchStudentForUpdate() {
    const idInput = document.getElementById('update_search_id');
    const id = idInput.value.trim();
    const errorMsg = document.getElementById('update-error');
    const updateForm = document.getElementById('update-form');

    errorMsg.style.display = 'none';

    if (!id) {
        errorMsg.textContent = 'Please enter a Student ID.';
        errorMsg.style.display = 'block';
        return;
    }

    fetch('includes/update_data.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                errorMsg.textContent = data.error;
                errorMsg.style.display = 'block';
                updateForm.style.display = 'none';
            } else {
                document.getElementById('update_id').value = data.id;
                document.getElementById('update_surname').value = data.surname || '';
                document.getElementById('update_name').value = data.name || '';
                document.getElementById('update_middlename').value = data.middlename || '';
                document.getElementById('update_address').value = data.address || '';
                document.getElementById('update_contact').value = data.contact_number || '';
                updateForm.style.display = 'block';
            }
        })
        .catch(err => {
            errorMsg.textContent = 'Error fetching student data.';
            errorMsg.style.display = 'block';
            console.error(err);
        });
}

// =============================================
// DELETE - Fetch student by ID and show preview
// =============================================

function fetchStudentForDelete() {
    const idInput = document.getElementById('delete_search_id');
    const id = idInput.value.trim();
    const errorMsg = document.getElementById('delete-error');
    const preview = document.getElementById('delete-preview');

    errorMsg.style.display = 'none';

    if (!id) {
        errorMsg.textContent = 'Please enter a Student ID.';
        errorMsg.style.display = 'block';
        return;
    }

    fetch('includes/update_data.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                errorMsg.textContent = data.error;
                errorMsg.style.display = 'block';
                preview.style.display = 'none';
            } else {
                const tbody = document.getElementById('delete-preview-tbody');
                tbody.innerHTML = `
                    <tr>
                        <td>${data.id}</td>
                        <td>${data.surname}</td>
                        <td>${data.name}</td>
                        <td>${data.middlename || ''}</td>
                        <td>${data.address || ''}</td>
                        <td>${data.contact_number || ''}</td>
                    </tr>
                `;
                document.getElementById('delete_id').value = data.id;
                preview.style.display = 'block';
            }
        })
        .catch(err => {
            errorMsg.textContent = 'Error fetching student data.';
            errorMsg.style.display = 'block';
            console.error(err);
        });
}

// =============================================
// TOAST NOTIFICATION ON PAGE LOAD
// =============================================

window.onload = function () {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    if (status === 'success' || status === 'updated' || status === 'deleted') {
        const toast = document.getElementById('success-toast');
        
        if (status === 'success') toast.textContent = 'Student Added Successfully!';
        if (status === 'updated') toast.textContent = 'Student Updated Successfully!';
        if (status === 'deleted') toast.textContent = 'Student Deleted Successfully!';

        toast.classList.remove('toast-hidden');

        // Hide it automatically after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.classList.add('toast-hidden'), 500);
        }, 3000);

        // Clean the URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
};
