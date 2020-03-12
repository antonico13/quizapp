<?php


namespace Quizapp\Controller;


use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Routing\RouteMatch;

class ExceptionController extends AbstractController
{
    public function exception (RouteMatch $routeMatch, Request $request) {
        return $this->renderer->renderView('exceptions-page.phtml', ['message' => $routeMatch->getRequestAttributes()['message']]);
    }
}