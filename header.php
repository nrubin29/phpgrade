<html>
    <head>
        <title>phpgrade</title>
    </head>

    <body>
        <?php
            session_start();
        ?>

        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php">phpgrade</a>
                </div>

                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="dropdown"><a href="about.php">About</a></li>

                        <?php if (isset($_SESSION["username"])) { ?>
                            <?php if (isset($_SESSION["type"]) && ($_SESSION["type"] == 3)) { ?>
                                <li class="dropdown"><a href="admin.php">Admin</a></li>
                            <?php } ?>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Welcome, <?php echo $_SESSION["username"] ?>! <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo "user.php?id=" . $_SESSION["id"] ?>">Account</a></li>
                                <li><a href="logout.php">Log Out</a></li>
                            </ul>
                        </li>
                    </ul>

                        <?php } ?>
                </div>
            </div>
        </nav>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    </body>
</html>