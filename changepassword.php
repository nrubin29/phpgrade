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

            if ($_SESSION["type"] == 1 && $_GET["id"] != $_SESSION["id"]) {
                header("Location: index.php");
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $conn = get_mysql();

                $id = $_POST["id"];
                $newpassword = $_POST["newpassword"];

                $conn->query("update users set password = '$newpassword' where id = $id");
                
                header("Location: user.php?id=$id");
            }
            
            else {
                $id = $_GET["id"];
            }
        ?>

        <div class="jumbotron red">
            <div class="container">
                <h1>Change Password</h1>
                <p><?php include_once("functions.php"); echo nameOfStudent($id); ?></p>
            </div>
        </div>

        <div class="container">
            <div class="col-lg-5 center">
                <div class="well well-sm">
                    <form method="post">
                        <div class="form-group">
                            <input class="form-control" type="password" id="newpassword" name="newpassword" placeholder="New Password"/>
                        </div>
                        
                        <div class="form-group">
                            <input class="form-control" type="hidden" id="id" name="id" value="<?php echo $id ?>"/>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary pull-right" type="submit" value="Change Password" id="button" name="button"/>
                            <br/>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>