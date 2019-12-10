<?php

require_once AUTH;
require_once  VIDEOMODEL;
require_once VALIDATION;

class videoController {
    
 
        function saveVideoIntro() {
            
            try {
            
		$model = new videoModel();
                $middleWare = new validation();            
                
                $formData = $middleWare->videoIntroValidation();
                
                if($formData['status'] == 200) :
                    
                   $authValn = new auth();
            
                    $rest = $authValn->authorize();
                                       
                    if($rest['status'] == 200) :
                        
                        if(($rest['flag'] == 0) && ($rest['flag'] == null)) :
                            
                            $rawData = array("status"=>400,"message"=>'token is not valid');
                         
                        else :
                            
                            $formData['data']->cid = $rest['id'];
                            
                            $rawData = $model->saveVideoIntro($formData['data']);
                            
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
        
        function getVideoIntroList() {
            
            try {
                
                   $authValn = new auth();
            
                    $rest = $authValn->authorize();                              
                    
                    if($rest['status'] == 200) :
                        
                        if(($rest['flag'] == 0) && ($rest['flag'] == null)) :
                            
                            $rawData = array("status"=>400,"message"=>'token is not valid');
                         
                        else :
                            
                            $videoModel = new videoModel();
                            
                            $rawData = $videoModel->getVideoIntroList($rest['id']);
                            
                        endif;
                        
                    else :
                        
                     $rawData =  $rest;  
                        
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
