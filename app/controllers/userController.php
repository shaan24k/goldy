<?php

require_once AUTH;
require_once  USERMODEL;
require_once VALIDATION;

class userController {
    
        function industriesList() {
            
            try {
                
                $model = new userModel();

                $rawData = $model->industriesList();			

                $response = $this->encodeJson($rawData);

                echo $response;	
            
            } catch (Exception $e) {

                $res = ['status' => 500, 'message' => $e->getMessage()];

                echo $this->encodeJson($res); die;
            }
       

	}
        
        
        function newRegisterationId() {
            
            try{
            
                $model = new userModel();

                $rawData = $model->newRegisterationId();			

                $response = $this->encodeJson($rawData);

                echo $response;	
            
            } catch (Exception $e) {

                $res = ['status' => 500, 'message' => $e->getMessage()];

                echo $this->encodeJson($res); die;
            }
	}
        
        
        
        function newRegisteration() {
            
            try {
            
		$model = new userModel();
                $middleWare = new validation();            
                
                $formData = $middleWare->registerValidation();
                
                if($formData['status'] != 200) :
                  
                  $rawData = $formData;
                
                elseif($model->checkUserExists($formData['data']) > 0) :
                    
                    $rawData = array("status" => 400,"message" => "User allready exists");         

                else :
                    
                    $rawData = $model->registeration($formData['data']);	
                
                endif;
                  
		$response = $this->encodeJson($rawData);
                        
		echo $response;	
                
            } catch (Exception $e) {

                $res = ['status' => 500, 'message' => $e->getMessage()];

                echo $this->encodeJson($res); die;
            }
            
	}
        

        function setPaymentProcess() {
            
            try {
            
		$model = new userModel();
                $middleWare = new validation();            
                
                $formData = $middleWare->paymentProcessValidation();
                
                if($formData['status'] == 200) :
                    
                    $data = $formData['data'];
                    
                    $rawData = (isset($data->detail) && $data->detail == 'interview') ? $model->setInterviewPaymentProcess($data) : $model->setPaymentProcess($data);	
                
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



        
        function setPyamentSuccess() {
            
            try {
            
		$model = new userModel();
                $middleWare = new validation();            
                
                $formData = $middleWare->paymentSuccessValidation();
                
                if($formData['status'] == 200) :
                    
                    $data = $formData['data'];
                    
                    $rawData = (isset($data->paymentFor) && $data->paymentFor == 'interview') ? $model->setInterViewPaymentSuccess($data) : $model->setPaymentSuccess($data);	              
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
        
        function Login() {
            
            try {
            
		$model = new userModel();
                $middleWare = new validation();            
                
                $formData = $middleWare->LoginValidation();
                
                if($formData['status'] == 200) :
                    
                    $rawData = $model->Login($formData['data']);	
                
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
        
        
        function Logout() {
            
            try {
            
		$model = new userModel();
                
                   $authValn = new auth();
            
                    $rest = $authValn->authorize();
      
                if($rest['status'] == 200) :

                    if(($rest['flag'] == 0) && ($rest['flag'] == null)) :

                        $rawData = array("status"=>400,"message"=>'token is not valid');

                    else :

                        $rawData = $model->Logout($rest['id']);

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
        
        
        
        
        function getUserProfile() {
            
            try {
            
		$model = new userModel();
                
                   $authValn = new auth();
            
                    $rest = $authValn->authorize();  
      
                if($rest['status'] == 200) :

                    if(($rest['flag'] == 0) && ($rest['flag'] == null)) :

                        $rawData = array("status"=>400,"message"=>'token is not valid');

                    else :

                        $rawData = $model->getUserProfileDetail($rest['id']);

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
        
       
        function profileUpdate() {
            
            try {    
            
		$model = new userModel();
                $middleWare = new validation();            
                
                $formData = $middleWare->registerValidation();
                
                if($formData['status'] != 200) :
                  
                  $rawData = $formData;
                
                elseif($model->checkUserExists($formData['data']) > 0) :
                    
                    $rawData = array("status" => 400,"message" => "User allready exists");         

                else :
                    
                    $rawData = $model->registeration($formData['data']);	
                
                endif;
                  
		$response = $this->encodeJson($rawData);
                        
		echo $response;	
                
            } catch (Exception $e) {

                $res = ['status' => 500, 'message' => $e->getMessage()];

                echo $this->encodeJson($res); die;
            }
            
	}
        
        
        
        function payTmPaymentStatus() {
           
            require_once LIB."/payTm/encdec_paytm.php";

            /* initialize an array */
            $paytmParams = array();

            /* body parameters */
            $paytmParams["body"] = array(

                /* Find your MID in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys */
                "mid" => "YOUR_MID_HERE",

                /* Enter your order id which needs to be check status for */
                "orderId" => "YOUR_ORDER_ID",
            );

            /**
            * Generate checksum by parameters we have in body
            * Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
            */
            $checksum = getChecksumFromString(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), "YOUR_KEY_HERE");

            /* head parameters */
            $paytmParams["head"] = array(

                /* put generated checksum value here */
                "signature"	=> $checksum
            );

            /* prepare JSON string for request */
            $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

            /* for Staging */
            $url = "https://securegw-stage.paytm.in/merchant-status/api/v1/getPaymentStatus";

            /* for Production */
            // $url = "https://securegw.paytm.in/merchant-status/api/v1/getPaymentStatus";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));  
            $response = curl_exec($ch);
            
            print_r($this->encodeJson($response)); die;
            
        }


	public function encodeJson($responseData) {
            
		$jsonResponse = json_encode($responseData);
                
		return $jsonResponse;		
	}
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
}
