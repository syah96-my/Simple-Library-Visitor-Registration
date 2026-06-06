<?php

session_start();
header('Content-Type: text/html');
include_once '../../config/config.php';
include_once '../../sessions/session.php';

if (!isLoggedIn()) {
    header('Location: ' . $base_url . '/views/admin/log-masuk.php');
    exit();
}

// Function to read locations from DB
function readLocations() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM locations");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function readUsers() {
    global $pdo;  // Use your existing PDO connection

    try {
        $stmt = $pdo->query("SELECT account_id, username, role FROM accounts ORDER BY username ASC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    } catch (PDOException $e) {
        // Handle error - in production, better logging
        error_log("Database error in readUsers(): " . $e->getMessage());
        return [];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Interface - Dark Theme</title>
    <!-- Bulma CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css" />
    <link rel="stylesheet" href="../../assets/css/main-visitor.css" />
    <link rel="stylesheet" href="../../assets/css/icon.css" />
    <!-- Iconify Icons -->
    <script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>
    <style>
        /* Dark theme base */
        body {
            background-color: #121212; /* Very dark gray / almost black */
            color: #e0e0e0; /* Light gray text */
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
            margin: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            width: 400px;
            background-color: #1e1e1e; /* Darker container */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.8);
            margin-top: 20px;
            color: #e0e0e0;
        }
        .tab-content {
            margin-top: 20px;
            padding: 15px;
            background: #2c2c2c; /* Slightly lighter than container */
            border-radius: 5px;
            color: #e0e0e0;
        }
        .is-hidden {
            display: none;
        }
        .tabs a {
            cursor: pointer;
            color: #ccc;
        }
        .tabs .is-active a {
            color: #3273dc; /* Bulma blue highlight */
            font-weight: bold;
            border-bottom: 2px solid #3273dc;
        }
        input.input,
        textarea.textarea {
            background-color: #333;
            color: #e0e0e0;
            border: 1px solid #555;
        }
        input.input::placeholder,
        textarea.textarea::placeholder {
            color: #999;
        }
        input.input:focus,
        textarea.textarea:focus {
            background-color: #444;
            border-color: #3273dc;
            color: #fff;
            outline: none;
        }
        button.button {
            background-color: #3273dc;
            color: white;
            border: none;
        }
        button.button.is-danger {
            background-color: #cc3333;
        }
        button.button.is-info {
            background-color: #209cee;
        }
        button.button.is-success {
            background-color: #23d160;
        }
        button.button:hover {
            filter: brightness(1.1);
        }
        table {
            color: #e0e0e0;
            border-color: #555;
        }
        table thead {
            background-color: #444;
            color: #ccc;
        }
        table tbody tr:hover {
            background-color: #555;
        }
        table td, table th {
            border: 1px solid #555;
            padding: 8px 10px;
        }
        /* Color square */
        td span {
            font-size: 1.5rem;
        }
        /* Smaller margin for buttons in table */
        table .button {
            margin-right: 4px;
        }
        
.input, .select {
  max-width: 300px;
}
.stat-head {
/* Center text vertically */
   background-color: #00ffff !important;
        /* Add some padding for spacing */

}
.stat-head th {
  text-align: center !important;   /* Center text horizontally */
  vertical-align: middle !important;   /* Center text vertically */

  padding: 10px;            /* Add some padding for spacing */

}
    </style>
</head>
<body>
<div class="container has-text-centered">
    <nav class="tabs is-boxed is-centered">
        <ul>
            <li id="statisticTab" class="is-active"><a onclick="showTab('Statistic')">Statistic</a></li>
            <li id="locationTab"><a onclick="showTab('Location')">Location</a></li>
            <li id="profileTab"><a onclick="showTab('Profile')">Profile</a></li>
        </ul>
    </nav>

    <div id="Statistic" class="tab-content">
            <h2 class="title">Statistics</h2>
    <div class="mb-3">
        <label for="yearSelect">Select Year:</label>
        <select id="yearSelect">
            <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
            <?php endfor; ?>
        </select>
    </div>

    <table class="table is-fullwidth is-striped is-hoverable">
        <thead class="stat-head">
            <tr>
                <th>Month</th>
                <th>Location</th>
                <th>Adult</th>
                <th>Child</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody id="statisticsTableBody">
            <!-- Data will be loaded here dynamically -->
        </tbody>
    </table>
    </div>

    <div id="Location" class="tab-content is-hidden">
        <h2 class="title">Locations</h2>
<input type="hidden" id="editingLocationId" value="">

<input id="newLocationName" class="input mb-2" type="text" placeholder="Location Name">
<textarea id="newLocationDescription" class="textarea mb-2" rows="4" placeholder="Location Description"></textarea>
<input id="newLocationColor" class="input mb-2" type="color" value="#000000">

<button id="submitLocationBtn" onclick="submitLocation()" class="button is-success mb-2">Add Location</button>
<button id="cancelEditBtn" onclick="cancelEdit()" class="button is-warning mb-2 is-hidden">Cancel Edit</button>

        
        
        

        <table class="table is-fullwidth is-striped is-hoverable">
            <thead class="stat-head">
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Color</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (readLocations() as $location): ?>
                <tr>
                    <td><?php echo htmlspecialchars($location['name']); ?></td>
                    <td><?php echo htmlspecialchars($location['description']); ?></td>
                    <td>
                        <span style="color: <?php echo htmlspecialchars($location['color']); ?>;">■</span>
                    </td>
                    <td>
                        <button
    class="button is-small is-info"
    onclick="editLocation(
        <?php echo $location['location_id']; ?>,
        '<?php echo addslashes(htmlspecialchars($location['name'])); ?>',
        '<?php echo addslashes(htmlspecialchars($location['description'])); ?>',
        '<?php echo $location['color']; ?>'
    )"
>
    Edit
</button>

                        <button class="button is-small is-danger" onclick="deleteLocation(<?php echo $location['location_id']; ?>)">Delete</button>
                        <button class="button is-small" onclick="viewLocation(<?php echo $location['location_id']; ?>)">View</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <div id="Profile" class="tab-content is-hidden">
    <h2 class="title">User Management</h2>
    
    <input type="hidden" id="editingUserId" value="">

    <input id="newUsername" class="input mb-2" type="text" placeholder="Username" />
    <input id="newPassword" class="input mb-2" type="password" placeholder="Password" />
    
    <div class="select mb-2">
        <select id="newRole">
            <option value="admin">Admin</option>
            <option value="superadmin">Super Admin</option>
        </select>
    </div>
    
    <button id="submitUserBtn" onclick="submitUser()" class="button is-success mb-2">Add User</button>
    <button id="cancelEditBtn" onclick="cancelEditUser()" class="button is-warning mb-2 is-hidden">Cancel Edit</button>

    <table class="table is-fullwidth is-striped is-hoverable">
        <thead class="stat-head">
            <tr>
                <th>Username</th>
                <th>Password (hashed)</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
<tbody id="usersTableBody">
    <?php foreach (readUsers() as $user): ?>
    <tr data-user-id="<?php echo $user['account_id']; ?>">
        <td><?php echo htmlspecialchars($user['username']); ?></td>
        <td>••••••••</td> <!-- never show actual password -->
        <td><?php echo htmlspecialchars($user['role']); ?></td>
        <td>
            <button
                class="button is-small is-info"
                onclick="editUser(
                    <?php echo $user['account_id']; ?>,
                    '<?php echo addslashes(htmlspecialchars($user['username'])); ?>',
                    '<?php echo $user['role']; ?>'
                )"
            >
                Edit
            </button>

            <button class="button is-small is-danger" onclick="deleteUser(<?php echo $user['account_id']; ?>)">Delete</button>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
    </table>
