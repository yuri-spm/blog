<?php

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\SimpleRouter;
use system\Core\Helpers;

try {
    SimpleRouter::setDefaultNamespace('system\Controller');


    SimpleRouter::get(URL_SITE, 'SiteController@index');
    SimpleRouter::get(URL_SITE.'sobre', 'SiteController@about');
    SimpleRouter::get(URL_SITE.'404', 'SiteController@error404');
    SimpleRouter::get(URL_SITE.'post/{id}', 'SiteController@post');
    SimpleRouter::post(URL_SITE.'search', 'SiteController@search');
    SimpleRouter::get(URL_SITE.'category/{id}', 'SiteController@category');

    SimpleRouter::start();
} catch (NotFoundHttpException $e) {
    if (Helpers::localhost()) {
        echo $e;
    } else {
      
        Helpers::redirect('404');
    }
}
