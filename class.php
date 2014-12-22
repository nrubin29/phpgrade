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
                if (isset($_GET["delete"]) && $_GET["delete"] == 1) {
                    $conn->query("delete from classes where id = " . $_GET["id"]);
                    header("Location: admin.php");
                }
                
                if (isset($_GET["remove"])) {
                    $classes = $conn->query("select classes from users where id = " . $_GET["remove"])->fetch_assoc()["classes"];
                    
                    $classes = array_values(explode(",", $classes));
                    
                    foreach ($classes as $index => $value) {
                        if ($value == $_GET["id"]) {
                            unset($classes[$index]);
                        }
                    }
                    
                    $classes = implode(",", $classes);
                    
                    $conn->query("update users set classes = '$classes' where id = " . $_GET["remove"]);
                    
                    header("Location: class.php?id=" . $_GET["id"]);
                }
            }
        ?>

        <?php
            $result = $conn->query("select * from classes where id = " . $_GET["id"]);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $name = $row["name"];
                $teacher = $row["teacher"];
                $teacher = explode(",", $teacher);
                $teacher = implode("<br/>", $teacher);
            }

            else {
                $name = "null";
                $teacher = "null";
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
                                    <th>Teacher</th>
                                    <td><?php echo $teacher ?></td>
                                </tr>

                                <?php
                                    $result = $conn->query("select grade from grades where user = '" . $_SESSION["id"] . "' and class = " . $_GET["id"]);

                                    $result1 = $conn->query("select total, enabled from assignments where class = " . $_GET["id"]);

                                    $totalgrade = 0;
                                    $numgrades = 0;

                                    while (($row = $result->fetch_assoc()) && ($row1 = $result1->fetch_assoc())) {
                                        if ($row1["enabled"] && $row["grade"] != null) {
                                            $totalgrade += ($row["grade"] / $row1["total"] * 100);
                                            $numgrades++;
                                        }
                                    }

                                    echo '<tr>';
                                    echo '<th>Final Grade';
                                    echo '<td>' . ($numgrades != 0 ? round($totalgrade / $numgrades) : 0) . '%</td>';
                                    echo '</tr>';
                                ?>
                                
                                <?php if ($_SESSION["type"] > 1) { ?>
                                    <tr>
                                        <th>View As</th>
                                        <td>
                                            <select class="form-control" id="viewas" name="viewas">
                                                <?php
                                                $id = $_GET["id"];
                                                $students = studentsInClass($id);

                                                foreach ($students as $student) {
                                                    $sid = idOfStudent($student);
                                                    echo '<option value="' . $sid . '" selected="' . $sid == $_SESSION["id"] ? "selected" : "" . '">' . $student . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>

                            <?php if ($_SESSION["type"] == 3) { ?>
                                <div class="col-xs-6">
                                    <?php
                                        $onclick = "location.href = 'editclass.php?id=$id';";
                                        echo '<input type="button" onclick="' . $onclick . '" value="Edit" class="btn btn-primary pull-right"/>';
                                    ?>
                                </div>

                                <div class="col-xs-6">
                                    <?php
                                        $onclick = "location.href = 'class.php?id=$id&delete=1';";
                                        echo '<input type="button" onclick="' . $onclick . '" value="Delete" class="btn btn-danger pull-right"/>';
                                    ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="panel panel-default">
                        <div class="panel-heading">Grades</div>
                        <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <th>Assignment</th>
                                    <th>Due</th>
                                    <th>Grade</th>
                                    <th>Percentage</th>
                                    <th>Comments</th>
                                    <th>Enabled</th>
                                </tr>

                                <?php
                                    $result1 = $conn->query("select id, name, due, total, enabled from assignments where class = " . $_GET["id"]);

                                    while ($row = $result1->fetch_assoc()) {
                                        $result2 = $conn->query("select grade, comment from grades where user = '" . $_SESSION["id"] . "' and class = " . $_GET["id"] . " and assignment = " . $row["id"]);
                                      
                                        if ($result2->num_rows > 0) {
                                            $row1 = $result2->fetch_assoc();
                                        }
                                        
                                        else {
                                            $row1 = array("grade" => "0");
                                        }

                                        if ($row1["grade"] == null) {
                                            $row1["grade"] = "--";
                                        }
                                        
                                        echo '<tr>';
                                        echo '<th><a href="assignment.php?id=' . $row["id"] . '">' . $row["name"] . '</a></th>';
                                        echo '<td>' . $row["due"] . '</td>';
                                        echo '<td>' . $row1["grade"] . '/' . $row["total"] . '</td>';
                                        echo '<td>' . ($row1["grade"] / $row["total"] * 100) . '%</td>';
                                        echo '<td><em>' . $row1["comment"] . '</em></td>';
                                        echo '<td>' . ($row["enabled"] ? '<span class="glyphicon glyphicon-ok"/>' : '<span class="glyphicon glyphicon-remove"/>') . '</td>';
                                        echo '</tr>';
                                    }

                                    $conn->close();
                                ?>
                            </table>
                            
                            <?php
                                if ($_SESSION["type"] >= 2) {
                                    $class = $_GET["id"];
                                    $onclick = "location.href = 'addassignment.php?class=$class';";
                                    echo '<input type="button" onclick="' . $onclick . '" value="Add Assignment" class="btn btn-primary pull-right"/>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if ($_SESSION["type"] != 1) { ?>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Students</div>
                            <div class="panel-body">
                                <table class="table">
                                    <?php
                                        $students = studentsInClass($id);

                                        foreach ($students as $student) {
                                            $sid = idOfStudent($student);
                                            
                                            echo '<tr>';
                                            echo '<th><a href="user.php?id=' . $sid . '">' . $student . '</a></th>';
                                            
                                            $onclick = "location.href = 'class.php?id=$id&remove=$sid';";
                                            echo '<td><input type="button" onclick="' . $onclick . '" value="Remove" class="btn btn-danger pull-right"/></td>';
                                            
                                            echo '</tr>';
                                        }
                                    ?>
                                </table>
                                
                                <input type="button" onclick="<?php $id = $_GET["id"]; echo "location.href = 'addto.php?class=$id'" ?>" value="Add Student" class="btn btn-primary pull-right"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>