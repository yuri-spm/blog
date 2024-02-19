<?php

namespace system\Controller;

use system\Core\Controller;

class SiteController extends Controller
{

    public function __construct()
    {
        parent::__construct('templates/site/views');
    }

    public function index(): void
    {
        echo $this->template->render('index.html', [
           
            'subtitulo' => 'teste de subtitulo'
        ]);
    }

    public function about(): void
    {
        echo $this->template->render('about.html', [
            'titulo' => 'Sobre'
        ]);
    }

}
