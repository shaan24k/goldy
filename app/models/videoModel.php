<?php

require_once DATABASE;

/* 
A domain Class to demonstrate RESTful web services
*/
Class videoModel {
      
     
    function saveVideoIntro($formData) {

    try {
        
        $dbConn = new DBConn();

        $query = "INSERT INTO candidateintroduction (cid, payment, amount, browserAgent) VALUES ('$formData->cid', '1', '$formData->amount', '$formData->userAgent')";
        
            $videoId = $dbConn->executeQuery($query);
        
            if($videoId != NULL) :
                
                $query = "SELECT r.folderId FROM registrationmaster r LEFT JOIN candidatemaster c ON c.rId = r.id WHERE c.id = '$formData->cid'";
                $folderRow = $dbConn->getOne($query);

            
                $videoUrl = PUBLIC_DIR.$folderRow['folderId'].'/'.'video/';
                $userFolder = PUBLIC_DIR.$folderRow['folderId'];
                $videoFolder = PUBLIC_DIR.$folderRow['folderId'].'/'.'video';

               if(!is_dir($videoUrl)) :
                   
                   chmod($userFolder, 0777);
                   mkdir($userFolder);
                   
                   chmod($videoFolder, 0777);
                   mkdir($videoFolder);        
                   
               endif;
                               
                $videoUrl = $videoUrl.'/'.$formData->cid.$videoId.'.webm'; 
                 
                $this->uploadDocuments($videoUrl); 
                 
                $query = "UPDATE candidateintroduction SET videoUrl = '$videoUrl' WHERE id = '$videoId'";
                $dbConn->executeQuery($query);

                unset($dbConn);
                $response = array("status"=>200, 'videoUrl' =>$videoUrl, "message"=>'Introduction save successfully');
                
            else :
                
                unset($dbConn);
                $response = array("status"=>400,"message"=>'error in query');
 
            endif;
            
            return $response;

        } catch(Exception $e) {

            return array("status"=>500,"message"=>$e->getMessage());

        }

    }
 

    
    
    function getVideoIntroList($id) {
        
        $dbConn = new DBConn();

        $query = "SELECT id,videoUrl,DATE_FORMAT(createdAt, '%M %d,%Y') FROM candidateintroduction WHERE cid = '$id' ORDER BY id DESC";
        
        $rows = $dbConn->getAll($query);
        
        unset($dbConn);
        
        
        $isAdmin = ($id == 1) ? 1 : 0;             
        
       return array("status"=>200, "total" => count($rows), "isAdmin" => $isAdmin, "data" => $rows, "message"=>'Video Introduction List');
                  
    }
    
    
   private function uploadDocuments($folderId) {
       
       try {                               
               $userTempFile = $_FILES['video']['tmp_name'];
               $target_file  = $folderId;
               move_uploaded_file($userTempFile, $target_file);             
           
           return TRUE;
                  
           
       } catch (Exception $e) {
           
           return $e->getMessage();
       }
       
       
   }
    
    
    
  
    
    
    
    
	
	
}