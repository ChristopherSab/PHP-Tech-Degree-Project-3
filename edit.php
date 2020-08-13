
<?php include 'inc/functions.php'; ?>

<?php

session_start();

if(isset($_GET["id"])) {
    $_SESSION["id"] = trim(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
 }

$id = intval($_SESSION["id"]);

$entry = get_journal_entry($id);

?>

<?php

if($_SERVER['REQUEST_METHOD']== 'POST'){

    //filter and store journal entry keys on the POST array
    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));
    $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
    $date = trim(filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING));
    $time_spent = trim(filter_input(INPUT_POST, 'time_spent', FILTER_SANITIZE_STRING));
    $learned = trim(filter_input(INPUT_POST, 'learned', FILTER_SANITIZE_STRING));
    $resources = trim(filter_input(INPUT_POST, 'resources', FILTER_SANITIZE_STRING));

    if(empty($title) || empty($date) || empty($time_spent) || empty($learned) || empty($resources)){
        header("Location: edit.php?entry=empty&title=$title&date=$date&time_spent=$time_spent&learned=$learned&resources=$resources");
        $_SESSION['error_empty'] = '<p class="error">Please Correctly Fill In All The Fields!</p>';
        exit();
    }  

    elseif(validDate($date) == false){
        header("Location: edit.php?entry=dateinvalid&title=$title&time_spent=$time_spent&learned=$learned&resources=$resources");
        $_SESSION['error_date'] = '<p class="error">Please Enter A Valid Date</p>';
        exit();
    }

    else {

        if(addEntry($id, $db, $title, $date, $time_spent, $learned, $resources)){
            $_SESSION["toast_message"] = '<h4 class="toast success">Update Successful</h4>';
            header("Location: index.php?update=success");
        } else{
            echo "<h3> Failed To Update Journal Entry </h3>";
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
                <div class="edit-entry">
                    <h2>Edit Entry</h2>
                    <?php 

                    if(isset($_GET['entry'])){

                        if($_GET['entry'] == 'empty'){

                            echo $_SESSION['error_empty'];
    
                        }elseif($_GET['entry'] == 'dateinvalid'){
    
                            echo $_SESSION['error_date'];
                        }

                    }
                    ?>
                    <!-- 
                    PHP FORM LOGIC: The form input fields show the different
                    journal entry details by default. HOWEVER, when a user
                    submits the form incorrectly the input already typed in is preserved.
                    A message will show the user what mistake/s they made when they
                    submitted the form.
                    -->
                    <form method="POST" action="edit.php">
                        <input type = "hidden" value = "<?php echo $id?>" name="id"/>
                        <!-- Title Input Section -->
                        <label for="title"> Title</label>
                        <input id="title" type="text" name="title"value="<?php 
                        if (isset($_GET['title'])){

                            echo $_GET['title'];

                        }else{
                            echo $entry["title"]; 
                        }
                        ?>"> <br>
                        <!-- Date Input Section -->
                        <label for="date">Date</label>
                        <input id="date" type="date" name="date" value="<?php
                        
                        if(isset($_GET['date'])){

                            echo $_GET['date'];

                        }else{
                            echo $entry["date"]; 
                        } 
                        ?>"><br>
                        <!-- Time Spent Input Section -->
                        <label for="time-spent"> Time Spent</label>
                        <input id="time-spent" type="text" name="time_spent" value="<?php 

                        if(isset($_GET['time_spent'])){

                            echo $_GET['time_spent'];

                        }else{
                            echo $entry["time_spent"]; 
                        }
                        ?>"><br>
                        <!-- Learned Input Section-->
                        <label for="what-i-learned">What I Learned</label>
                        <textarea id="what-i-learned" rows="5" name="learned"><?php 
                        if(isset($_GET['learned'])){

                            echo htmlspecialchars($_GET['learned'], ENT_QUOTES);

                        }else{
                            echo $entry["learned"]; 
                        }
                        ?></textarea>
                        <!-- Resources Input Section -->
                        <label for="resources-to-remember">Resources to Remember (Seperate Each Resource with a comma ",")</label>
                        <textarea id="resources-to-remember" rows="5" name="resources"><?php 

                        if(isset($_GET['resources'])){

                            echo htmlspecialchars($_GET['resources'], ENT_QUOTES);

                        }else{
                            echo $entry["resources"]; 
                        }
                        ?></textarea>
                        
                        <input type="submit" value="Publish Entry" class="button">
                        <a href="index.php" class="button button-secondary">Cancel</a>
                    </form>

                    <?php
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