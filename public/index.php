<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <meta name="description" content="Student Management System - CRUD Operations for Integrative Programming Technologies">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand" id="logo-container">
            <img src="images/logo.svg" id="logo" alt="SMS Logo">
            <span class="nav-title">Student Management</span>
        </div>
        <div class="nav-buttons">
            <button class="navbarbuttons" onclick="showSection('create')">Create</button>
            <button class="navbarbuttons" onclick="showSection('read')">Read</button>
            <button class="navbarbuttons" onclick="showSection('update')">Update</button>
            <button class="navbarbuttons" onclick="showSection('delete')">Delete</button>
        </div>
    </nav>

    <!-- HOME SECTION -->
    <section id="home" class="homecontent">
        <h1 class="splash">Student Management System</h1>
        <h2 class="splash">A Project in Integrative Programming Technologies</h2>

    </section>

    <!-- CREATE SECTION -->
    <section id="create" class="content">
        <h1 class="contenttitle">Insert New Student</h1>
        <form action="includes/insert.php" method="POST">
            <label for="create_surname" class="label">Surname</label>
            <input type="text" name="surname" id="create_surname" class="field name-field" pattern="[A-Za-z\s]+" title="Letters and spaces only" required><br/>

            <label for="create_name" class="label">Name</label>
            <input type="text" name="name" id="create_name" class="field name-field" pattern="[A-Za-z\s]+" title="Letters and spaces only" required><br/>

            <label for="create_middlename" class="label">Middle name</label>
            <input type="text" name="middlename" id="create_middlename" class="field name-field" pattern="[A-Za-z\s]*" title="Letters and spaces only"><br/>

            <label for="create_address" class="label">Address</label>
            <input type="text" name="address" id="create_address" class="field"><br/>

            <label for="create_contact" class="label">Mobile Number</label>
            <input type="text" name="contact" id="create_contact" class="field contact-field" pattern="[0-9]*" title="Numbers only" maxlength="20"><br/>

            <div id="btncontainer">
                <button type="button" id="clrbtn" class="btns" onclick="clearFields()">Clear Fields</button>
                <button type="submit" id="savebtn" class="btns">Save</button>
            </div>
        </form>
    </section>

    <!-- READ SECTION -->
    <section id="read" class="content">
        <h1 class="contenttitle">View Students</h1>
        <div id="students-table-container">
            <table id="students-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Surname</th>
                        <th>Name</th>
                        <th>Middle Name</th>
                        <th>Address</th>
                        <th>Contact Number</th>
                    </tr>
                </thead>
                <tbody id="students-tbody">
                    <!-- Data will be loaded via JavaScript -->
                </tbody>
            </table>
            <p id="no-data-msg" style="display:none; text-align:center; color:#888;">No student records found.</p>
        </div>
        <div id="btncontainer">
           
        </div>
    </section>

    <!-- UPDATE SECTION -->
    <section id="update" class="content">
        <h1 class="contenttitle">Update Student Record</h1>

        <div id="update-search">
            <label for="update_search_id" class="label">Student ID</label>
            <input type="number" id="update_search_id" class="field" placeholder="Enter Student ID"><br/>
            <div id="btncontainer">
                <button type="button" class="btns" onclick="fetchStudentForUpdate()">Search</button>
            </div>
        </div>

        <form id="update-form" action="includes/update_data.php" method="POST" style="display:none;">
            <input type="hidden" name="id" id="update_id">

            <label for="update_surname" class="label">Surname</label>
            <input type="text" name="surname" id="update_surname" class="field name-field" pattern="[A-Za-z\s]+" title="Letters and spaces only" required><br/>

            <label for="update_name" class="label">Name</label>
            <input type="text" name="name" id="update_name" class="field name-field" pattern="[A-Za-z\s]+" title="Letters and spaces only" required><br/>

            <label for="update_middlename" class="label">Middle name</label>
            <input type="text" name="middlename" id="update_middlename" class="field name-field" pattern="[A-Za-z\s]*" title="Letters and spaces only"><br/>

            <label for="update_address" class="label">Address</label>
            <input type="text" name="address" id="update_address" class="field"><br/>

            <label for="update_contact" class="label">Mobile Number</label>
            <input type="text" name="contact" id="update_contact" class="field contact-field" pattern="[0-9]*" title="Numbers only" maxlength="20"><br/>

            <div id="btncontainer">
                <button type="button" class="btns" onclick="clearUpdateFields()">Clear Fields</button>
                <button type="submit" id="updatebtn" class="btns">Update</button>
            </div>
        </form>
        <p id="update-error" style="display:none; color:red; margin-top:10px;"></p>
    </section>

    <!-- DELETE SECTION -->
    <section id="delete" class="content">
        <h1 class="contenttitle">Delete Student Record</h1>

        <div id="delete-search">
            <label for="delete_search_id" class="label">Student ID</label>
            <input type="number" id="delete_search_id" class="field" placeholder="Enter Student ID"><br/>
            <div id="btncontainer">
                <button type="button" class="btns" onclick="fetchStudentForDelete()">Search</button>
            </div>
        </div>

        <div id="delete-preview" style="display:none;">
            <table id="delete-preview-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Surname</th>
                        <th>Name</th>
                        <th>Middle Name</th>
                        <th>Address</th>
                        <th>Contact Number</th>
                    </tr>
                </thead>
                <tbody id="delete-preview-tbody"></tbody>
            </table>
            <form action="includes/delete_data.php" method="POST">
                <input type="hidden" name="id" id="delete_id">
                <div id="btncontainer">
                    <button type="submit" class="btns btn-danger" onclick="return confirm('Are you sure you want to delete this student?')">Delete</button>
                </div>
            </form>
        </div>
        <p id="delete-error" style="display:none; color:red; margin-top:10px;"></p>
    </section>

    <!-- Toast notifications -->
    <div id="success-toast" class="toast-hidden">
        Operation Successful!
    </div>

    <script src="script.js"></script>
</body>
</html>