</div>
<a href="../../logout.php" style="
  display: inline-block;
  padding: 8px 16px;
  background-color: #f44336;
  color: white;
  text-decoration: none;
  border-radius: 4px;
  font-weight: bold;
  cursor: pointer;
">Log Out</a>

</div>

<script>
function showTab(tabName) {
    const tabs = ['Statistic', 'Location', 'Profile'];
    tabs.forEach((tab) => {
        document.getElementById(tab).classList.add('is-hidden');
        document.getElementById(tab.toLowerCase() + 'Tab').classList.remove('is-active');
    });
    document.getElementById(tabName).classList.remove('is-hidden');
    document.getElementById(tabName.toLowerCase() + 'Tab').classList.add('is-active');
}

// Called on Add or Update button click
async function submitLocation() {
    const id = document.getElementById('editingLocationId').value;
    const name = document.getElementById('newLocationName').value.trim();
    const description = document.getElementById('newLocationDescription').value.trim();
    const color = document.getElementById('newLocationColor').value;

    if (!name) {
        alert('Please enter a location name.');
        return;
    }

    const csrfToken = await getCsrfToken();
    if (!csrfToken) return;

    const action = id ? 'edit' : 'add';
    const payload = { action, name, description, color, csrf_token: csrfToken };
    if (id) payload.id = id;

    const response = await fetch('../../controllers/location_api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });
    const result = await response.json();

    if (result.status === 'success') {
        alert(result.message);
        updateLocationTable();
        cancelEdit();
    } else {
        alert('Error: ' + result.message);
    }
}


    // Find the location's <li> or from DOM (better to have a data attribute or from backend reload)
    // But here we parse from the DOM list for simplicity:
    
function editLocation(id, name, description, color) {
    document.getElementById('newLocationName').value = name;
    document.getElementById('newLocationDescription').value = description;
    document.getElementById('newLocationColor').value = color;

    document.getElementById('editingLocationId').value = id;

    document.getElementById('submitLocationBtn').textContent = 'Update Location';
    document.getElementById('cancelEditBtn').classList.remove('is-hidden');

    document.getElementById('newLocationName').scrollIntoView({ behavior: 'smooth' });
}


function cancelEdit() {
    document.getElementById('editingLocationId').value = '';
    document.getElementById('newLocationName').value = '';
    document.getElementById('newLocationDescription').value = '';
    document.getElementById('newLocationColor').value = '#000000';

    document.getElementById('submitLocationBtn').textContent = 'Add Location';
    document.getElementById('cancelEditBtn').classList.add('is-hidden');
}

// Helper: convert rgb(a) color string to hex (#rrggbb)
function rgbToHex(rgb) {
    if (!rgb) return '#000000';
    if (rgb.startsWith('#')) return rgb; // Already hex

    // rgb or rgba format: "rgb(255, 0, 0)" or "rgba(255, 0, 0, 1)"
    const rgbValues = rgb.match(/\d+/g);
    if (!rgbValues || rgbValues.length < 3) return '#000000';

    return '#' + rgbValues.slice(0,3).map(x => {
        const hex = parseInt(x).toString(16);
        return hex.length === 1 ? '0' + hex : hex;
    }).join('');
}

async function deleteLocation(id) {
    if (!confirm('Are you sure you want to delete this location?')) return;

    const csrfToken = await getCsrfToken();
    if (!csrfToken) return;

    const response = await fetch('../../controllers/location_api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'delete', id, csrf_token: csrfToken })
    });
    const result = await response.json();

    if (result.status === 'success') {
        alert(result.message);
        updateLocationTable();
    } else {
        alert('Error: ' + result.message);
    }
}

