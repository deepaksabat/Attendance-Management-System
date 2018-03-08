<html>
<head>
    <title>
        Attendance Management System Installation
    </title>
    <script src="public/js/jquery-1.9.0.min.js"></script>
    <script src="public/js/bootstrap3/js/bootstrap.js"></script>
    <link media="all" type="text/css" rel="stylesheet" href="public/js/bootstrap3/css/bootstrap.min.css">
    <link media="all" type="text/css" rel="stylesheet" href="public/js/bootstrap3/css/font-awesome.min.css">
    <link media="all" type="text/css" rel="stylesheet" href="public/js/bootstrap3/css/main.css">
</head>
<body>

<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php // echo base_url()?>">Kingpabel ATN</a>
        </div>
        <div class="navbar-collapse collapse">

        </div>
        <!--/.nav-collapse -->
    </div>
</div>
<div class="rc">
    <div class="container main">
        <div class="col-md-3">

        </div>
        <div class="col-md-6">
            <form method="post" accept-charset="utf-8" role="form" class="form-signin form-horizontal">
                <h2 class="form-signin-heading">Installation</h2>

                <div style="margin-top: 20px;">
                    <label for="host_name" class="col-sm-3 control-label" style="color: black">
                        Host Name
                    </label>

                    <div class="input text database_host  col-md-9">
                        <input type="text" name="host_name" class="form-control" placeholder="Database Host Name"
                               autofocus=""
                               required="required" id="host_name">
                    </div>
                </div>
                <div>
                    <label for="database_name" class="col-sm-3 control-label" style="color: black">
                        Database Name
                    </label>

                    <div class="input text database_name  col-md-9">
                        <input type="text" name="database_name" class="form-control" placeholder="Database Name"
                               autofocus=""
                               required="required" id="database_name">
                    </div>
                </div>
                <div>
                    <label for="user_name" class="col-sm-3 control-label" style="color: black">
                        User Name
                    </label>

                    <div class="input text user_name  col-md-9">
                        <input type="text" name="user_name" class="form-control" placeholder="Database User Name"
                               autofocus=""
                               required="required" id="user_name">
                    </div>
                </div>
                <div>
                    <label for="password" class="col-sm-3 control-label" style="color: black">
                        Password
                    </label>

                    <div class="input text password col-md-9">
                        <input type="password" name="password" class="form-control" placeholder="Database Password"
                               autofocus=""
                               id="password">
                    </div>
                </div>
                <div>
                    <label for="project_url" class="col-sm-3 control-label" style="color: black">
                        Project Url
                    </label>

                    <div class="input text project_url col-md-8 input-group input-group-sm">
                        <input style="    margin-left: 13px;" type="text" name="project_url" class="form-control"
                               placeholder="Project URL"
                               autofocus=""
                               id="project_url" aria-describedby="basic-addon2">
                        <span class="input-group-btn">
        <button class="btn btn-default" type="button" style="margin-top: -15px;">/public</button>
      </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-lg btn-login btn-block">Install</button>
            </form>
        </div>

    </div>
</div>
<section id="bottom" style="margin-top: 50px">

</section>
<footer id="footer">
    <div class="container">
        <div class="footer">
            <div class="row">
                <div class="col-md-12">
                    <span>&copy; 2016 <a href="http://www.kingpabel.com">Kingpabel</a>. All Rights Reserved.</span>
                </div>
            </div>
        </div>
    </div>
</footer>
</body>
</html>

<?php
if ($_POST) {
    $envFile = file_get_contents('.env');

    if (isset($_POST['host_name']) && $_POST['host_name'])
        $envFile = str_replace('DB_HOST=localhost', "DB_HOST={$_POST['host_name']}", $envFile);

    if (isset($_POST['database_name']) && $_POST['database_name'])
        $envFile = str_replace('DB_DATABASE=homestead', "DB_DATABASE={$_POST['database_name']}", $envFile);

    if (isset($_POST['user_name']) && $_POST['user_name'])
        $envFile = str_replace('DB_USERNAME=homestead', "DB_USERNAME={$_POST['user_name']}", $envFile);

    if (isset($_POST['password']) && $_POST['password'])
        $envFile = str_replace('DB_PASSWORD=secret', "DB_PASSWORD={$_POST['password']}", $envFile);

    file_put_contents('.env', $envFile);

    if (isset($_POST['project_url']) && $_POST['project_url']) {
        file_get_contents('index.php');
        file_put_contents('index.php', "<?php
    header('Location: {$_POST['project_url']}/public');
    ");
    }
}