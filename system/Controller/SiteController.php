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
            'title' => 'Pagina inicial'
        ]);
    }

    public function about(): void
    {
        echo $this->template->render('about.html', [
            'title' => 'Sobre'
        ]);
    }

    public function error404(): void
    {
        echo $this->template->render('404.html', [
            'title' => 'Pagina não encontrada'
        ]);
    }

}
