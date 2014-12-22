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
            
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                $class = $_GET["class"];
            }

            elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
                $conn = get_mysql();

                $name = $_POST["name"];
                $class = $_POST["class"];
                $due = $_POST["due"];
                $total = $_POST["total"];
                
                var_dump($_POST);

                $conn->query("insert into assignments (class, name, due, total) values ($class, '$name', '$due', $total)");
                
                header("Location: class.php?id=$class");
            }
        ?>

        <div class="jumbotron blue">
            <div class="container">
                <h1>Add Assignment</h1>
                <p><?php include_once("functions.php"); echo nameOfClass($_GET["class"]) ?></p>
            </div>
        </div>

        <div class="container">
            <div class="col-lg-5 center">
                <div class="well well-sm">
                    <form method="post">
                        <div class="form-group">
                            <input class="form-control" type="text" id="name" name="name" placeholder="Name"/>
                        </div>
                        
                        <div class="form-group">
                            <input class="form-control" type="hidden" id="class" name="class" " value="<?php echo $class ?>"/>
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="text" id="due" name="due" placeholder="Due (yyyy-mm-dd)"/>
                        </div>
                        
                        <div class="form-group">
                            <input class="form-control" type="number" id="total" name="total" placeholder="Point Value"/>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary pull-right" type="submit" value="Add Assignment" id="add" name="add"/>
                            <br/>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>