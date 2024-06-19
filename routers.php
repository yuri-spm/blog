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
    SimpleRouter::get(URL_SITE.'post/{id}', 'SiteController@post');
    SimpleRouter::get(URL_SITE.'category/{id}', 'SiteController@category');
    SimpleRouter::post(URL_SITE.'search', 'SiteController@find');

    SimpleRouter::get(URL_SITE.'404', 'SiteController@error404');


    //admin
    SimpleRouter::group(['namespace' => 'Admin'], function(){

        //Dashboard
        SimpleRouter::get(URL_ADMIN.'dashboard', 'AdminDashboard@dashboard');
        SimpleRouter::get(URL_ADMIN.'exit', 'AdminDashboard@exit');

       //Login
       SimpleRouter::match(['get','post'], URL_ADMIN.'login', 'AdminLogin@login');

        //Posts
        SimpleRouter::get(URL_ADMIN.'posts/posts', 'AdminPosts@lists');
        SimpleRouter::match(['get','post'], URL_ADMIN.'posts/register', 'AdminPosts@register');
        SimpleRouter::match(['get','post'], URL_ADMIN.'posts/edit/{id}', 'AdminPosts@edit');
        SimpleRouter::get(URL_ADMIN.'posts/delete/{id}', 'AdminPosts@delete');
        
        //Category
        SimpleRouter::get(URL_ADMIN.'categories/categories', 'AdminCategories@lists');
        SimpleRouter::match(['get','post'], URL_ADMIN.'categories/register', 'AdminCategories@register');
        SimpleRouter::match(['get','post'], URL_ADMIN.'categories/edit{id}', 'AdminCategories@edit');
        SimpleRouter::get(URL_ADMIN.'categories/delete/{id}', 'AdminCategories@delete');

        //Admin User
        SimpleRouter::get(URL_ADMIN.'users/users', 'AdminUser@lists');
        SimpleRouter::match(['get','post'], URL_ADMIN.'users/register', 'AdminUser@register');
        SimpleRouter::match(['get','post'], URL_ADMIN.'users/edit/{id}', 'AdminUser@edit');
        SimpleRouter::get(URL_ADMIN.'users/delete/{id}', 'AdminUser@delete');
    });

    SimpleRouter::start();
} catch (NotFoundHttpException $e) {
    if (Helpers::localhost()) {
        echo $e;
    } else {
      
        Helpers::redirect(URL_SITE.'404');
    }
}
