<html>
<head>
  <title>CS3425 Final</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
  <link href="assets/css/app.css" rel="stylesheet" media="screen">
  <script src="assets/js/jquery-1.11.1.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/app.js"></script>
</head>

<body>
  <div id="wrapper">
    <nav role="navigation" class="navbar navbar-default">
        <div class="navbar-header">
            <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbarCollapse" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <!--<li><a href="#new-student-modal" data-toggle="modal">Add New Student</a></li>-->
                <?php session_start(); if(isset($_SESSION["user"]) && strlen($_SESSION["user"]) > 0) : ?>
                    <li><a href="#">Exams</a></li>
                    <li><a href="#" id="logout">Logout</a></li>
                <?php else : ?>
                    <li><a href='#login-modal' data-toggle='modal'>Student Login</a></li>
                <?php endif; ?>
            </ul>

        </div>
    </nav>

    <!-- New Student Modal -->
    <div id="new-student-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Create New Student Account</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="input-id">User ID</label>
                            <input type="email" class="form-control" id="input-id" placeholder="123456">
                        </div>
                        <div class="form-group">
                            <label for="inputPassword">Student Name</label>
                            <input type="password" class="form-control" id="inputPassword" placeholder="John Smith">
                        </div>
                        <div class="form-group">
                            <label for="inputPassword">Major</label>
                            <input type="password" class="form-control" id="inputPassword" placeholder="Computer Science">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Add New Student</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Login Modal -->
    <div id="login-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Student Login</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="input-id">User ID</label>
                            <input type="email" class="form-control" id="input-user" placeholder="User ID">
                        </div>
                        <div class="form-group">
                            <label for="inputPassword">Password</label>
                            <input type="password" class="form-control" id="input-pass" placeholder="Password">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="login-btn">Login</button>
                </div>
            </div>
        </div>
    </div>
