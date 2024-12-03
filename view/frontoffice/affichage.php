<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <style>
        .navbar-custom {
            background-color: #4CAF50;
            padding: 10px 0;
        }
        .navbar-custom .navbar-brand img {
            margin-top: -17px; 
            width: 60px;
            height: 60px;
        }
        .navbar-custom .navbar-text {
            color: #fff;
            font-size: 48px;
            margin-left: 400px;
        }
        .table-custom {
            margin-top: 120px; 
        }
        .table-custom th, .table-custom td {
            text-align: center;
            vertical-align: middle;
        }
        .btn-custom {
            margin: 0 5px;
        }
        .ml-auto {
            margin-left: 1100px;
        }
        .voice-recognition {
            margin-top: 20px;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function fetchUsers(filters = {}) {
                const params = new URLSearchParams(filters);
                fetch(`search.php?${params.toString()}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(users => {
                        console.log('Users fetched:', users); // Log the fetched users
                        const userList = document.getElementById('userList');
                        userList.innerHTML = ''; // Clear previous results
                        users.forEach(user => {
                            const userItem = document.createElement('tr');
                            userItem.innerHTML = `
                                <td>${user.cin}</td>
                                <td>${user.nom}</td>
                                <td>${user.prenom}</td>
                                <td>${user.numero}</td>
                                <td>${user.pwd}</td>
                                <td>${user.role == 1 ? 'Admin' : 'User'}</td>
                                <td>${user.mail}</td>
                                <td>${user.statut == 0 ? 'Active' : 'Blocked'}</td>
                                <td>
                                    <a href='delete.php?cin=${user.cin}' class='btn btn-danger btn-sm btn-custom' onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                    <a href='edit.php?cin=${user.cin}' class='btn btn-primary btn-sm btn-custom'>Edit</a>
                                </td>
                            `;
                            userList.appendChild(userItem);
                        });
                    })
                    .catch(error => console.error('Error fetching users:', error));
            }

            document.getElementById('searchForm').addEventListener('submit', function(event) {
                event.preventDefault();
                const filters = {
                    role: document.getElementById('role').value,
                    status: document.getElementById('status').value,
                    keyword: document.getElementById('keyword').value
                };
                console.log('Filters applied:', filters); // Log the applied filters
                fetchUsers(filters);
            });

            fetchUsers(); 

            //voice 
            const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
            recognition.lang = 'en-US';
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;

            const voiceButton = document.getElementById('voiceButton');
            const voiceOutput = document.getElementById('voiceOutput');

            voiceButton.addEventListener('click', function() {
                recognition.start();
                console.log('Voice recognition started.');
            });

            recognition.addEventListener('result', function(event) {
                const transcript = event.results[0][0].transcript;
                console.log('Voice recognition result:', transcript);
                voiceOutput.value = transcript;
                document.getElementById('keyword').value = transcript;
                fetchUsers({ keyword: transcript });
            });

            recognition.addEventListener('speechend', function() {
                recognition.stop();
                console.log('Voice recognition stopped.');
            });

            recognition.addEventListener('error', function(event) {
                console.error('Voice recognition error:', event.error);
            });
        });
    </script>
</head>
<body>
    <!-- Bande verte avec le logo et le titre -->
    <nav class="navbar navbar-expand-lg navbar-custom navbar-fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="assets/img/logoweb.jpg" alt="Logo" />
            </a>
            <span class="navbar-text">
                Users List
            </span>
            <div class="ml-auto">
                <a href="../backoffice/bs-simple-admin/index.html" class="btn btn-primary">Return to Dashboard</a>
            </div>
        </div>
    </nav>
    <!-- Fin de la bande verte avec le logo et le titre -->

    <div class="container table-custom">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        Advanced User Search
                    </div>
                    <div class="card-body">
                        <form id="searchForm">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select id="role" class="form-control">
                                            <option value="">All</option>
                                            <option value="1">Admin</option>
                                            <option value="0">User</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="status" class="form-control">
                                            <option value="">All</option>
                                            <option value="0">Active</option>
                                            <option value="1">Blocked</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="keyword">Keyword</label>
                                        <input type="text" id="keyword" class="form-control" placeholder="Search by name or email">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                        <div class="voice-recognition">
                            <button id="voiceButton" class="btn btn-secondary">Start Voice Recognition</button>
                            <textarea id="voiceOutput" class="form-control" rows="2" readonly></textarea>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>CIN</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Number</th>
                            <th>Password</th>
                            <th>Role</th>
                            <th>Mail</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userList">
                        <?php
                        include '../../Controller/usercontroller.php';
                        $controller = new UserController();
                        $list = $controller->listUsers();

                        foreach ($list as $row) {
                            $cin = $row['cin'];
                            $fname = $row['nom'];
                            $lname = $row['prenom'];
                            $num = $row['numero'];
                            $pwd = $row['pwd'];
                            $role = $row['role'];
                            $mail = $row['mail'];
                            $statut = $row['statut'];

                            echo "<tr>
                                    <td>{$cin}</td>
                                    <td>{$fname}</td>
                                    <td>{$lname}</td>
                                    <td>{$num}</td>
                                    <td>{$pwd}</td>
                                    <td>{$role}</td>
                                    <td>{$mail}</td>
                                    <td>{$statut}</td>
                                    <td>
                                        <a href='delete.php?cin={$cin}' class='btn btn-danger btn-sm btn-custom' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>
                                        <a href='edit.php?cin={$cin}' class='btn btn-primary btn-sm btn-custom'>Edit</a>
                                    </td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>
</html>