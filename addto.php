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

            if ($_SESSION["type"] == 1) {
                header("Location: index.php");
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $conn = get_mysql();

                $name = $_POST["name"];
                $class = $_POST["class"];
                
                $classes = $conn->query("select classes from users where id = $name")->fetch_assoc()["classes"];
                    
                $classes = $classes . $class . ",";
                    
                $conn->query("update users set classes = '$classes' where id = $name");
                    
                header("Location: class.php?id=$class");
            }
        ?>

        <div class="jumbotron red">
            <div class="container">
                <h1>Add Class</h1>
            </div>
        </div>

        <div class="container">
            <div class="col-lg-5 center">
                <div class="well well-sm">
                    <form method="post">
                        <div class="form-group">
                            <input class="form-control" type="text" id="name" name="name" placeholder="Student ID"/>
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="hidden" id="class" name="class" value="<?php echo $_GET["class"] ?>"/>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary pull-right" type="submit" value="Add Student to Class" id="add" name="add"/>
                            <br/>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>