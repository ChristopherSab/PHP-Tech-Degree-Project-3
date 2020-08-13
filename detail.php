
<?php include 'inc/header.php'; ?>
<?php require_once 'inc/functions.php';?>

<?php

session_start();

if(!empty($_GET['id'])){

    $id = intval($_GET['id']);
    $entry = get_journal_entry($id);

}

if($_SERVER['REQUEST_METHOD']== 'POST'){

    $id = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT));

    if(deleteData($db,$id)){
        $_SESSION["toast_message"] = '<h4 class="toast error">Deletion Successful</h4>';
        header("Location: index.php?update=success");
    } else{
        echo "<h3> Error : Failed to delete journal entry </h3>";
    }
}

?>

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
                <div class="entry-list single">
                    <article>
                        <h1><?php echo $entry['title']?></h1>
                        <?php echo '<time datetime="'.$entry["date"].'">'.dateFormatter($entry["date"]).'</time>';   ?>
                        <div class="entry">
                            <h3>Time Spent: </h3>
                            <p><?php echo $entry['time_spent'] ?></p>
                        </div>
                        <div class="entry">
                            <h3>What I Learned:</h3>
                            <p><?php echo $entry['learned'] ?></p>
                            
                        </div>
                        <div class="entry">
                            <h3>Resources to Remember:</h3>
                            <ul>
                            <?php
                            $resources = explode(',', $entry['resources'] );

                            if(!empty($entry['resources'])){

                                foreach($resources as $r){
                                    echo '<li>'.$r.'</li>';
                                }

                            }else{
                                echo '<li> None </li>';
                            }
                            
                            ?>
                            </ul>
                        </div>
                    </article>
                </div>
            </div>
            <div class="edit">
            <?php echo "<p><a href='edit.php?id=".$id."'>Edit Entry</a></p>"; ?>
                <form method="post" action="detail.php">
                    <input hidden value="<?= $id ?>" name="id">
                    <input type="submit" value="Delete" class="button" name="delete"/>
                </form>
            </div>
        </section>
        <?php include 'inc/footer.php'; ?>
    </body>
</html>