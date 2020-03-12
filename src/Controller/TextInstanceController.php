<?php


namespace Quizapp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
use Framework\Routing\RouteMatch;
use Quizapp\Contracts\ServiceInterface;

class TextInstanceController extends SecurityController
{
    /**
     * @var ServiceInterface
     */
    private $answerService;

    public function __construct (RendererInterface $renderer, SessionInterface $session, ServiceInterface $answerService)
    {
        parent::__construct($renderer, $session);
        $this->answerService = $answerService;
    }

    /**
     * @param RouteMatch $routeMatch
     * @param Request $request
     * @return \Framework\Http\Response
     */
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

    /**
     * @param RouteMatch $routeMatch
     * @param Request $request
     * @return \Framework\Http\Response
     */
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