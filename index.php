
 <?php include 'inc/header.php'; ?>
 <?php require_once 'inc/functions.php';?>
 <?php

 session_start();

 $_SESSION["form_error"] = "";

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
 
                <div class="entry-list">

                <?php

                if(isset($_GET["update"])){

                    echo $_SESSION["toast_message"];
                } 

                foreach(get_journal_entries() as $entry){
                    echo '<article>';
                    echo '<h2><a href="detail.php?id='.$entry["id"].'">'.$entry["title"].'</a></h2>';
                    echo '<time datetime="'.$entry["date"].'">'.dateFormatter($entry["date"]).'</time>';
                    echo '</article>';
                }

                ?>

                </div>
            </div>
        </section>
        <?php include 'inc/footer.php'; ?>
    </body>
</html>