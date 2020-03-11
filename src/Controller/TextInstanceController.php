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

class TextInstanceController extends AbstractController
{
    private $answerService;
    private $session;

    public function __construct (RendererInterface $renderer, ServiceInterface $answerService, SessionInterface $session)
    {
        parent::__construct($renderer);
        $this->answerService = $answerService;
        $this->session = $session;
    }

    public function next (RouteMatch $routeMatch, Request $request) {
        $nextQuestion = $routeMatch->getRequestAttributes()['next'];
        $textID = $routeMatch->getRequestAttributes()['id'];
        $text = $request->getParameter('answer');
        $this->answerService->save($textID, $text);

        $location = "Location: http://local.quizapp.com/user/quiz/question/$nextQuestion";
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function save (RouteMatch $routeMatch, Request $request) {
        $textID = $routeMatch->getRequestAttributes()['id'];
        $text = $request->getParameter('answer');
        $this->answerService->save($textID, $text);

        $location = "Location: http://local.quizapp.com/user/quiz/review";
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }
}