<?php

namespace system\Controller\Admin;

use system\Core\Helpers;
use system\Support\Email;

class AdminConfig extends AdminController
{  

    public function configEmail()
    {
        echo $this->template->render('config/config.html.twig', []);
    }

}