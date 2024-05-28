<?php

namespace system\Controller;

use system\Core\Controller;
use system\Core\Helpers;
use system\Core\Session;
use system\Model\UserModel;

class UserController extends Controller
{    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('templates/site/views');
    }
    
    /**
     * user
     *
     * @return void
     */
    public static function user()
    {
        $session = new Session();

        if(!$session->check('userId')){
            return null;
        }
        
        return (new UserModel())->findByID($session->userId);


    }

}
