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
                $newdue = $_POST["newdue"];
                $newtotal = $_POST["newtotal"];
                $newenabled = empty($_POST["newenabled"]) ? 0 : 1;

                $conn->query("update assignments set name = '$newname', due = '$newdue', total = '$newtotal', enabled = $newenabled where id = $id");
                
                header("Location: assignment.php?id=$id");
            }
            
            else {
                $id = $_GET["id"];
            }
        ?>

        <?php
            $result = $conn->query("select * from assignments where id = " . $_GET["id"]);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $name = $row["name"];
                $due = $row["due"];
                $total = $row["total"];
                $enabled = $row["enabled"];
            }

            else {
                $name = "null";
                $due = "null";
                $total = "null";
                $enabled = false;
            }
        ?>

        <div class="jumbotron red">
            <div class="container">
                <h1>Edit Assignment</h1>
                <p><?php echo nameOfAssignment($id); ?></p>
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
                            <input class="form-control" type="text" id="newdue" name="newdue" placeholder="New Due (yyyy-mm-dd)" value="<?php echo $due ?>"/>
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="text" id="newtotal" name="newtotal" placeholder="New Total" value="<?php echo $total ?>"/>
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="checkbox" id="newenabled" name="newenabled" <?php if ($enabled) echo "checked" ?>/>
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