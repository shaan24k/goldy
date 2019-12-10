<?php

require_once DATABASE;

class auth {

   
    function authorize() {

        try {   
   
               $token = null;
            
              $headers = apache_request_headers();

                foreach ($headers as $header => $value) :
            
                    if(strtolower($header)=='token') :

                      $token =  $value; 

                    endif;
           
                endforeach; 
                
              
            if($token!=null) :
                
                
                $dbConn = new DBConn();
            
                $query = "SELECT cid as id FROM sessiontoken WHERE token = '$token' AND deletedAt IS NULL";
                $row = $dbConn->getOne($query);
                
                unset($dbConn);
                
             
                if($row['id']!=NULL || !empty($row['id'])) :
                    
                  $res = array('status' => 200, 'flag' => 1, 'id' => $row['id']);
                
                else :
                    
                    $res = array('status' => 200, 'flag' => 0, 'id' => null);
                    
                endif;
                
                return $res;    
                
            else :
                
              return array("status"=>400,"message"=>'token is empty in header request');
                
            endif;  
            
            }  catch(Exception $e) {

                return array("status"=>500,"message"=>$e->getMessage());
            }  
            
            
    }
   

   
   
   
                
                   
}
