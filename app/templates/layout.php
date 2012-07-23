<?php header('Content-Type: text/html; charset=utf-8');?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $title ?></title>
        <link href="/static/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/static/css/main.css" type="text/css" />

    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="span12">
                    <h1>YaBlog</h1>
                </div>
            </div>

            <div class="row">
                <div class="span4">Navigation</div>
                <div class="span8">
                    <?php echo $_content ?>
                </div>
            </div>
        </div>
    </body>
</html>