<?php

require_once DATABASE;

/* 
A domain Class to demonstrate RESTful web services
*/
Class payTmModel {
      
    function getAllProcessOrderData() {
        
        $dbConn = new DBConn();

        $query = "SELECT * FROM orderprocessmaster WHERE status = 0";
        
        $rows = $dbConn->getAll($query);
        
        unset($dbConn);        
        
       return array("status"=>200, "data" => $rows, "message"=>'order List');
                  
    }
    
    

    function updateOrderProcessTable($formData) {

    try {
        
        $dbConn = new DBConn();

        $query = "SELECT r.id AS rId, r.mobile, r.fname, r.mname, r.lname, r.email, p.id AS prodId, k.id AS pkgId, k.amount FROM registrationmaster r "
                . "LEFT JOIN `candidateproffesionaldetail` p ON r.id = p.rId "
                . "LEFT JOIN `packagemaster` k ON r.id = k.rId WHERE r.id = '$formData->registrationId' AND r.isActive = 1";
        
            $row = (object) $dbConn->getOne($query);
        
            if($row->rId != NULL) :
            
                $data     = array();
                $status   = 200;
                $username = 'SR2019';
                $password = md5($row->mobile);


                $query = "SELECT count(*) AS total FROM candidatemaster WHERE rId = '$row->rId'";
                $count = $dbConn->getOne($query);
            
                if($count['total'] > 0) :

                    return array("status" => 400,"message" => "Candidate allready exists.");          

                else :

                    $query = "INSERT INTO candidatemaster (rId, prodId, pkgId, password, orderId, registrationId) "
                    ."VALUES('$row->rId', '$row->prodId', '$row->pkgId', '$password', '$formData->orderId', '$formData->cusRegistrationId')";     

                    if($candidateId  = $dbConn->executeQuery($query)) :
                        
                        $query = "UPDATE candidatemaster SET username = '$username$candidateId' WHERE id = '$candidateId'";
                        $dbConn->executeUpdateQuery($query);
                        
                        $query = "UPDATE orderprocessmaster SET status = 1, updatedAt = now() WHERE rId = '$formData->registrationId'";
                        $dbConn->executeUpdateQuery($query);
                      
                        $data = ['fname'=>$row->fname,'mname'=>$row->mname,'lname'=>$row->lname,
                        'email'=>$row->email,'mobile'=>$row->mobile,'amount'=>$row->totalAmt,'username'=>($username.$candidateId),'password'=>$row->mobile];
                        $message = "Candidate is registered successfully";
                        
                    else :
                        
                        $status  = 400;
                        $message = "Something went wrong!";   
                        
                    endif;
                    
                    unset($dbConn);

                    return array("status" => $status, "message" => $message);    

                endif;


            else :

                unset($dbConn);
                return array("status" => 400,"message" => "No record map with registration id");          

            endif;

        } catch(Exception $e) {

            return array("status"=>500,"message"=>$e->getMessage());

        }

    }
 
    function updateOnlyStatus($formData) {
        
        $dbConn = new DBConn();

        $query = "UPDATE orderprocessmaster SET status = 1, updatedAt = now() WHERE rId = '$formData->registrationId'";
        $dbConn->executeUpdateQuery($query);
        
        unset($dbConn);        
        
       return array("status"=>200, "message"=>'done');
                  
    }
	
	
}