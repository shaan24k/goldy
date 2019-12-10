<?php

require_once DATABASE;

/* 
A domain Class to demonstrate RESTful web services
*/
Class userModel {
      
    function industriesList() {
            
            try {

                $c      = 0;
                $cid    = 0;
                $sid    = 0;
                $data   = [];
                $dbConn = new DBConn();   
                
//                $query = "SELECT c.id AS cat_id, c.title, s.id AS sub_id, s.cid, s.title AS subtitle, r.id AS role_id, r.cid, r.scid, r.title AS roletitle FROM `industrymaster` c 
//
//                LEFT JOIN `departmentmaster` s ON s.cid = c.id LEFT JOIN `rolemaster` r ON r.scid = s.id WHERE c.isActive = 1 AND s.isActive = 1 AND r.isActive = 1";
                
                $query = "SELECT distinct (r.title) AS roletitle, i.id AS cat_id, i.title, d.id AS sub_id, d.cid, d.title AS subtitle, r.id AS role_id, r.cid, r.scid FROM `rolemaster` r

                LEFT JOIN `departmentmaster` d ON d.id = r.scid 

                LEFT JOIN `industrymaster` i ON i.id = r.cid

                INNER JOIN `questionsetmaster` q ON q.jobRoleId = r.id

                INNER JOIN `rpackmaster` rp ON rp.questionSetId = q.id

                WHERE q.isActive = 1 AND rp.isActive = 1";
                
                
                $result = $dbConn->getAll($query);
                                
                foreach($result as $row) :
   
                                        
                    if($cid != $row['cat_id']) :
                        
                        $s   = 0;
                        $cid = $row['cat_id'];
                    
                        $data[$c++] = ['id' => $row['cat_id'],'title' => $row['title']];                       
                        
                    endif;
                    
           
                   if($sid != $row['sub_id']) :
                        
                        $r   = 0;
                        $sid = $row['sub_id'];
                        $data[$c-1]['subcategories'][$s++]  = ['id' => $row['sub_id'], 'cid' => $row['cid'], 'title' => $row['subtitle']];
                        
                    endif;
                    
                    
                      $data[$c-1]['subcategories'][$s-1]['role'][$r++] = ['id' => $row['role_id'], 'cid' => $row['cid'], 'sid' => $row['scid'],'title' => $row['roletitle']];  

                endforeach;
                
                unset($dbConn);
                
                return array("status" => 200,"industries" => $data, "message" => "Industries List");   
                
            } catch(Exception $e) {

                return array("status"=>500,"message"=>$e->getMessage());
            }  
             		
    }
    
    
    function checkUserExists($formData) {
        
        try {
        
            $dbConn = new DBConn();
            
            $query = "SELECT COUNT(*) AS total, (SELECT COUNT(*) FROM candidatemaster WHERE rId = '$formData->formId') AS canTotal FROM registrationmaster r "
            . "INNER JOIN candidatemaster c ON c.rId = r.id WHERE r.mobile = '$formData->mobile' AND r.email = '$formData->email' AND r.isActive = 1";
            
                    
            if($result = $dbConn->getOne($query)) :
             
                unset($dbConn);
            
                return ($result['total'] > 0 || $result['canTotal'] > 0) ? 1 : 0; 
                
            else :

                unset($dbConn);
                return 0;

            endif;
        
        } catch(Exception $e) {
            
            unset($dbConn);
            return array("status"=>500,"message"=>$e->getMessage());
            
        }

    }
    
    
    function newRegisterationId() {
        
        try {
            
                  
            $dbConn = new DBConn();

            $query = "INSERT INTO registrationmaster (isActive) VALUES(0)";

            if($ExId = $dbConn->executeQuery($query)) :

                unset($dbConn);
                return array('status'=>200, 'id'=>$ExId, 'message'=>'New registration Id');

            else :

                unset($dbConn);
                return array('status'=>400, 'message'=>'Error occur');

            endif;
            
        } catch(Exception $e) {
            
            return array("status"=>500,"message"=>$e->getMessage());
            
        }


    }
    
    
    function registeration($formData) {
        
        try {
            
            
         //   print_r(json_encode($formData)); die;

            $data     = array();
            $flag     = 0;
            $status   = 200;
            $username = 'SR2019';
            $password = md5($formData->mobile);
            $dbConn   = new DBConn();     
            $registrationId = $formData->formId;
            $userNameFolder = 'SR2019'.$registrationId;

            $query = "UPDATE `registrationmaster` SET `fname`='$formData->fname', `mname`='$formData->mname', `lname`='$formData->lname', `email`='$formData->email', "

            . "`mobile`='$formData->mobile', `gender`='$formData->gender', `highestQualification`='$formData->highestQualification', `careerCentre`='$formData->modelCareerCentre', "

            . "`jobCategory`='$formData->jobCategory', `jobSubcategory`='$formData->jobSubCategory', `jobRole`='$formData->jobRole', `expectedCTC`='$formData->expectedCTC', "

            . "`preferredLocation1`='$formData->preferredLocation1', `preferredLocation2`='$formData->preferredLocation2', `preferredLocation3`='$formData->preferredLocation3', "

            . " `experienced` = '$formData->experienced', `aadhaarNo`='$formData->aadhaarCardNo', `passportNo`='$formData->passwortNo', `panNo`='$formData->panCardNo', "

            . "`isActive`='1', `updateAt`=now() WHERE id = $registrationId";
        
                if($dbConn->executeUpdateQuery($query)) :
                    
                    $mname = (empty($formData->mname)) ? ' ' : ' '.$formData->mname.' ';                
                    $name = ucwords(strtolower($formData->fname.$mname.$formData->lname));
                    
                    
                    $query = "INSERT INTO packagemaster (rId,package,amount) VALUES('$registrationId', '$formData->package', '$formData->totalAmt')";
                    
                    ($formData->experienced == 0) ? $dbConn->executeUpdateQuery($query) : '';
                
                    $data = ['registrationId' => $registrationId, 'name' => $name,'email' => $formData->email,'mobile' => $formData->mobile, 'totalAmt' => $formData->totalAmt];
                
                   if($formData->experienced > 0) :                
                   
                        $query = "INSERT INTO candidatemaster (rId, password) VALUES('$registrationId', '$password')";     

                        if($candidateId  = $dbConn->executeQuery($query)) :
                            $query = "UPDATE candidatemaster SET username = '$username$candidateId' WHERE id = '$candidateId'";
                            $dbConn->executeUpdateQuery($query);
                        endif; 
                        
                        
                        $flag = 1;
                        $data = ['fname'=>$formData->fname,'mname'=>$formData->mname,'lname'=>$formData->lname,
                        'email'=>$formData->email,'mobile'=>$formData->mobile, 'username'=>($username.$candidateId),'password'=>$formData->mobile];
                       
                   endif;
                            
                        $userFolderId = $userNameFolder;

                        $dir = PUBLIC_DIR.$userFolderId.'/';

                        if(!is_dir($dir)) :
                           
                          chmod($dir, 0777);
                          mkdir($dir);

                        endif;
    
                        $query =  "UPDATE registrationmaster SET folderId = '$userFolderId' WHERE id = '$registrationId'";
                        $dbConn->executeQuery($query);
                        unset($dbConn);
                        
                        $this->uploadDocuments($dir);                                                            
                                                                  
                        return array("status" => 200, 'flag' => $flag, "data" => $data, "message" => "User registration successfully completed");   
                    
                else :

                    unset($dbConn);
                    return array("status" => 500, 'flag' => $flag, "message" => "Something went wrong!");                 
                
                endif;
                    
            
        } catch(Exception $e) {
            
            return array("status"=>500, 'flag' => 0, "message"=>$e->getMessage());
            
        }
        
        
        
    }
    
    
    function setPaymentProcess($formData) {

    try {
        
        $dbConn = new DBConn();

        $query = "SELECT count(*) as total FROM registrationmaster WHERE id = '$formData->rId'";
        
            $row = (object) $dbConn->getOne($query);
        
            if($row->total > 0) :
            

                $query = "SELECT count(*) AS total FROM orderprocessmaster WHERE rId = '$formData->rId' AND orderId = '$formData->orderId'";
                $count = (object) $dbConn->getOne($query);
            
                if($count->total == 0) :

                    $query = "INSERT INTO orderprocessmaster (rId, orderId, amount) VALUES('$formData->rId', '$formData->orderId', '$formData->amount')";
             
                    if($dbConn->executeQuery($query)) :
                        
                        unset($dbConn);
                        return array("status" => 200, "message" => "process order successfully");
                    
                    else: 
                        
                        unset($dbConn);
                        return array("status" => 400, "message" => "something went wrong");  
                    
                    endif;

                else :
                   
                    unset($dbConn);
                    return array("status" => 400, "message" => "something went wrong");

                endif;

            else :

                unset($dbConn);
                return array("status" => 400,"message" => "No record map with registration id");          

            endif;

        } catch(Exception $e) {

            return array("status"=>500,"message"=>$e->getMessage());

        }

    }
    
    
    
    function setInterviewPaymentProcess($formData) {

    try {
        
        $dbConn = new DBConn();

        $query = "SELECT count(*) as total FROM candidatemaster WHERE id = '$formData->userId'";
        
            $row = (object) $dbConn->getOne($query);
        
            if($row->total > 0) :
            

                $query = "SELECT count(*) AS total FROM orderprocessmaster WHERE rId = '$formData->rId' AND orderId = '$formData->orderId'";
                $count = (object) $dbConn->getOne($query);
            
                if($count->total == 0) :

                    $query = "INSERT INTO orderprocessmaster (rId, orderId, rpackId, paymentFor, detail, amount) "
                    . "VALUES('$formData->rId', '$formData->orderId', '$formData->rpackId', '$formData->paymentFor', '$formData->detail', '$formData->amount')";
                

                    if($instId = $dbConn->executeQuery($query)) :
                        
                        unset($dbConn);
                        return array("status" => 200, 'id' => $instId, "message" => "process order successfully");
                    
                    else: 
                        
                        unset($dbConn);
                        return array("status" => 400, "message" => "something went wrong");  
                    
                    endif;

                else :
                   
                    unset($dbConn);
                    return array("status" => 400, "message" => "something went wrong");

                endif;

            else :

                unset($dbConn);
                return array("status" => 400,"message" => "No record map with registration id");          

            endif;

        } catch(Exception $e) {

            return array("status"=>500,"message"=>$e->getMessage());

        }

    }
    
    
    
    function setPaymentSuccess($formData)  {

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

                    return array("status" => $status, "data" => $data, "message" => $message);    

                endif;


            else :

                unset($dbConn);
                return array("status" => 400,"message" => "No record map with registration id");          

            endif;

        } catch(Exception $e) {

            return array("status"=>500,"message"=>$e->getMessage());

        }

    }
 
    
    function setInterViewPaymentSuccess($formData)  {

    try {
        
        $flag   = 0;
        $status = 200;
        $dbConn = new DBConn();

        $query = "SELECT o.id, c.id AS cId,o.rpackId,o.amount FROM `candidatemaster` c INNER JOIN `registrationmaster` r ON r.id = c.rId "
               . "INNER JOIN `orderprocessmaster` o ON o.rId = r.id WHERE r.id = '$formData->registrationId' AND o.id = '$formData->orderProcessId' AND o.orderId = '$formData->orderId'";
        
            $row = (object) $dbConn->getOne($query);
            
            if($row->id != NULL) :

                    $query = "INSERT INTO candidatepaymentdetail (candidateId, rPackId, reTake, paymentType, amount, status, createdAt) "
                    ."VALUES('$row->cId', '$row->rpackId', '$formData->reTake', 'interview', '$row->amount', '1', now())";     

                    if($candidateId  = $dbConn->executeQuery($query)) :
                                           
                        $query = "UPDATE orderprocessmaster SET status = 1, updatedAt = now() WHERE id = '$row->id'";
                        $dbConn->executeUpdateQuery($query);
                      
                        if($formData->reTake == 1) :                         
                            $query = "UPDATE candidateinterviews SET reTake = 1, lastReTakeDate = now() WHERE candidateId = '$row->cId' AND rpackId = '$row->rpackId'";
                            $dbConn->executeUpdateQuery($query); 
                        endif;
                                              
                        $flag = 1;
                        $message = "Payment successfully done";
                        
                    else :
                        
                        $status  = 400;
                        $message = "Something went wrong!";   
                        
                    endif;
                    
                        unset($dbConn);
                        return array("status" => $status, "flag" => $flag, "message" => $message);    

                else :
                    
                    unset($dbConn);
                    return array("status" => $status, "flag" => 0, "message" => $message);
                
                endif; 

        } catch(Exception $e) {

            return array("status"=>500,"message"=>$e->getMessage());

        }

    }
 


    function Login($formData) {

        try {

            $dbConn = new DBConn();
            $password = md5($formData->password);
            $token = bin2hex(openssl_random_pseudo_bytes(32));

//            $query = "SELECT c.id, r.fname, r.mname, r.lname, r.mobile, r.email, "
//
//                    . "(SELECT COUNT(*) FROM candidateintroduction WHERE cid = c.id) AS flag "
//
//                    . "FROM candidatemaster c LEFT JOIN `registrationmaster` r ON r.id = c.rId "
//
//                    . "WHERE c.username = '$formData->username' AND c.password = '$password'";
            
            
            $query = "SELECT c.id, res.fname, res.mname, res.lname, res.mobile, res.email, res.folderId, (SELECT COUNT(*) FROM candidateintroduction WHERE cid = c.id) AS flag ,

            i.id AS cat_id, i.title, d.id AS sub_id, d.cid, d.title AS subtitle, r.id AS role_id, r.cid, r.scid, r.title AS roletitle

            FROM candidatemaster c 

            LEFT JOIN `registrationmaster` res ON res.id = c.rId 

            LEFT JOIN `industrymaster` i ON i.id = res.jobCategory

            LEFT JOIN `departmentmaster` d ON d.id = res.jobSubcategory 

            INNER JOIN `rolemaster` r ON r.id = res.jobRole

            WHERE c.username = '$formData->username' AND c.password = '$password'";

            $row = (object) $dbConn->getOne($query);
            

            if($row->id != NULL) :

                $data = array('id' => $row->id, 'fname'=> $row->fname, 'mname' => $row->mname, 'lname' => $row->lname,        
                'mobile' => $row->mobile, 'username' => $formData->username, 'email' => $row->email, 'videoRecord' => $row->flag,
                    
                    'imagesrc' => '/'.PUBLIC_DIR.$row->folderId.'/photo.jpg',
                    
                    "industries" => array('category_id' => $row->cat_id, 
                                        'subcategory_id' => $row->sub_id, 
                                        'jobrole_id' => $row->role_id, 
                                        'category_title' => $row->title,
                                        'subcategory_title' => $row->subtitle,
                                        'jobrole_title' => $row->roletitle)
                    );

                $query = "SELECT token FROM sessiontoken WHERE cid = '$row->id' AND deletedAt IS NULL";
                $rowToken = $dbConn->getOne($query);

                if($rowToken['token']!=NULL || !empty($rowToken['token'])) :

                    unset($dbConn);

                    $data['token'] = $rowToken['token'];

                    return array("status" => 200, "data" => $data, "message" => "Use given token for dashboard API's");    


                else :

                    $query = "INSERT INTO sessiontoken (cid, token, agent) VALUES('$row->id', '$token', '$formData->userAgent')";     

                    $dbConn->executeQuery($query);
                    unset($dbConn);

                    $data['token'] = $token;

                    return array("status" => 200, "data" => $data, "message" => "Use given token for dashboard API's");    


                endif;


            else :

                unset($dbConn);
                return array("status" => 400,"message" => "Username or password is incorrect.");          

            endif;

        } catch(Exception $e) {

            return array("status"=>500,"message"=>$e->getMessage());

        }

    }

    
    function Logout($cid) {
        
        try {    
                              
            $dbConn = new DBConn();

                $query = "UPDATE sessiontoken SET deletedAt = now() WHERE cid = '$cid' AND deletedAt IS NULL";
                $dbConn->executeQuery($query);
  
                unset($dbConn);
                return array('status'=>200, 'flag' => 1, 'message'=>'Logout successfully');
            
        } catch(Exception $e) {
            
            return array("status"=>500, 'flag' => 0, "message"=>$e->getMessage());
            
        }


    }
    

    
    function getUserProfileDetail($id) {
        
        try {
            
            $dbConn = new DBConn();
            
               $query = "SELECT r.*, d.*, p.*, c.username, c.registrationId FROM `candidatemaster` c LEFT JOIN `registrationmaster` r ON r.id = c.rId "
                       . "LEFT JOIN `candidateproffesionaldetail` d ON d.id = c.prodId LEFT JOIN `packagemaster` p ON p.id = c.pkgId WHERE c.id = '$id'";
               
                $row = (object) $dbConn->getOne($query);
                
                unset($dbConn);
                
                if($row->id!=NULL) :
                                    
                    $row->id = $id;
                    $row->folderPath = PUBLIC_DIR.$row->folderId.'/';
                    unset($row->rId);
                    unset($row->isActive);
                    unset($row->folderId);
                    unset($row->createdAt);
                    unset($row->updateAt);
                    
                    return array("status" => 200, "data" => $row, "message" => "User profile detail");          
                
                else :
                   
                    return array("status" => 200, "data" => [], "message" => "Profile detail is empty");          

                endif;


                
            
        } catch(Exception $e) {

            return array("status"=>500,"message"=>$e->getMessage());

        }
        
    }


    private function candidateRegister($formData) {
        
         try { 
             
                $query = "INSERT INTO candidatemaster (rId, password) VALUES('$formData->rId', '$formData->password')";     

                    if($candidateId  = $dbConn->executeQuery($query)) :
                        
                        $query = "UPDATE candidatemaster SET username = 'SR2019$candidateId' WHERE id = '$candidateId'";
                        $dbConn->executeUpdateQuery($query);

                    
                    endif;    
                        
                        
             
         }catch (Exception $e) {
           
           return $e->getMessage();
       }
        

        
    }




    private function uploadDocuments($folderId) {
       
       try {
                      
           if($_POST['userImageType'] == 'file' && isset($_FILES['userImage']['name'])) :
              
               $userFileName = $_FILES['userImage']['name'];
               $userTempFile = $_FILES['userImage']['tmp_name'];
               $target_file  = $folderId.'photo.'.pathinfo($userFileName, PATHINFO_EXTENSION);
               move_uploaded_file($userTempFile, $target_file);
               
            endif;   
            
            if($_POST['userImageType'] != 'file' && isset($_POST['userImage'])) :
        
                $image_parts = explode(";base64,", trim($_POST['userImage']));
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $target_file  = $folderId.'photo.jpg';
                file_put_contents($target_file, $image_base64);
                
            endif;       
           
            if($_POST['aadhaarImageType'] == 'file' && isset($_FILES['aadhaarImage']['name'])) :
              
               $userFileName = $_FILES['aadhaarImage']['name'];
               $userTempFile = $_FILES['aadhaarImage']['tmp_name'];
               $target_file  = $folderId.'aadhaar-card.'.pathinfo($userFileName, PATHINFO_EXTENSION);
               move_uploaded_file($userTempFile, $target_file);
               
            endif; 
            
            if($_POST['aadhaarImageType'] != 'file' && isset($_POST['aadhaarImage'])) :

                $image_parts = explode(";base64,", trim($_POST['aadhaarImage']));
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $target_file  = $folderId.'aadhaar-card.jpg';
                file_put_contents($target_file, $image_base64);

            endif;
                     
            if($_POST['passwortImageType'] == 'file' && isset($_FILES['passwortImage']['name'])) :
              
               $userFileName = $_FILES['passwortImage']['name'];
               $userTempFile = $_FILES['passwortImage']['tmp_name'];
               $target_file  = $folderId.'passport.'.pathinfo($userFileName, PATHINFO_EXTENSION);
               
               move_uploaded_file($userTempFile, $target_file);
               
            endif;   
               
            if($_POST['passwortImageType'] != 'file' && isset($_POST['passwortImage'])) :                            
                
                $image_parts = explode(";base64,", trim($_POST['passwortImage']));
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $target_file  = $folderId.'passport.jpg';
                file_put_contents($target_file, $image_base64);

           endif;
           
            if($_POST['panCardImageType'] == 'file' && isset($_FILES['panCardImage']['name'])) :
              
               $userFileName = $_FILES['panCardImage']['name'];
               $userTempFile = $_FILES['panCardImage']['tmp_name'];
               $target_file  = $folderId.'pan-card.'.pathinfo($userFileName, PATHINFO_EXTENSION);
               
               move_uploaded_file($userTempFile, $target_file);
            
            endif;           
               
            if($_POST['panCardImageType'] != 'file' && isset($_POST['panCardImage'])) :  
                
                $image_parts = explode(";base64,", trim($_POST['panCardImage']));
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $target_file  = $folderId.'pan-card.jpg';
                file_put_contents($target_file, $image_base64);

           endif;
           
           return TRUE;
                  
           
       } catch (Exception $e) {
           
           return $e->getMessage();
       }
       
       
   }
    
    
    
  
    
    
    
    
	
	
}