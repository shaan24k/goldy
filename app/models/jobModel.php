<?php

require_once DATABASE;

/* 
A domain Class to demonstrate RESTful web services
*/
Class jobModel {
      
    
    function getJobOpeningList($formData) {
        
        $dbConn = new DBConn();
        
        $i     = 0;
        $total = 0;
        $limit = (isset($formData->limit)) ? (int) $formData->limit : 10;
        $record = 0;
        $arrayList = array();
        $page  = ($formData->page == 0) ? 0 : $formData->page; 
        $offset = $page * $limit;

        $query = "CALL `jobOpeningList`($formData->industry, $formData->department, $formData->role, $formData->cid, $limit, $offset)";
        
        $rows = $dbConn->getAll($query);
        
        unset($dbConn);
        
        if(!empty($rows)):
        
            foreach($rows as $val) :
                  
                $apply    = ($val['apply'] > 0) ? "1" : "0";
                $purchase = ($val['purchased'] > 0) ? "1" : "0";
                
                $finalApply = ($val['interviewId'] != null && $val['assesmentStatus'] != null) ? "2" : $apply;
                
                $interviewId     = $val['interviewId'];
                $assesmentStatus = $val['assesmentStatus'];
                
               if($val['assesmentStatus'] == 0 && $val['interviewId']!=NULL && $val['assesmentStatus']!=NULL) :
                   
                    $finalApply      = "0";
                    $purchase        = "1";
                    $interviewId     = null;
                    $assesmentStatus = null;
                   
               endif;
          
                $arrayList[$i]['id'] =  $val['id']; 
                $arrayList[$i]['questionSetId'] =  $val['questionSetId']; 
                $arrayList[$i]['locationCity'] =  $val['locationCity']; 
                $arrayList[$i]['client_name'] =  $val['client_name']; 
                $arrayList[$i]['designation'] =  $val['designation']; 
                $arrayList[$i]['experience'] =  $val['experience']; 
                $arrayList[$i]['vacancies'] =  $val['vacancies']; 
                $arrayList[$i]['ctcPannum'] =  $val['currency'].' '.$val['ctcPannum']; 
                $arrayList[$i]['interviewId'] = $interviewId; 
                $arrayList[$i]['assesmentStatus'] =  $assesmentStatus; 
                $arrayList[$i]['apply'] =   $finalApply;                                         
                $arrayList[$i]['purchased'] = ($val['apply'] == 1) ? "1" : $purchase;
                
                $i++;
            
            endforeach;
            
        endif;
        
        $isAdmin = ($formData->cid == 1) ? 1 : 0;   
        
        if(!empty($rows)) :
            
            $total = $rows[0]['total'];
            $record = $rows[0]['recordedInterviews'];
            
        endif;
        
       return array("status"=>200, "limit" => $limit, "offset" => $page, "total" => (int) $total, "record" => (int) $record, "isAdmin" => $isAdmin, "data" => $arrayList, "message"=>'Job Opening List');
                  
    }
    

    function checkRpackavailablity($formData) {
        
        $flag   = 0;
        $status = 200;
        $dbConn = new DBConn();

       $query = "SELECT COUNT(p.id) as paymentFlag, "
               . "(SELECT COUNT(id) FROM `candidateinterviews` WHERE candidateId = $formData->cid AND rpackId = $formData->rpackId AND status = 1) AS interviewFlag "
               . "FROM candidatepaymentdetail p WHERE p.candidateId = $formData->cid AND p.rPackId = $formData->rpackId";
               
        $rows = (object) $dbConn->getOne($query);
        
        unset($dbConn);

        $isAdmin = ($formData->cid == 1) ? 1 : 0;   
        
        if($rows->interviewFlag == 0 && $rows->paymentFlag == 0) :
            
            $flag = 1;
            $message = "Rpack is Available";
        
        elseif($rows->interviewFlag > 0 && $rows->paymentFlag == 0):    
            
            $flag = 0;
            $message = "Rpack Not Available"; 
            
        elseif($rows->interviewFlag > 0 && $rows->paymentFlag > 0):    
   
            $flag = 1;
            $message = "Rpack is Available";   
            
        endif;
        
       return array("status" => $status, "isAdmin" => $isAdmin, "flag" => $flag, "message" => $message);
                  
    }
    
    function getRpackQuestionDetail($formData) {
        
        $dbConn = new DBConn();

        $query = "SELECT q.id, r.id AS rpackId, q.questionSetId, q.heading, q.question, q.question_alt, q.type, timeLimit, timeUnit, "
        . "(SELECT a.answer FROM `interviewanswers` a INNER JOIN candidateinterviews c ON c.questionSetId = a.questionSetId WHERE a.questionId = q.id AND c.rpackId = $formData->rpackId AND c.candidateId = $formData->cid AND a.candidateId = $formData->cid) AS answer, "
        . "(SELECT DATE_FORMAT(a.createdAt, '%M %d,%Y') FROM `interviewanswers` a INNER JOIN candidateinterviews c ON c.questionSetId = a.questionSetId WHERE a.questionId = q.id AND c.rpackId = $formData->rpackId AND c.candidateId = $formData->cid AND a.candidateId = $formData->cid) AS answerDate "
        . "FROM questionmaster q INNER JOIN rpackmaster r ON r.questionSetId = q.questionSetId WHERE r.id = $formData->rpackId";
               
        $rows = $dbConn->getAll($query);
        
        unset($dbConn);

        $isAdmin = ($formData->cid == 1) ? 1 : 0;             
        
       return array("status"=>200, "total" => count($rows), "isAdmin" => $isAdmin, "data" => $rows, "message"=>'Rpack Questions List');
                  
    }
    
    
    function getRpackPurchaseDetail($formData) {
        
        $dbConn = new DBConn();

        $query = "SELECT id,designation,locationCity,testFees, (SELECT rId FROM `candidatemaster` WHERE id = $formData->cid) AS registrationId "
               . "FROM `rpackmaster` WHERE id = $formData->rpackId";
               
        $rows = $dbConn->getOne($query);
        
        unset($dbConn);

        $isAdmin = ($formData->cid == 1) ? 1 : 0; 
        
        $rows['userId'] = $formData->cid; 
        
       return array("status"=>200, "isAdmin" => $isAdmin, "data" => $rows, "message"=>'Rpack detail');
                  
    }
    
            
    function saverPackQuestionAnswer($formData) {

    try {
 
            $dbConn     = new DBConn();
            $flag       = 0;
            $formAnswer = null;                
            
           $query = "SELECT r.clientId, r.questionSetId, "
                  . "(SELECT c.id FROM `candidateinterviews` c WHERE c.rpackId = '$formData->rpackId' AND c.candidateId = '$formData->cid') AS id, "
                  . "(SELECT COUNT(q.id) FROM `questionmaster` q WHERE q.questionSetId = r.questionSetId AND q.id = '$formData->questionId') AS questionExists, "
                  . "(SELECT COUNT(q.id) FROM `questionmaster` q WHERE q.questionSetId = r.questionSetId) AS totalQuestions, "
                  . "(SELECT COUNT(a.id) FROM `interviewanswers` a WHERE a.questionSetId = r.questionSetId AND a.candidateId = '$formData->cid') AS totalAnswer, "
                  . "(SELECT COUNT(a.id) FROM `interviewanswers` a WHERE a.candidateId = '$formData->cid' AND a.questionSetId = r.questionSetId AND a.questionId = '$formData->questionId') AS total, "
                  . "(SELECT a.id FROM `interviewanswers` a WHERE a.candidateId = '$formData->cid' AND a.questionSetId = r.questionSetId AND a.questionId = '$formData->questionId') AS answerId, "
                  . "(SELECT a.answer FROM `interviewanswers` a WHERE a.candidateId = '$formData->cid' AND a.questionSetId = r.questionSetId AND a.questionId = '$formData->questionId') AS answer "
                  . "FROM rpackmaster r WHERE r.id = '$formData->rpackId'";
                                         
            $IntId = (object) $dbConn->getOne($query);
                            
            if($IntId->questionExists > 0) :
                
                if($IntId->id == NULL) :
         
                    $query = "INSERT INTO candidateinterviews (candidateId, clientId, rpackId, questionSetId) VALUES('$formData->cid', '$IntId->clientId', '$formData->rpackId', '$IntId->questionSetId')";  
                    $IntId->id = $dbConn->executeQuery($query);
                   
                endif;
                                                       
                $query = "INSERT INTO interviewanswers (candidateId, questionSetId, questionId, type, createdAt) VALUES('$formData->cid', '$IntId->questionSetId', '$formData->questionId', '$formData->type', now())";
                
                $answerId = ($IntId->total == 0) ? $dbConn->executeQuery($query) : $IntId->answerId;
                
                if($answerId != 0 && $answerId!=NULL) :
                                    
                    if($formData->type != 3) :

                        $query = "SELECT r.folderId FROM registrationmaster r LEFT JOIN candidatemaster c ON c.rId = r.id WHERE c.id = '$formData->cid'";
                        $folderRow = $dbConn->getOne($query);

                        $rpackFileUrl = PUBLIC_DIR.$folderRow['folderId'].'/'.'interviews/'.$IntId->id;                       
                        $interviewFolder = PUBLIC_DIR.$folderRow['folderId'].'/'.'interviews';

                        if(!is_dir($interviewFolder)) :

                          chmod($interviewFolder, 0777);
                          mkdir($interviewFolder);        

                        endif;
                        
                        if(!is_dir($interviewFolder.'/'.$IntId->id)) :

                          chmod($interviewFolder.'/'.$IntId->id, 0777);
                          mkdir($interviewFolder.'/'.$IntId->id);        

                        endif;

                          $rpackFileUrl = $rpackFileUrl.'/'.$answerId; 
                          
                          if($IntId->answer!=null) :
                              
                              unlink($IntId->answer);
                              
                          endif;
                          
                          $formAnswer = $this->uploadDocuments($rpackFileUrl); 
   
                endif;
                
                    $totalGivenAnswers = ($IntId->totalAnswer >= ($IntId->totalQuestions - 1)) ? $IntId->totalQuestions : $IntId->totalAnswer; 

                    if($IntId->totalQuestions == $totalGivenAnswers) :  // status update to 1 when candidate gives all answers
                        
                        $query = "UPDATE candidateinterviews SET status = '1', updatedAt = now() WHERE id = '$IntId->id'";   
                        $dbConn->executeQuery($query);
                        
                    endif;
                
                    $query = "UPDATE interviewanswers SET answer = '$formAnswer', updatedAt = now() WHERE id = '$answerId'";   
                    $dbConn->executeQuery($query);

                   unset($dbConn);
                   return array("status"=>200, "flag" => 1, "answerType" => $formData->type , "answer" => $formAnswer, "message"=>'answer save successfully');
                   
                else :
                    
                    unset($dbConn);
                    return array("status"=>200, "flag" => 0, "answerType" => $formData->type , "answer" => $formAnswer, "message"=>'answer not saved'); 
                    
                endif;
            
            else :
                
                unset($dbConn);
                return array("status"=>400, "flag" => $flag, "answerType" => $formData->type , "answer" => $formAnswer, "message"=>'Question id not map with this rPack');  
                
            endif;
            
            

        } catch(Exception $e) {

            return array("status"=>500, "flag" => 0, "message"=>$e->getMessage());

        }

    }
    
    
    function appySameQuestionSetToRpack($formData) {

        try {

                $dbConn = new DBConn();
                
                $query = "SELECT COUNT(c.id) AS total, r.questionSetId, r.clientId  FROM `rpackmaster` r INNER JOIN `candidateinterviews` c "
                        . "ON c.questionSetId = r.questionSetId WHERE r.id = $formData->rpackId AND c.candidateId = $formData->cid"; 
                                
                $IntId = (object) $dbConn->getOne($query);
                
                
                if($IntId->clientId != NULL && $IntId->questionSetId != NULL && $IntId->total > 0) :
                    
                    
                    $query = "SELECT count(id) as total FROM candidateinterviews WHERE candidateId = '$formData->cid' AND rpackId = '$formData->rpackId' AND questionSetId = '$IntId->questionSetId'";
                
                    $countDuplicacy = (object) $dbConn->getOne($query);
                                                    
                    if($countDuplicacy->total == 0) :
                        
                        $query = "INSERT INTO candidateinterviews (candidateId, clientId, rpackId, questionSetId, status) "
                            . "VALUES('$formData->cid', '$IntId->clientId', '$formData->rpackId', '$IntId->questionSetId', '1')";
                        
                        $dbConn->executeQuery($query);
                                               
                        $flag = 1;
                        $message = "Job Applied Successfully";
                    
                    else :
                        
                        $flag = 0;
                        $message = "Something went wrong";
                        
                    endif;

                elseif($IntId->clientId != NULL && $IntId->questionSetId != NULL && $IntId->total == 0) :
                    
                    $flag = 0;
                    $message = "Apply action only done on same question set";
                
                else :    
                    
                    $flag = 0;
                    $message = "Incorrect Information"; // this message show when rpack or questionSet or both not exists in database 
                    
                endif;
                
                unset($dbConn);
                
                return array("status"=>200, "flag" => $flag , "message"=> $message);


        } catch(Exception $e) {

                return array("status"=>500,"message"=>$e->getMessage());

        }
    
    }
 

    
    private function uploadDocuments($folderId) {
       
       try {   
               $ext = explode('/',$_FILES["answer"]["type"])[1];
               $userTempFile = $_FILES['answer']['tmp_name'];
               $target_file  = $folderId.'.'.$ext;
               move_uploaded_file($userTempFile, $target_file);             
           
           return $target_file;
                  
           
       } catch (Exception $e) {
           
           return $e->getMessage();
       }
       
       
   }
    
    
    
  
    
    
    
    
	
	
}