<?php

try {
    
require_once PAYTMCTRL;
require_once EXCELCTRL;
require_once USERCTRL;
require_once VIDEOCTRL;
require_once JOBCTRL;


switch (trim($_REQUEST['route'])) :
    
        case "payTmCronJob":
        // to handle REST Url /api/v1/cron/paytm/payment/status
        $apiRestHandler = new payTmController();
        $apiRestHandler->updatePayTmPaymentStatus();
        break; 

        case "excel":
        // to handle REST Url /api/v1/excel
        $apiRestHandler = new excelController();
        $apiRestHandler->excelImport();
        break;   
    
        case "formId":
        // to handle REST Url /api/v1/formid           
            
        $apiRestHandler = new userController();
        $apiRestHandler->newRegisterationId();
        break;
    
        case "industries":
        // to handle REST Url /api/v1/industries
        $apiRestHandler = new userController();
        $apiRestHandler->industriesList();
        break;
        
        case "registeration":
        // to handle REST Url /api/v1/registeration
        $apiRestHandler = new userController();
        $apiRestHandler->newRegisteration();
        break;   
    
        case "paymentProcess":
        // to handle REST Url /api/v1/payment/process
        $apiRestHandler = new userController();
        $apiRestHandler->setPaymentProcess();
        break;  
    
        case "paymentSuccess":
        // to handle REST Url /api/v1/payment/success
        $apiRestHandler = new userController();
        $apiRestHandler->setPyamentSuccess();
        break;  
    
        case "Login":
        // to handle REST Url /api/v1/login
        $apiRestHandler = new userController();
        $apiRestHandler->Login();
        break; 
    
        case "Logout":
        // to handle REST Url /api/v1/logout
        $apiRestHandler = new userController();
        $apiRestHandler->Logout();
        break; 
    
        case "getUserProfile":
        // to handle REST Url /api/v1/profile/me?token=$token
        $apiRestHandler = new userController();
        $apiRestHandler->getUserProfile();
        break; 
    
        case "profileUpdate":
        // to handle REST Url /api/v1/profile/me/update
        $apiRestHandler = new userController();
        $apiRestHandler->profileUpdate();
        break; 
    
        case "SaveVideoIntroduction":
        // to handle REST Url /api/v1/video/save
        $apiRestHandler = new videoController();
        $apiRestHandler->saveVideoIntro();
        break; 
    
        case "VideoIntroductionList":
        // to handle REST Url /api/v1/video/intro/list
        $apiRestHandler = new videoController();
        $apiRestHandler->getVideoIntroList();
        break; 
    
        case "jobOpeningList":
        // to handle REST Url /api/v1/job/opening/list?industry=1&department=1&role=7&page=1
        $apiRestHandler = new jobController();
        $apiRestHandler->getJobOpeningList();
        break; 
    
        case "rPack":
        // to handle REST Url /api/v1/rpack/detail?id=1
        $apiRestHandler = new jobController();
        $apiRestHandler->getrPack();
        break; 
    
       default :
        
         jsonEncode(['status' => 400, 'message' => 'No route found']);
		
    endswitch;

} catch (Exception $e) {
    
    jsonEncode(['status' => 500, 'message' => $e->getMessage()]);
}


function jsonEncode($data) {
   
       echo json_encode($data); die; 
   
}