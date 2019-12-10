<?php

require_once AUTH;
require_once  JOBMODEL;
require_once VALIDATION;

class jobController {
    
    
        function getrPack() {

            $ctrl = (isset($_GET['endpoint'])) ? $_GET['endpoint'] : 'rPackDetail';
            
            switch ($ctrl) :
                
                case "rPackDetail" :
                
                    $this->getrPackDetail();
                    
                break;

                case "availablity" :
                    
                   $this->checkRpackavailablity(); 
               
                break;  
                    
                case "questions" :
                    
                   $this->getrPackQuestions(); 
                    
                break;
            
                case "answers" :
                    
                   $this->saveRpackAnswer(); 
                    
                break;
            
                case "purchase" :
                    
                   $this->getrPackPurchaseDetail(); 
                    
                break;
            
                case "apply" :
                    
                   $this->applySameQuestionAnswerSetToRpack(); 
                    
                break;
            
                case "retake" :
                    
                   $this->rPackRetake(); 
                    
                break;
                       
                default :
                    
                    $this->getrPackDetail();
                    
                break;

            
            endswitch;
            
 
	}
        
    
        function getJobOpeningList() {
            
            try {
                 
                   $authValn = new auth();
                    $middleWare = new validation();
                    $formData = $middleWare->validateParams(array('industry','department','role','page'));
                    
                    
                    if($formData['status'] == 200) :
                    
                    $authValn = new auth();
            
                    $rest = $authValn->authorize();
                                       
                    if($rest['status'] == 200) :
                        
                        if(($rest['flag'] == 0) && ($rest['flag'] == null)) :
                            
                            $rawData = array("status"=>400,"message"=>'token is not valid');
                         
                        else :
                            
                            $formData['data']->cid = $rest['id'];
                           
                            $jobModel = new jobModel();
                            $rawData = $jobModel->getJobOpeningList( $formData['data']);
                            
                        endif;
                        
                    else :
                        
                     $rawData =  $rest;  
                        
                    endif;                	
                
                else :
                    
                    $rawData = $formData; 
                
                endif;
                      
		$response = $this->encodeJson($rawData);
                        
		echo $response;	
                
            } catch (Exception $e) {

                $res = ['status' => 500, 'message' => $e->getMessage()];

                echo $this->encodeJson($res); die;
            }
            
	}
        
        
        function getrPackDetail() {
            
            try {
                
                $middleWare = new validation();
                
                $middleWare->dd('Api is under construction screen is not avilable t');
                
                 
                   $authValn = new auth();
                    
                    $formData = $middleWare->validateParams(array('industry','department','role','page'));
                    
                    
                    if($formData['status'] == 200) :
                    
                    $authValn = new auth();
            
                    $rest = $authValn->authorize();
                                       
                    if($rest['status'] == 200) :
                        
                        if(($rest['flag'] == 0) && ($rest['flag'] == null)) :
                            
                            $rawData = array("status"=>400,"message"=>'token is not valid');
                         
                        else :
                            
                            $formData['data']->cid = $rest['id'];
                           
                            $jobModel = new jobModel();
                            $rawData = $jobModel->getJobOpeningList( $formData['data']);
                            
                        endif;
                        
                    else :
                        
                     $rawData =  $rest;  
                        
                    endif;                	
                
                else :
                    
                    $rawData = $formData; 
                
                endif;
                      
		$response = $this->encodeJson($rawData);
                        
		echo $response;	
                
            } catch (Exception $e) {

                $res = ['status' => 500, 'message' => $e->getMessage()];

                echo $this->encodeJson($res); die;
            }
            
	}
        
        
        function checkRpackavailablity() {
            
            try {
                
                   $middleWare = new validation();
                   $authValn   = new auth();
                    
                    $formData = $middleWare->validateParams(array('rpackId'));
                    
                    
                    if($formData['status'] == 200) :
                    
                    $authValn = new auth();
            
                    $rest = $authValn->authorize();
                                       
                    if($rest['status'] == 200) :
                        
                        if(($rest['flag'] == 0) && ($rest['flag'] == null)) :
                            
                            $rawData = array("status"=>400,"message"=>'token is not valid');
                         
                        else :
                            
                            $formData['data']->cid = $rest['id'];
                           
                            $jobModel = new jobModel();
                            $rawData = $jobModel->checkRpackavailablity( $formData['data']);
                            
                        endif;
                        
                    else :
                        
                     $rawData =  $rest;  
                        
                    endif;                	
                
                else :
                    
                    $rawData = $formData; 
                
                endif;
                      
		$response = $this->encodeJson($rawData);
                        
		echo $response;	
                
            } catch (Exception $e) {

                $res = ['status' => 500, 'message' => $e->getMessage()];

                echo $this->encodeJson($res); die;
            }
            
	}
        
       
        function getrPackQuestions() {
            
            try {
                
                   $middleWare = new validation();
                   $authValn   = new auth();
                    
                    $formData = $middleWare->validateParams(array('rpackId'));
                    
                    
                    if($formData['status'] == 200) :
                    
                    $authValn = new auth();
            
                    $rest = $authValn->authorize();
                                       
                    if($rest['status'] == 200) :
                        
                        if(($rest['flag'] == 0) && ($rest['flag'] == null)) :
                            
                            $rawData = array("status"=>400,"message"=>'token is not valid');
                         
                        else :
                            
                            $formData['data']->cid = $rest['id'];
                           
                            $jobModel = new jobModel();
                            $rawData = $jobModel->getRpackQuestionDetail( $formData['data']);
                            
                        endif;
                        
                    else :
                        
                     $rawData =  $rest;  
                        
                    endif;                	
                
                else :
                    
                    $rawData = $formData; 
                
                endif;
                      
		$response = $this->encodeJson($rawData);
                        
		echo $response;	
                
            } catch (Exception $e) {

                $res = ['status' => 500, 'message' => $e->getMessage()];

                echo $this->encodeJson($res); die;
            }
            
	}
        

