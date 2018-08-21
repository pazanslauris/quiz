<?php

namespace Quiz\Controllers;


use Quiz\Services\QuizService;

abstract class BaseController
{
    protected $action;
    protected $post;
    protected $get;

    /** @var QuizService */
    protected $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    /**
     * @param string $action
     */
    public function handleCall(string $action)
    {
        $this->action = $action;
        $this->post = $_POST;
        $this->get = $_GET;

        $this->callAction($action);
    }

    /**
     * @param string $action
     */
    protected function callAction(string $action)
    {
        echo static::$action();
    }

    /**
     * @param string $view
     * @return string
     */
    protected function resolveViewFile(string $view): string
    {
        return VIEW_DIR . "/$view.phtml";
    }

    /**
     * @param string $view
     * @param array $variables
     * @return string
     */
    protected function render(string $view, array $variables = []): string
    {
        $viewFile = $this->resolveViewFile($view);

        if (file_exists($viewFile)) {
            extract($variables);
            ob_start();
            include $viewFile;
            $output = ob_get_clean();

            return $output;
        }

        return 'View not found';
    }
}