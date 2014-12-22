<html>
    <head>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/jumbotron-colors.css" rel="stylesheet">
    </head>

    <body>
        <?php
            include "header.php";

            include_once "functions.php";

            if (!isset($_SESSION["username"])) {
                header("Location: index.php");
            }

            $conn = get_mysql();
        ?>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                if ($_GET["delete"] == 1) {
                    $conn->query("delete from assignments where id = " . $_GET["id"]);
                    header("Location: admin.php");
                }
            }
        ?>

        <?php
            $result = $conn->query("select * from assignments where id = " . $_GET["id"]);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $name = $row["name"];
                $class = nameOfClass($row["class"]);
                $classid = $row["class"];
                $due = $row["due"];
                $total = $row["total"];
                $enabled = $row["enabled"];
            }

            else {
                $name = "null";
                $class = "null";
                $classid = -1;
                $due = "null";
                $total = "null";
                $enabled = false;
            }
        ?>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                foreach ($_POST as $key => $value) {
                    if (strpos($key, "g-") === 0) {
                        $key = substr($key, 2);

                        $amnt = sizeof($conn->query("select grade from grades where user = $key and assignment = " . $_GET["id"])->fetch_assoc());

                        if ($amnt == 0) {
                            $conn->query("insert into grades (user, class, assignment, grade) values ($key, $classid, " . $_GET["id"] . ", $value)");
                        }

                        else {
                            $conn->query("update grades set grade = $value where user = $key and assignment = " . $_GET["id"]);
                        }
                    }
                    
                    elseif (strpos($key, "c-") === 0) {
                        $key = substr($key, 2);

                        $amnt = sizeof($conn->query("select grade from grades where user = $key and assignment = " . $_GET["id"])->fetch_assoc());

                        if ($amnt == 0) {
                            $conn->query("insert into grades (user, class, assignment, comment) values ($key, $classid, " . $_GET["id"] . ", '$value')");
                        }

                        else {
                            $conn->query("update grades set comment = '$value' where user = $key and assignment = " . $_GET["id"]);
                        }
                    }
                }
            }
        ?>

        <div class="jumbotron blue">
            <div class="container">
                <h1><?php echo $name ?></h1>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">Information</div>
                        <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo $name ?></td>
                                </tr>
                                <tr>
                                    <th>Class</th>
                                    <td><a href="<?php echo "class.php?id=" . $classid ?>"><?php echo $class ?></a></td>
                                </tr>
                                <tr>
                                    <th>Due</th>
                                    <td><?php echo $due ?></td>
                                </tr>
                                <tr>
                                    <th>Point Value</th>
                                    <td><?php echo $total ?></td>
                                </tr>
                                <tr>
                                    <th>Enabled</th>
                                    <td><?php echo $enabled ? "true" : "false" ?></td>
                                </tr>
                            </table>

                            <?php if ($_SESSION["type"] == 3) { ?>
                                <div class="col-xs-6">
                                    <?php
                                        $id = $_GET["id"];
                                        $onclick = "location.href = 'editassignment.php?id=$id';";
                                        echo '<input type="button" onclick="' . $onclick . '" value="Edit" class="btn btn-primary pull-right"/>';
                                    ?>
                                </div>

                                <div class="col-xs-6">
                                    <?php
                                        $onclick = "location.href = 'assignment.php?id=$id&delete=1';";
                                        echo '<input type="button" onclick="' . $onclick . '" value="Delete" class="btn btn-danger pull-right"/>';
                                    ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php if ($_SESSION["type"] > 1) { ?>
                    <div class="col-md-9">
                        <div class="panel panel-default">
                            <div class="panel-heading">Grades</div>
                            <div class="panel-body">
                                <form method="post" action=<?php echo "assignment.php?id=" . $_GET["id"]; ?>>
                                    <table class="table">
                                        <tr>
                                            <th>Student</th>
                                            <th>Grade</th>
                                            <th>Comment</th>
                                        </tr>

                                        <?php
                                           $students = studentsInClass($classid);

                                           foreach ($students as $student) {
                                                $id = idOfStudent($student);
                                                $result = $conn->query("select grade, comment from grades where user = $id and assignment = " . $_GET["id"]);
                                            
                                                if ($result->num_rows > 0) {
                                                    $row = $result->fetch_assoc();
                                                }
                                        
                                                else {
                                                    $row = array("grade" => "", "comment" => "");
                                                }
                                            
                                                echo '<tr>';
                                                echo '<th><a href="user.php?id=' . $id . '">' . $student . '</a></th>';
                                                echo '<td><input type="number" id="g-' . $id . '" name="g-' . $id . '" class="form-control" value="' . $row["grade"] . '"/></td>';
                                                echo '<td><input type="text" id="c-' . $id . '" name="c-' . $id . '" class="form-control" value="' . $row["comment"] . '"/></td>';
                                                echo '</tr>';
                                            }
                                        ?>
                                    </table>

                                    <input type="submit" class="btn btn-primary pull-right"/>
                                </form>
                           </div>
                       </div>
                   </div>
                <?php } ?>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>