        function saveRpackAnswer() {
            
            try {
            
		$model = new jobModel();
                $middleWare = new validation();            
                
                $formData = $middleWare->rpackAnsValidation();
                             
                if($formData['status'] == 200) :
                    
                   $authValn = new auth();
            
                    $rest = $authValn->authorize();
                                       
                    if($rest['status'] == 200) :
                        
                        if(($rest['flag'] == 0) && ($rest['flag'] == null)) :
                            
                            $rawData = array("status"=>400,"message"=>'token is not valid');
                         
                        else :
                            
                            $data = $formData['data'];
                            
                            $data->cid = $rest['id'];
                
                            $rawData = $model->saverPackQuestionAnswer($data);
                            
                        endif;
                        
                    else :
                        
                     $rawData =  $rest;  
                        
                    endif;                	
                
                else :
                    
                    $rawData = $formData; 
                
                endif;
                  
		$response = $this->encodeJson($rawData);
                        
		echo $response;	
                
            } catch (Exception $e) {

                $res = ['status' => 500, 'message' => $e->getMessage()];

                echo $this->encodeJson($res); die;
            }
            
	}
       
        
        function getrPackPurchaseDetail() {
            
            try {
                
                   $middleWare = new validation();
                   $authValn   = new auth();
                    
                    $formData = $middleWare->validateParams(array('rpackId'));
                    
                    
                    if($formData['status'] == 200) :
                    
                    $authValn = new auth();
            
                    $rest = $authValn->authorize();
                                       
                    if($rest['status'] == 200) :
                        
                        if(($rest['flag'] == 0) && ($rest['flag'] == null)) :
                            
                            $rawData = array("status"=>400,"message"=>'token is not valid');
                         
                        else :
                            
                            $formData['data']->cid = $rest['id'];
                           
                            $jobModel = new jobModel();
                            $rawData = $jobModel->getRpackPurchaseDetail( $formData['data']);
                            
                        endif;
                        
                    else :
                        
                     $rawData =  $rest;  
                        
                    endif;                	
                
                else :
                    
                    $rawData = $formData; 
                
                endif;
                      
		$response = $this->encodeJson($rawData);
                        
		echo $response;	
                
            } catch (Exception $e) {

                $res = ['status' => 500, 'message' => $e->getMessage()];

                echo $this->encodeJson($res); die;
            }
            
	} 
        
        
        function applySameQuestionAnswerSetToRpack() {
            
            try {
                
                   $middleWare = new validation();
                   $authValn   = new auth();
                    
                    $formData = $middleWare->ApplyRpackValidation();
                    
                    
                    if($formData['status'] == 200) :
                    
                    $authValn = new auth();
            
                    $rest = $authValn->authorize();
                                       
                    if($rest['status'] == 200) :
                        
                        if(($rest['flag'] == 0) && ($rest['flag'] == null)) :
                            
                            $rawData = array("status"=>400,"message"=>'token is not valid');
                         
                        else :
                            
                            $formData['data']->cid = $rest['id'];
                           
                            $jobModel = new jobModel();
                            $rawData = $jobModel->appySameQuestionSetToRpack($formData['data']);
                            
                        endif;
                        
                    else :
                        
                     $rawData =  $rest;  
                        
                    endif;                	
                
                else :
                    
                    $rawData = $formData; 
                
                endif;
                      
		$response = $this->encodeJson($rawData);
                        
		echo $response;	
                
            } catch (Exception $e) {

                $res = ['status' => 500, 'message' => $e->getMessage()];

                echo $this->encodeJson($res); die;
            }
            
	} 
 
        
        function rPackRetake() {
            
            try {
                
                   $middleWare = new validation();
                   $authValn   = new auth();
                    
                    $formData = $middleWare->ApplyRpackValidation();
                    
                    
                    if($formData['status'] == 200) :
                    
                    $authValn = new auth();
            
                    $rest = $authValn->authorize();
                                       
                    if($rest['status'] == 200) :
                        
                        if(($rest['flag'] == 0) && ($rest['flag'] == null)) :
                            
                            $rawData = array("status"=>400,"message"=>'token is not valid');
                         
                        else :
                            
                            $formData['data']->cid = $rest['id'];
                           
                            $jobModel = new jobModel();
                            $rawData = $jobModel->appySameQuestionSetToRpack($formData['data']);
                            
                        endif;
                        
                    else :
                        
                     $rawData =  $rest;  
                        
                    endif;                	
                
                else :
                    
                    $rawData = $formData; 
                
                endif;
                      
		$response = $this->encodeJson($rawData);
                        
		echo $response;	
                
            } catch (Exception $e) {

                $res = ['status' => 500, 'message' => $e->getMessage()];

                echo $this->encodeJson($res); die;
            }
            
	} 
 

	public function encodeJson($responseData) {
            
		$jsonResponse = json_encode($responseData);
                
		return $jsonResponse;		
	}
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
}
