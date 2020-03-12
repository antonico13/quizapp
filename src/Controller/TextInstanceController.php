<?php


namespace Quizapp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use Framework\Routing\RouteMatch;
use Quizapp\Contracts\ServiceInterface;

class TextInstanceController extends SecurityController
{
    private $answerService;

    public function __construct (RendererInterface $renderer, SessionInterface $session, ServiceInterface $answerService)
    {
        parent::__construct($renderer, $session);
        $this->answerService = $answerService;
    }

    public function next (RouteMatch $routeMatch, Request $request) {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        $nextQuestion = $routeMatch->getRequestAttributes()['next'];
        $textID = $routeMatch->getRequestAttributes()['id'];
        $text = $request->getParameter('answer');
        $this->answerService->save($textID, $text);

        $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/user/quiz/question/'.$nextQuestion;

        return $this->redirect($location, 301);
    }

    public function save (RouteMatch $routeMatch, Request $request) {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        $textID = $routeMatch->getRequestAttributes()['id'];
        $text = $request->getParameter('answer');
        $this->answerService->save($textID, $text);

        $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/user/quiz/review';

        return $this->redirect($location, 301);
    }
}