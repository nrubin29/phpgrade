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

                $name = $_POST["name"];
                $teacher = $_POST["teacher"];

                $conn->query("insert into classes (name, teacher) values ('$name', '$teacher')");
                
                header("Location: admin.php");
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
                            <input class="form-control" type="text" id="name" name="name" placeholder="Name"/>
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="text" id="teacher" name="teacher" placeholder="Teacher"/>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary pull-right" type="submit" value="Add Class" id="add" name="add"/>
                            <br/>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>