<?php
		
class validation {

        
    function registerValidation() {

        try {            
            
                if ($this->checkVar($_POST["formId"])) :

                    return array("status" => 400, "message" => "formId is required");
            
                elseif ($this->checkVar($_POST["fname"])) :

                    return array("status" => 400, "message" => "First Name is required");             

                elseif ($this->checkVar($_POST["lname"])) :

                    return array("status" => 400, "message" => "Last Name is required");

                elseif ($this->checkVar($_POST["userImageType"])) :

                    return array("status" => 400, "message" => "Please mention the type 'file' or 'base64' for user image");      

                elseif (($_POST["userImageType"]=='file') && $this->checkVar($_FILES['userImage']['name'])) :

                    return array("status" => 400, "message" => "Please upload or capture your photo");      

                elseif (($_POST["userImageType"]=='file') && ($this->checkFileExtn($_FILES['userImage']['name']))) :

                    return array("status" => 400, "message" => "Please upload photo in given format");  

                elseif (($_POST["userImageType"]=='file') && ($this->checkFileSize($_FILES['userImage']['size']))) :

                    return array("status" => 400, "message" => "File size must be greater than 4kb and less than 1mb");      

                elseif ($this->checkVar($_POST["email"])) :

                    return array("status" => 400, "message" => "Email Id is required");

                elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) :

                    return array("status" => 400, "message" => "$email is not a valid email address");

                elseif ($this->checkVar($_POST["mobile"])) :

                    return array("status" => 400, "message" => "Mobile number is required");        

                elseif (!is_numeric($_POST["mobile"]) || strlen($_POST["mobile"]) < 10 || strlen($_POST["mobile"]) > 14) :

                    return array("status" => 400, "message" => "Please enter valid mobile number");

                elseif ($this->checkVar($_POST["gender"])) :

                    return array("status" => 400, "message" => "gender is required");                                                     

                elseif ($this->checkVar($_POST["highestQualification"])) :

                    return array("status" => 400, "message" => "Highest Qualification is required");             

                elseif ($this->checkVar($_POST["modelCareerCentre"])) :

                    return array("status" => 400, "message" => "Model Career Centre is required");             

                elseif ($this->checkVar($_POST["jobCategory"])) :

                    return array("status" => 400, "message" => "Job Category is required"); 

                elseif ($this->checkVar($_POST["jobSubCategory"])) :

                    return array("status" => 400, "message" => "Job Subcategory is required"); 

                elseif ($this->checkVar($_POST["jobRole"])) :

                    return array("status" => 400, "message" => "Job Role is required"); 

                elseif ($this->checkVar($_POST["expectedCTC"])) :

                    return array("status" => 400, "message" => "Expected CTC is required"); 

                elseif ($this->checkVar($_POST["preferredLocation1"])) :

                    return array("status" => 400, "message" => "Preferred Location is required");   
                
                elseif ($this->checkVar($_POST["totalAmt"])) :

                    return array("status" => 400, "message" => "total amount of packages is required");   

                elseif (!empty($_FILES['aadhaarImage']['name']) && ($_POST["aadhaarImageType"]=='file') && ($this->checkFileExtn($_FILES['aadhaarImage']['name']))) :

                    return array("status" => 400, "message" => "Please upload adhaar card in given format");  

                elseif (!empty($_FILES['aadhaarImage']['name']) && ($_POST["aadhaarImageType"]=='file') && ($this->checkFileSize($_FILES['aadhaarImage']['size']))) :

                    return array("status" => 400, "message" => "File size must be greater than 4kb and less than 1mb");  

                elseif (!empty($_FILES['passwordImage']['name']) && ($_POST["passwordImageType"]=='file') && ($this->checkFileExtn($_FILES['passwordImage']['name']))) :

                    return array("status" => 400, "message" => "Please upload voter id in given format");  

                elseif (!empty($_FILES['passwordImage']['name']) && ($_POST["passwordImageType"]=='file') && ($this->checkFileSize($_FILES['passwordImage']['size']))) :

                    return array("status" => 400, "message" => "File size must be greater than 4kb and less than 1mb");  

                elseif (!empty($_FILES['panCardImage']['name']) && ($_POST["panCardImageType"]=='file') && ($this->checkFileExtn($_FILES['panCardImage']['name']))) :

                    return array("status" => 400, "message" => "Please upload pan card in given format");  

                elseif (!empty($_FILES['panCardImage']['name']) && ($_POST["panCardImageType"]=='file') && ($this->checkFileSize($_FILES['panCardImage']['size']))) :
                    
                    return array("status" => 400, "message" => "File size must be greater than 4kb and less than 1mb");                

               else :  
                           
                   $formData = $this->senitizeInput();

                    return array("status" => 200, 'data'=>$formData,  "message" => "all clear");                 

                endif;
                

            }  catch(Exception $e) {

                return array("status"=>500,"message"=>$e->getMessage());
            }  
    }


    function paymentProcessValidation() {

        try {   
            
                if ($this->checkVar($_POST["rId"])) :

                    return array("status" => 400, "message" => "rid is required");

                elseif ($this->checkVar($_POST["orderId"])) :

                    return array("status" => 400, "message" => "orderId is required");
                    
                elseif ($this->checkVar($_POST["amount"])) :

                    return array("status" => 400, "message" => "total amount is required");
                
                else :
                    
                    $formData = $this->senitizeInput();
                
                    return array("status" => 200, 'data'=>$formData,  "message" => "all clear");             
                
                endif;

            }  catch(Exception $e) {

                return array("status"=>500,"message"=>$e->getMessage());
            }  
    } 
    
    function paymentSuccessValidation() {

        try {   
            
                if ($this->checkVar($_POST["registrationId"])) :

                    return array("status" => 400, "message" => "registrationId is required");

                elseif ($this->checkVar($_POST["payment"])) :

                    return array("status" => 400, "message" => "payment flag is required");
                    
                elseif ($this->checkVar($_POST["orderId"])) :

                    return array("status" => 400, "message" => "order id is required");
                    
                elseif ($this->checkVar($_POST["cusRegistrationId"])) :

                    return array("status" => 400, "message" => "cusRegistrationId is required");
                
                else :
                    
                    $formData = $this->senitizeInput();
                
                    return array("status" => 200, 'data'=>$formData,  "message" => "all clear");             
                
                endif;

            }  catch(Exception $e) {

                return array("status"=>500,"message"=>$e->getMessage());
            }  
    } 
    
    
    function LoginValidation() {

        try {   
            
                if ($this->checkVar($_POST["username"])) :

                    return array("status" => 400, "message" => "username is required");

                elseif ($this->checkVar($_POST["password"])) :

                    return array("status" => 400, "message" => "password is required");
                
                else :
                    
                    $formData = $this->senitizeInput();
                
                    $formData->userAgent = $_SERVER['HTTP_USER_AGENT'];
                
                    return array("status" => 200, 'data'=>$formData,  "message" => "all clear");             
                
                endif;

            }  catch(Exception $e) {

                return array("status"=>500,"message"=>$e->getMessage());
            }  
    } 
    
   
    function videoIntroValidation() {

        try {   
            
                if ($this->checkVar($_FILES["video"]["name"])) :

                    return array("status" => 400, "message" => "video is required in blob format");
                
                else :
                    
                    $formData = $this->senitizeInput();
                
                    $formData->userAgent = $_SERVER['HTTP_USER_AGENT'];
                
                    return array("status" => 200, 'data'=>$formData,  "message" => "all clear");             
                
                endif;

            }  catch(Exception $e) {

                return array("status"=>500,"message"=>$e->getMessage());
            }  
    } 
    
    
    function rpackAnsValidation() {

        try {   
            
                if ($this->checkVar(isset($_POST['rpackId']))) :

                    return array("status" => 400, "message" => "rpackId is required");
                
                elseif ($this->checkVar(isset($_POST['questionId']))) :

                    return array("status" => 400, "message" => "questionId is required");
                
                elseif ($this->checkVar(isset($_POST['type']))) :  //---- type 1=video, 2=audio, 3=text, 4=file

                    return array("status" => 400, "message" => "type is required");
                            
                elseif ($_POST['type'] == 3 && $this->checkVar(isset($_POST["answer"]["name"]))) :

                    return array("status" => 400, "message" => "answer is required");
                
               
                elseif ($_POST['type'] != 3 && $this->checkVar(isset($_FILES["answer"]))) :

                    return array("status" => 400, "message" => "answer is required");
                
                else :
                    
                    $formData = $this->senitizeInput();
                
                    $formData->userAgent = $_SERVER['HTTP_USER_AGENT'];
                
                    return array("status" => 200, 'data'=>$formData,  "message" => "all clear");             
                
                endif;

            }  catch(Exception $e) {

                return array("status"=>500,"message"=>$e->getMessage());
            }  
    } 
    
    
        function ApplyRpackValidation() {

        try {   
            
                if ($this->checkVar(isset($_POST['rpackId']))) :

                    return array("status" => 400, "message" => "rpackId is required");
                
//                elseif ($this->checkVar(isset($_POST['questionSetId']))) :
//
//                    return array("status" => 400, "message" => "questionSetId is required");
                
                else :
                    
                    $formData = $this->senitizeInput();
                
                    $formData->userAgent = $_SERVER['HTTP_USER_AGENT'];
                
                    return array("status" => 200, 'data'=>$formData,  "message" => "all clear");             
                
                endif;

            }  catch(Exception $e) {

                return array("status"=>500,"message"=>$e->getMessage());
            }  
    } 
    
    
    function validateParams($params) {

        try {   
            
               $arrayKeys = array_keys($_GET);
            
                foreach($params as $key) :

                    if(!in_array($key, $arrayKeys)) :

                        return array("status" => 400, "message" => "$key Required");

                    endif;

                endforeach;
            
                $formData = $this->senitizeGetParams();

                return array("status" => 200, 'data'=>$formData,  "message" => "all clear");     

            }  catch(Exception $e) {

                return array("status"=>500,"message"=>$e->getMessage());
            }  
    } 
    
    
    function dd($param) {
        
        print_r($param);
        die;
        
        
    }
                
                 
             
    private function senitizeInput() {


       $formData = array();

       foreach($_POST as $key => $value):
        
            $formData[$key] = $this->senitize($value); 
           
       endforeach;
       
       return (object) $formData;
        
    }
    
    
    private function senitizeGetParams() {


       $formData = array();

       foreach($_GET as $key => $value):
        
            $formData[$key] = $this->senitize($value); 
           
       endforeach;
       
       return (object) $formData;
        
    }  

        
        
        
        
        
    private function checkVar($input) {

        if(!isset($input) || empty($input) || $input==NULL) :

            return TRUE;

        else :

            return FALSE;

        endif;

    }        
        
        
    private function checkFileSize($file) {

        if(($file > 0.004) && ($file <= 1000)) :

            return TRUE;

        else :

            return FALSE;

        endif; 

    }
        
    private function checkFileExtn($file) {

        $extn = array('png','PNG','jpg','jpeg','JPG','JPEG','pdf','PDF');

        if(in_array($file, $extn)) :

            return TRUE;

        else :

            return FALSE;

        endif;   

    }
        
                
    private function senitize($data) {

        $data = trim($data);

        $data = addslashes($data);

        $data = htmlspecialchars($data);

        return $data;

    }
        
    
        
        
        
}
