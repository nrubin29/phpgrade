<html>
    <head>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/jumbotron-colors.css" rel="stylesheet">
    </head>

    <body>
        <?php
            include "header.php";

            include_once "functions.php";

            $conn = get_mysql();
        ?>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST["login"])) {
                    $username = $_POST["username"];
                    $password = $_POST["password"];

                    $result = $conn->query("select * from users where username = '$username' and password = '$password'");

                    if ($result->num_rows > 0) {
                        $_SESSION["username"] = $username;
                        
                        $data = $result->fetch_assoc();
                        
                        $_SESSION["type"] = $data["type"];
                        $_SESSION["id"] = $data["id"];
                    }

                    header("Location: index.php");
                }
            }
        ?>

        <div class="jumbotron orange">
            <div class="container">
                <h1>phpgrade</h1>
                <p>Check your grades.</p>
            </div>
        </div>

        <?php if (!isset($_SESSION["username"])) { ?>

        <div class="container">
            <div class="col-lg-5 center">
                <div class="well well-sm">
                    <form method="post">
                        <div class="form-group">
                            <input class="form-control" type="text" id="username" name="username" placeholder="Username"/>
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="password" id="password" name="password" placeholder="Password"/>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary pull-right" type="submit" value="Login" id="login" name="login"/>
                            <br/>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php } else { ?>
        
        <div class="container">
            <div class="row">
                <div class="col-md-13">
                  <div class="panel panel-default">
                    <div class="panel-heading">Classes</div>
                    <div class="panel-body">
                      <ul class="list-group">
                          <?php
                              $username = $_SESSION["username"];

                              $classes = $conn->query("select classes from users where username = '$username'")->fetch_assoc()["classes"];
                              $classes = explode(",", $classes);

                              $result = $conn->query("select name, id from classes");

                              if ($result->num_rows > 0) {
                                  while ($row = $result->fetch_assoc()) {
                                      if (in_array($row["id"], $classes)) {
                                          echo '<li class="list-group-item"><a href="class.php?id=' . $row["id"] . '">' . $row["name"] . '</a></li>';
                                      }
                                  }
                              }

                              $conn->close();
                          ?>
                      </ul>
                    </div>
                  </div>
                </div>
            </div>
        </div>
        
        <?php } ?>

        <script src="js/bootstrap.min.js"></script>
    </body>
</html>