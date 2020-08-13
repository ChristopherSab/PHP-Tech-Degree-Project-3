
<?php include 'inc/functions.php'; ?>
<?php

session_start();

if($_SERVER['REQUEST_METHOD']== 'POST'){

    //filter and store journal entry keys on the POST array
    $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
    $date = trim(filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING));
    $time_spent = trim(filter_input(INPUT_POST, 'time_spent', FILTER_SANITIZE_STRING));
    $learned = trim(filter_input(INPUT_POST, 'learned', FILTER_SANITIZE_STRING));
    $resources = trim(filter_input(INPUT_POST, 'resources', FILTER_SANITIZE_STRING));

    if(empty($title) || empty($date) || empty($time_spent) || empty($learned) || empty($resources)){
        header("Location: new.php?entry=empty&title=$title&date=$date&time_spent=$time_spent&learned=$learned&resources=$resources");
        $_SESSION['error_empty'] = '<p class="error">Please Correctly Fill In All The Fields!</p>';
        exit();
    }  

    elseif(validDate($date) == false){
        header("Location: new.php?entry=dateinvalid&title=$title&time_spent=$time_spent&learned=$learned&resources=$resources");
        $_SESSION['error_date'] = '<p class="error">Please Enter A Valid Date</p>';
        exit();
    }

    else {

        if(addEntry($id, $db, $title, $date, $time_spent, $learned, $resources)){
            $_SESSION["toast_message"] = '<h4 class="toast success">Entry Successful</h4>';
            header("Location: index.php?update=success");
        } 
    }  
}
?>

<?php include 'inc/header.php'; ?>
    <body>
        <header>
            <div class="container">
                <div class="site-header">
                    <a class="logo" href="index.php"><i class="material-icons">library_books</i></a>
                    <a class="button icon-right" href="new.php"><span>New Entry</span> <i class="material-icons">add</i></a>
                </div>
            </div>
        </header>
        <section>
            <div class="container">
                <div class="new-entry">
 
                    <h2>New Entry</h2>
                    <?php 

                    if(isset($_GET['entry'])){

                        if($_GET['entry'] == 'empty'){

                            echo $_SESSION['error_empty'];

                        }elseif($_GET['entry'] == 'dateinvalid'){

                            echo $_SESSION['error_date'];
                        }

                    }
                    ?>
                    <form method="POST" action="new.php"> 

                        <!-- Title Input Section -->
                        <label for="title"> Title</label>
                        <?php
                        if(isset($_GET['title'])){

                         $title = $_GET['title'];  
                         echo  '<input id="title" type="text" name="title" value="'.$title.'"><br>';

                        }else{

                            echo '<input id="title" type="text" name="title"><br>';
                        }
                        ?>

                        <!-- Date Input Section -->
                        <label for="date">Date</label>
                        <?php

                        if(isset($_GET['date'])){

                            $date = $_GET['date'];
                            echo '<input id="date" type="date" name="date" value="'.$date.'"><br>';

                        }else{
                            echo '<input id="date" type="date" name="date"><br>';
                        }
                        ?>

                        <!-- Time-Spent Input Section -->
                        <label for="time-spent"> Time Spent</label>
                        <?php

                        if(isset($_GET['time_spent'])){

                            $time_spent = $_GET['time_spent'];
                            echo '<input id="time-spent" type="text" name="time_spent" value="'.$time_spent.'"><br>';

                        }else{
                            echo '<input id="time-spent" type="text" name="time_spent"><br>';
                        }

                        ?>
                        <!-- Learned Input Section -->
                        <label for="what-i-learned">What I Learned</label>
                        <?php

                        if(isset($_GET['learned'])){

                            $learned = htmlspecialchars($_GET['learned'], ENT_QUOTES);
                            echo '<textarea id="what-i-learned" rows="5" name="learned">'.$learned.'</textarea>';

                        }else{
                            echo '<textarea id="what-i-learned" rows="5" name="learned"></textarea>';
                        }

                        ?>
                        <!-- Resources Input Section -->
                        <label for="resources-to-remember">Resources to Remember (Seperate Each Resource with a comma ",")</label>
                        <?php

                        if(isset($_GET['resources'])){

                            $resources = htmlspecialchars($_GET['resources'], ENT_QUOTES);
                            echo '<textarea id="resources-to-remember" rows="5" name="resources">'.$resources.'</textarea>';

                        }else{
                            echo '<textarea id="resources-to-remember" rows="5" name="resources"></textarea>';
                        }
                        ?>
                        <input type="submit" value="Publish Entry" class="button">
                        <a href="index.php" class="button button-secondary">Cancel</a>
                    </form>

                    <?php
                    //The code below will check the URL for either "entry=empty" OR "entry=dateinvalid"
                    //And echo the appropriate error message
                    $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

                    if(strpos($fullUrl, "entry=empty") == true){
                        echo $_SESSION['error_empty'];
                        exit();
                    }
                    elseif(strpos($fullUrl, "entry=dateinvalid") == true){
                        echo $_SESSION['error_date'];
                        exit();
                    }
                    ?>

                </div>
            </div>
        </section>
        <?php include 'inc/footer.php'; ?>
    </body>
</html>

