<html>
    <head>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/jumbotron-colors.css" rel="stylesheet">
    </head>

    <body>
        <?php
            include "header.php";

            include_once "functions.php";

            if (!isset($_SESSION["username"]) || !isset($_SESSION["type"])) {
                header("Location: index.php");
            }

            if ($_SESSION["type"] != 3) {
                header("Location: index.php");
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $conn = get_mysql();

                $username = $_POST["username"];
                $password = $_POST["password"];
                $type = $_POST["type"];

                $conn->query("insert into users (username, password, type) values ('$username', '$password', $type)");
                
                header("Location: admin.php");
            }
        ?>

        <div class="jumbotron red">
            <div class="container">
                <h1>Add User</h1>
            </div>
        </div>

        <div class="container">
            <div class="col-lg-5 center">
                <div class="well well-sm">
                    <form method="post">
                        <div class="form-group">
                            <input class="form-control" type="text" id="username" name="username" placeholder="Username"/>
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="password" id="password" name="password" placeholder="Password"/>
                        </div>
                        
                        <div class="form-group">
                            <select class="form-control" id="type" name="type">
                                <option value="1">Student</option>
                                <option value="2">Teacher</option>
                                <option value="3">Admin</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary pull-right" type="submit" value="Add User" id="add" name="add"/>
                            <br/>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>