async function updateLocationTable() {
    const csrfToken = await getCsrfToken();
    if (!csrfToken) return;

    const response = await fetch('../../controllers/location_api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'list', csrf_token: csrfToken })
    });
    const result = await response.json();

    if (result.status === 'success') {
        const tbody = document.querySelector('#Location tbody');
        tbody.innerHTML = ''; // Clear existing rows

        result.locations.forEach(location => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${escapeHtml(String(location.name || ''))}</td>
                <td>${escapeHtml(String(location.description || ''))}</td>
                <td><span style="color: ${location.color};">■</span></td>
                <td>
                    <button class="button is-small is-info" onclick="editLocation(${Number(location.location_id)}, '${escapeJs(String(location.name || ''))}', '${escapeJs(String(location.description || ''))}', '${escapeJs(String(location.color || '#000000'))}')">Edit</button>
                    <button class="button is-small is-danger" onclick="deleteLocation(${location.location_id})">Delete</button>
                    <button class="button is-small" onclick="viewLocation(${location.location_id})">View</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } else {
        alert('Error: ' + result.message);
    }
}


//user
// Function to fetch CSRF token from the session
async function getCsrfToken() {
    try {
        const response = await fetch('../../controllers/get_csrf.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        if (!response.ok) throw new Error('Failed to fetch CSRF token');
        const data = await response.json();
        return data.csrf_token;
    } catch (err) {
        console.error('Error fetching CSRF token:', err);
        alert('Failed to load CSRF token.');
        return null;
    }
}

// Create user row
function createUserRow(user) {
    const tr = document.createElement('tr');
    tr.setAttribute('data-user-id', user.id);
    tr.innerHTML = `
        <td>${escapeHtml(user.username)}</td>
        <td>••••••••</td>
        <td>${escapeHtml(user.role)}</td>
        <td>
            <button class="button is-small is-info" onclick="editUser(${user.id}, '${escapeJs(user.username)}', '${escapeJs(user.role)}')">Edit</button>
            <button class="button is-small is-danger" onclick="deleteUser(${user.id})">Delete</button>
        </td>
    `;
    return tr;
}

// Escape helper functions for security
function escapeHtml(text) {
    return text.replace(/[&<>"']/g, m => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    })[m]);
}

function escapeJs(text) {
    return text.replace(/'/g, "\\'").replace(/"/g, '\\"');
}

// Submit user (add/update)
async function submitUser() {
    const id = document.getElementById('editingUserId').value;
    const username = document.getElementById('newUsername').value.trim();
    const password = document.getElementById('newPassword').value;
    const role = document.getElementById('newRole').value;

    if (!username) {
        alert('Username required');
        return;
    }

    try {
        // Get CSRF token from the server
        const csrfToken = await getCsrfToken();
        if (!csrfToken) return;

        const action = id ? 'update' : 'add';
        const payload = { action, username, role, csrf_token: csrfToken };
        if (id) payload.id = id;
        if (password) payload.password = password;

        const response = await fetch('../../controllers/user_api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Server error ${response.status}: ${errorText || response.statusText}`);
        }

        const data = await response.json();
        alert(data.message);

        if (data.status === 'success') {
            const usersTableBody = document.getElementById('usersTableBody');
            if (action === 'add' && data.user) {
                usersTableBody.appendChild(createUserRow(data.user));
            } else if (action === 'update') {
                const row = usersTableBody.querySelector(`tr[data-user-id="${id}"]`);
                if (row) {
                    row.cells[0].textContent = username;
                    row.cells[2].textContent = role;
                }
            }
            cancelEditUser();
        }
    } catch (err) {
        console.error('Error submitting user:', err);
        alert('An error occurred while processing the user: ' + err.message);
    }
}

// Delete user
async function deleteUser(id) {
    if (!confirm('Are you sure you want to delete this user?')) return;

    try {
        // Get CSRF token from the server
        const csrfToken = await getCsrfToken();
        if (!csrfToken) return;

        const response = await fetch('../../controllers/user_api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ action: 'delete', id, csrf_token: csrfToken })
        });

        const data = await response.json();
        alert(data.message);

        if (data.status === 'success') {
            const usersTableBody = document.getElementById('usersTableBody');
            const row = usersTableBody.querySelector(`tr[data-user-id="${id}"]`);
            if (row) usersTableBody.removeChild(row);
        }
    } catch (err) {
        console.error('Error deleting user:', err);
        alert('An error occurred while deleting the user.');
    }
}


function editUser(id, username, role) {
    // Set the hidden input so submitUser knows this is an update
    document.getElementById('editingUserId').value = id;
    // Fill inputs with existing data
    document.getElementById('newUsername').value = username;
    document.getElementById('newPassword').value = ''; // clear password field for security
    document.getElementById('newRole').value = role;

    // Change the submit button to reflect update mode
    const submitBtn = document.getElementById('submitUserBtn');
    submitBtn.textContent = 'Update User';

    // Show the cancel edit button
    document.getElementById('cancelEditBtn').classList.remove('is-hidden');
}

function cancelEditUser() {
    // Clear the hidden input so submitUser knows this is an add operation
    document.getElementById('editingUserId').value = '';

    // Clear all form inputs
    document.getElementById('newUsername').value = '';
    document.getElementById('newPassword').value = '';
    document.getElementById('newRole').value = 'admin'; // or your default role

    // Reset buttons
    const submitBtn = document.getElementById('submitUserBtn');
    submitBtn.textContent = 'Add User';

    // Hide the cancel edit button
    document.getElementById('cancelEditBtn').classList.add('is-hidden');
}

async function loadStatistics(year) {
    try {
        // Get the CSRF token
        const csrfToken = await getCsrfToken();
        if (!csrfToken) return;

        // Fetch the statistics with the CSRF token in headers
        const response = await fetch(`../../controllers/visitor_api.php?year=${year}&csrf_token=${csrfToken}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) throw new Error(`Failed to fetch statistics: ${response.statusText}`);

        // Attempt to parse JSON response
        let result;
        try {
            result = await response.json();
        } catch {
            throw new Error('Invalid JSON response from server.');
        }

        if (result.status !== 'success') throw new Error(result.message);

        const data = result.data;
        const tableBody = document.getElementById('statisticsTableBody');
        tableBody.innerHTML = '';

        // Populate the statistics table
        data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.month}</td>
                <td>${row.location_name}</td>
                <td>${row.total_adult}</td>
                <td>${row.total_child}</td>
                <td>${row.total}</td>
            `;
            tableBody.appendChild(tr);
        });
    } catch (error) {
        console.error('Error loading statistics:', error);
        alert('Error loading statistics: ' + error.message);
    }
}

// Load statistics for the current year on page load
document.addEventListener('DOMContentLoaded', () => {
    const currentYear = new Date().getFullYear();
    loadStatistics(currentYear);

    // Event listener for year change
    const yearSelect = document.getElementById('yearSelect');
    yearSelect.addEventListener('change', () => {
        const selectedYear = yearSelect.value;
        loadStatistics(selectedYear);
    });
});

function viewLocation(id) {
  const encodedId = btoa(id); // encode id to base64
  window.open('qr-page.php?id=' + encodeURIComponent(encodedId), '_blank');
}


</script>
</body>
</html>
