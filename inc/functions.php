
<?php

require_once 'connection.php';

//This function returns an Associative array of Journal Entries
function get_journal_entries(){

    include 'inc/connection.php';

    try{

        $results = $db->query('SELECT * FROM entries ORDER BY date DESC');
        return $results->fetchAll(PDO::FETCH_ASSOC);
    
     } catch(Exception $e){
        echo "error: ".$e->getMessage();
        return array();
     }
     
}

//This function gets one Journal entry by the ID
function get_journal_entry($id){

    include 'inc/connection.php';

    try{

        $results = $db->prepare('SELECT * FROM entries WHERE id = ?');
        $results->bindParam(1, $id);
        $results->execute();
        return $results->fetch(PDO::FETCH_ASSOC);
        
        
        } catch(Exception $e){
        echo "There Was An Error: ".$e->getMessage();
        return array();
        }
}


//This function ADDS or UPDATES a journal entry into the database, 
//Depending on whether an ID is passed in or NOT
function addEntry($id=NULL, $db, $title, $date, $time_spent, $learned, $resources){

    if($id) {
        $sql = "UPDATE entries SET title = ?, date = ?, time_spent = ?, learned = ?, resources = ? WHERE id = ?";
      }
       else {
         $sql = "INSERT INTO entries(title,date,time_spent,learned,resources) VALUES(?, ?, ?, ?, ?)";
      }
       
      try {
         $results = $db->prepare($sql);
         $results->bindValue(1,$title,PDO::PARAM_STR);
         $results->bindValue(2,$date,PDO::PARAM_STR);
         $results->bindValue(3,$time_spent,PDO::PARAM_STR);
         $results->bindValue(4,$learned,PDO::PARAM_STR);
         $results->bindValue(5,$resources,PDO::PARAM_STR);

        if($id) {
          $results->bindValue(6,$id,PDO::PARAM_INT);
        }

         $results->execute();

      } catch(Exception $e) {
        echo "Error message ". $e->getMessage();
        return false;
      }
      return true;
}

//This function deletes a journal entry from the database 
//By taking in an $id parameter
function deleteData($db,$id){

    try{
        $sql = ("DELETE FROM entries WHERE id=?");
    
       $results = $db->prepare($sql);
    
       $results->bindValue(1, $id, PDO::PARAM_INT);
    
       $results->execute();
    
        } catch(Exception $e){
    
            $e->getMessage();
            return false;
        }
        return true;
}


//This function takes a date string in the form "Year-Month-Date" & converts it to "Month Date, Year"
//The "Month" as its full letter name, "Date" as a Number, "Year" as full number
function dateFormatter($date){

    echo date_format(new DateTime($date), 'F d, Y');

}


//This function takes in a HTML date input of type string, (Year-Month-Year) 
//& returns Boolean If the format is correct
function validDate($date){

    $converted_date = explode('-', $date);

    $month = intval($converted_date[1]);
    $day = intval($converted_date[2]);
    $year = intval($converted_date[0]);

    if(checkdate($month, $day, $year) == FALSE){
        return FALSE;
    }else{
        return TRUE;
    }
}