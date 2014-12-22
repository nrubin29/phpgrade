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

            $conn = get_mysql();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $id = $_POST["id"];
                $newname = $_POST["newname"];
                $newteacher = $_POST["newteacher"];

                $conn->query("update classes set name = '$newname' where id = $id");
                $conn->query("update classes set teacher = '$newteacher' where id = $id");
                
                header("Location: class.php?id=$id");
            }
            
            else {
                $id = $_GET["id"];
            }
        ?>

        <?php
            $result = $conn->query("select * from classes where id = " . $_GET["id"]);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $name = $row["name"];
                $teacher = $row["teacher"];
            }

            else {
                $name = "null";
                $teacher = "null";
            }
        ?>

        <div class="jumbotron red">
            <div class="container">
                <h1>Edit Class</h1>
                <p><?php echo nameOfClass($id); ?></p>
            </div>
        </div>

        <div class="container">
            <div class="col-lg-5 center">
                <div class="well well-sm">
                    <form method="post">
                        <div class="form-group">
                            <input class="form-control" type="text" id="newname" name="newname" placeholder="New Name" value="<?php echo $name ?>"/>
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="text" id="newteacher" name="newteacher" placeholder="New Teacher" value="<?php echo $teacher ?>"/>
                        </div>
                        
                        <div class="form-group">
                            <input class="form-control" type="hidden" id="id" name="id" value="<?php echo $id ?>"/>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary pull-right" type="submit" value="Update" id="button" name="button"/>
                            <br/>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>