<?php

namespace system\Support;

use system\Controller\UserController;
use Twig\Lexer;
use system\Core\Helpers;


/**
 * Classe Template
 */
class Template
{

    private \Twig\Environment $twig;

    public function __construct(string $diretorio)
    {
        $loader = new \Twig\Loader\FilesystemLoader($diretorio);
        $this->twig = new \Twig\Environment($loader);

        $lexer = new Lexer($this->twig, array(
            $this->helpers()
        ));
        $this->twig->setLexer($lexer);
    }


    public function render(string $view, array $data)
    {
        try {
            return $this->twig->render($view, $data);
        } catch (\Twig\Error\LoaderError | \Twig\Error\SyntaxError $ex) {

            echo 'Erro:: ' . $ex->getMessage();
        }
    }


    private function helpers(): void
    {
        array(
            $this->twig->addFunction(
                    new \Twig\TwigFunction('url', function (string $url = null) {
                                return Helpers::url($url);
                            })
            ),
            $this->twig->addFunction(
                    new \Twig\TwigFunction('greetings', function () {
                                return Helpers::greetings();
                            })
            ),
            $this->twig->addFunction(
                    new \Twig\TwigFunction('summarizeText', function (string $texto, int $limit) {
                                return Helpers::summarizeText($texto, $limit);
                            })
            ),
            $this->twig->addFunction(
                    new \Twig\TwigFunction('flash', function () {
                                return Helpers::flash();
                            })
            ),
            $this->twig->addFunction(
                    new \Twig\TwigFunction('user', function () {
                                return UserController::user();
                            })
            ),
            $this->twig->addFunction(
                    new \Twig\TwigFunction('countTime', function (string $data) {
                                return Helpers::countTime($data);
                            })
            ),
            $this->twig->addFunction(
                    new \Twig\TwigFunction('formaterNumber', function (int $numero) {
                                return Helpers::formaterNumber($numero);
                            })
            ),
            $this->twig->addFunction(
                    new \Twig\TwigFunction('timeStart', function () {

                                $timeTotal = microtime(true) - filter_var($_SERVER["REQUEST_TIME_FLOAT"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                                return number_format($timeTotal, 2);
                            })
            ),
        );
    }

}
