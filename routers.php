<?php

use Pecee\Http\Request;
use system\Core\Helpers;
use Pecee\SimpleRouter\SimpleRouter;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;

try {
    SimpleRouter::setDefaultNamespace('system\Controller');

    //site
    SimpleRouter::get(URL_SITE, 'SiteController@index');
    SimpleRouter::get(URL_SITE.'sobre', 'SiteController@about');
    SimpleRouter::get(URL_SITE.'post/{slug}', 'SiteController@post');
    SimpleRouter::get(URL_SITE.'category/{slug}/{page?}', 'SiteController@category'); 
    SimpleRouter::match(['get','post'],URL_SITE.'contato', 'SiteController@contact');
  
    SimpleRouter::post(URL_SITE.'search', 'SiteController@find');

    SimpleRouter::get(URL_SITE.'404', 'SiteController@errorr404');

    


    //admin
    SimpleRouter::group(['namespace' => 'Admin'], function(){

        //Dashboard
        SimpleRouter::get(URL_ADMIN.'dashboard', 'AdminDashboard@dashboard');
        SimpleRouter::get(URL_ADMIN.'exit', 'AdminDashboard@exit');

       //Login
       SimpleRouter::match(['get','post'], URL_ADMIN.'login', 'AdminLogin@login');

        //Posts
        SimpleRouter::get(URL_ADMIN.'posts/lists', 'AdminPosts@lists');
        SimpleRouter::match(['get','post'], URL_ADMIN.'posts/add', 'AdminPosts@add');
        SimpleRouter::match(['get','post'], URL_ADMIN.'posts/edit/{id}', 'AdminPosts@edit');
        SimpleRouter::get(URL_ADMIN.'posts/delete/{id}', 'AdminPosts@delete');
        SimpleRouter::post(URL_ADMIN.'posts/datatable', 'AdminPosts@datatable');
        
        //Category
        SimpleRouter::get(URL_ADMIN.'categories/modal_categories', 'AdminCategories@modalCategories');
        SimpleRouter::get(URL_ADMIN.'categories/lists', 'AdminCategories@lists');
        SimpleRouter::match(['get','post'], URL_ADMIN.'categories/add', 'AdminCategories@add');
        SimpleRouter::match(['get','post'], URL_ADMIN.'categories/edit{id}', 'AdminCategories@edit');
        SimpleRouter::get(URL_ADMIN.'categories/delete/{id}', 'AdminCategories@delete');

        //Admin User
        SimpleRouter::get(URL_ADMIN.'users/lists', 'AdminUser@lists');
        SimpleRouter::match(['get','post'], URL_ADMIN.'users/add', 'AdminUser@add');
        SimpleRouter::match(['get','post'], URL_ADMIN.'users/edit/{id}', 'AdminUser@edit');
        SimpleRouter::get(URL_ADMIN.'users/delete/{id}', 'AdminUser@delete');

        //Admin Config
        SimpleRouter::get(URL_ADMIN.'config', 'AdminConfigEmail@configEmail');
        SimpleRouter::match(['get','post'], URL_ADMIN.'config/add', 'AdminConfigEmail@add');
        SimpleRouter::match(['get','post'], URL_ADMIN.'config/edit/{id}', 'AdminConfigEmail@edit');
        SimpleRouter::match(['get','post'], URL_ADMIN.'config/sendTeste', 'AdminConfigEmail@sendTeste');
    });

    SimpleRouter::start();
} catch (NotFoundHttpException $e) {
    if (Helpers::localhost()) {
        echo $e;
    } else {
      
        Helpers::redirect(URL_SITE.'404');
    }
}