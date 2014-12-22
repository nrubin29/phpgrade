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
            
            if ($_SESSION["type"] == 1 && $_GET["id"] != $_SESSION["id"]) {
                header("Location: index.php");
            }

            $conn = get_mysql();
        ?>
        
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                if ($_GET["delete"] == 1) {
                    $conn->query("delete from users where id = " . $_GET["id"]);
                    header("Location: admin.php");
                }
            }
        ?>

        <?php
            $result = $conn->query("select * from users where id = " . $_GET["id"]);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $username = $row["username"];
                $password = $row["password"];
                $type = $row["type"];
                $classids = $row["classes"];
                $classes = array();

                if ($classids != "") {
                    $classids = explode(",", $classids);

                    foreach ($classids as $key => $classid) {
                        if ($classid == "") {
                            unset($classids[$key]);
                        }
                    }

                    foreach ($classids as $classid) {
                        $classes[$classid] = nameOfClass($classid);
                    }
                }
            }

            else {
                $username = "null";
                $password = "null";
                $type = "null";
            }
        ?>

        <div class="jumbotron blue">
            <div class="container">
                <h1><?php echo $username ?></h1>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">Information</div>
                        <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <th>Username</th>
                                    <td><?php echo $username ?></td>
                                </tr>
                                <tr>
                                    <th>Password</th>
                                    <td><?php echo $password ?></td>
                                </tr>
                                <tr>
                                    <th>ID</th>
                                    <td><?php echo $_GET["id"] ?></td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td><?php echo $type == 1 ? "Student" : ($type == 2 ? "Teacher" : ($type == 3 ? "Admin" : "null")) ?></td>
                                </tr>
                            </table>
                            
                            <?php $id = $_GET["id"]; if (($_SESSION["type"] == 3) || ($id == $_SESSION["id"])) { ?>
                                <div class="col-xs-6">
                                  <input type="button" onclick="<?php echo "location.href = 'changepassword.php?id=$id'" ?>" value="Change Password" class="btn btn-primary"/>
                                </div>
                            <?php } ?>
                                
                            <?php if ($_SESSION["type"] == 3) { ?>
                                <div class="col-xs-3">
                                    <input type="button" onclick="<?php echo "location.href = 'edituser.php?id=$id'" ?>" value="Edit" class="btn btn-primary"/>
                                </div>

                                <div class="col-xs-3">
                                  <input type="button" onclick="<?php echo "location.href = 'user.php?id=$id&delete=1'" ?>" value="Delete" class="btn btn-danger"/>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">Classes</div>
                        <div class="panel-body">
                            <ul class="list-group">
                                <?php
                                    for ($i = 0; $i < sizeof($classes); $i++) {
                                        if (array_values($classes)[$i] != "") {
                                            echo '<li class="list-group-item"><a href="class.php?id=' . $classids[$i] . '">' . array_values($classes)[$i] . '</a></li>';
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>