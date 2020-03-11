<?php


namespace Quizapp\Controller;


use Framework\Controller\AbstractController;

class ResultsController extends AbstractController
{
    public function getResults() {
        $this->renderer->renderView('admin-results.phtml', []);
    }

}