<?php
include_once("../../connectFiles/connect_e.php");
include_once("cas-go.php");

?>
    <!DOCTYPE html>
    <html lang="">

    <head>
        <title>ELC Student Data</title>

        <!-- 	Meta Information -->
        <meta charset="utf-8">
        <meta name="description" content="This section of the ELC website outlines the ELC curriculum." />
        <meta name="keywords" content="ELC, BYU, ESL, Curriculum, Levels, Learning, Outcomes" />
        <meta name="robots" content="ELC, BYU, ESL, Curriculum, Levels, Learning, Outcomes" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,100' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Martel:600,400,200' rel='stylesheet' type='text/css'>
        <link href="jquery-ui.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/jquery-ui.js"></script>
        <script type="text/javascript" src="js/js.js"></script>
        
    </head>

    <body>
        <header>
            <div id='title'>
                ELC Student Data
            </div>
            <div id="user">
                <?php echo $button;?>
            </div>
        </header>
        <article>
            <?php
            if (isset($net_id)==false) {
                echo "Access Denied.";
                return;
            } else {
                if ($net_id == "blm39" || $net_id =="hatuhart" || $net_id =="kjh27" || $net_id =="sandyh2") {
                } else {
                    echo "Access Denied.";
                    return;
                }
            }
            ?>

            <div id="search_bar">
                search (by name or id)
                <div contenteditable="true" id='search'>

                </div>
                <a id='get'>Get Info</a>

            </div>
                <div id="content">

                    <div id="student_data">

                    </div>
                  </div>

        </article>
        <footer>
            <div id="results"></div>
        </footer>


    </body>

    </html>
