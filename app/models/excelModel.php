<?php

require_once DATABASE;
require_once(LIB.'/php-excel-reader/SpreadsheetReader.php');
/* 
A domain Class to demonstrate RESTful web services
*/
Class excelModel {
        
    function importCategories() {

        if(isset($_FILES['excel']['name'])) :


            $target_file = './'.$_FILES['excel']['name'];
            $tempfile = $_FILES['excel']['tmp_name'];
            
            if (move_uploaded_file($_FILES["excel"]["tmp_name"], $target_file)) :
           
            else :
                
                return writeError("Sorry, there was an error uploading your file.");
            
            endif;

            
            $Reader;
            $catID;
            $index;
            $subCatID;
            
            try {

                $Reader = new SpreadsheetReader($target_file);

            }
            catch(Exception $e) {

                return array("status"=>500,"message"=>$e->getMessage());
            }  
            
            
            $Sheets = $Reader -> Sheets();

            $Reader -> ChangeSheet(2);
            
            $data = array();

            foreach ($Reader as $Row) :              
                        
                //array_push( $data, array('category' =>$Row[0], 'sub-category' => $Row[1], 'role' => $Row[2]));
                
                if(!empty($Row[0])) :
                    
                    $dbConn = new DBConn();   
                    $category = addslashes(trim($Row[0]));  
                
                    $query = "INSERT industrymaster (title) SELECT '$category'
                    WHERE NOT EXISTS (SELECT id FROM industrymaster WHERE title = '$category') LIMIT 1";
                    
                    $catID = $dbConn->executeQuery($query);
                    
                    unset($dbConn);              
                    
                endif;
                
                
                if(!empty($Row[1])) :
                
                    $dbConn = new DBConn();   
                    $subcategory = addslashes(trim($Row[1]));  
                    
                    $query = "INSERT departmentmaster (cid, title) SELECT '$catID', '$subcategory'
                    WHERE NOT EXISTS (SELECT id FROM departmentmaster WHERE cid = '$catID' AND title = '$subcategory') LIMIT 1";
                    
                    $subCatID = $dbConn->executeQuery($query);
                    
                    unset($dbConn);
                    
                    
                endif;
 
                
                if(!empty($Row[2])) :
                    
                    $dbConn = new DBConn();   
                    $role = addslashes(trim($Row[2]));   

                    $query = "INSERT rolemaster (cid, scid, title) SELECT '$catID', '$subCatID', '$role'
                    WHERE NOT EXISTS (SELECT id FROM rolemaster WHERE cid = '$catID' AND scid = '$subCatID' AND title = '$role') LIMIT 1";
                    
                    $dbConn->executeQuery($query);
                    
                    unset($dbConn);
                    
                    $index++;
                    
                endif;
               
            endforeach;
            
            return array("status"=>200,"record"=>$index, "message"=>"excel import successfylly"); 
                   
        
        else :
            
            return array("status"=>500,"message"=>"excel file not upload correctly");
            
        endif;                         		
    }
    
	
}