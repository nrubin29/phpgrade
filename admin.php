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

            $conn = get_mysql();
        ?>

        <div class="jumbotron red">
            <div class="container">
                <h1>Admin</h1>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Users</div>
                        <div class="panel-body">
                            <ul class="list-group">
                                <?php
                                    $result = $conn->query("select username, id from users");

                                    while ($row = $result->fetch_assoc()) {
                                        echo '<li class="list-group-item"><a href="user.php?id=' . $row["id"] . '">' . $row["username"] . '</a></li>';
                                    }
                                ?>
                            </ul>

                            <input type="button" onclick="location.href = 'adduser.php';" value="Add User" class="btn btn-primary pull-right"/>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">Classes</div>
                        <div class="panel-body">
                            <ul class="list-group">
                                <?php
                                    $result = $conn->query("select * from classes");

                                    while ($row = $result->fetch_assoc()) {
                                        echo '<li class="list-group-item"><a href="class.php?id=' . $row["id"] . '">' . $row["name"] . '</a></li>';
                                    }

                                    $conn->close();
                                ?>
                            </ul>

                            <input type="button" onclick="location.href = 'addclass.php';" value="Add Class" class="btn btn-primary pull-right"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>