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
                $newusername = $_POST["newusername"];
                $newpassword = $_POST["newpassword"];
                $newtype = $_POST["newtype"];

                $conn->query("update users set username = '$newusername' where id = $id");
                $conn->query("update users set password = '$newpassword' where id = $id");
                $conn->query("update users set type = $newtype where id = $id");
                
                header("Location: user.php?id=$id");
            }
            
            else {
                $id = $_GET["id"];
            }
        ?>

        <?php
            $result = $conn->query("select * from users where id = " . $_GET["id"]);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $username = $row["username"];
                $password = $row["password"];
                $type = $row["type"];
            }

            else {
                $username = "null";
                $password = "null";
                $type = "null";
            }
        ?>

        <div class="jumbotron red">
            <div class="container">
                <h1>Edit User</h1>
                <p><?php echo $username; ?></p>
            </div>
        </div>

        <div class="container">
            <div class="col-lg-5 center">
                <div class="well well-sm">
                    <form method="post">
                        <div class="form-group">
                            <input class="form-control" type="text" id="newusername" name="newusername" placeholder="New Username" value="<?php echo $username ?>"/>
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="password" id="newpassword" name="newpassword" placeholder="New Password" value="<?php echo $password ?>"/>
                        </div>

                        <div class="form-group">
                            <select class="form-control" id="newtype" name="newtype">
                                <option value="1" selected="<?php echo $type == 1 ? "selected" : "" ?>">Student</option>
                                <option value="2" selected="<?php echo $type == 2 ? "selected" : "" ?>">Teacher</option>
                                <option value="3" selected="<?php echo $type == 3 ? "selected" : "" ?>">Admin</option>
                            </select>
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