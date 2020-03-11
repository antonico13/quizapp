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

class QuizInstanceController extends AbstractController
{
    private $quizService;
    private $session;

    public function __construct (RendererInterface $renderer, ServiceInterface $quizService, SessionInterface $session)
    {
        parent::__construct($renderer);
        $this->quizService = $quizService;
        $this->session = $session;
    }

    public function getQuiz (RouteMatch $routeMatch, Request $request) {
        $quizTemplateID = $routeMatch->getRequestAttributes()['id'];
        $userID = $this->session->get('id');
        $quizInstanceID= $this->quizService->addQuiz($userID, $quizTemplateID);
        $this->session->set('quizInstanceID', $quizInstanceID);
        $location = "Location: http://local.quizapp.com/user/quiz/question/1";
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function getReview (RouteMatch $routeMatch, Request $request) {
        $quizInstanceID = $this->session->get('quizInstanceID');
        $quizInstance = $this->quizService->findQuiz($quizInstanceID);
        $questionsAnswers = $this->quizService->getAllQuestions($quizInstanceID);
        return $this->renderer->renderView('candidate-results.phtml', ['questionsAnswers' => $questionsAnswers, 'quizInstance' => $quizInstance]);
    }

    public function getResult (RouteMatch $routeMatch, Request $request) {
        $quizInstanceID = $routeMatch->getRequestAttributes()['id'];
        $quizInstance = $this->quizService->findQuiz($quizInstanceID);
        $questionsAnswers = $this->quizService->getAllQuestions($quizInstanceID);
        return $this->renderer->renderView('admin-results.phtml', ['questionsAnswers' => $questionsAnswers, 'quizInstance' => $quizInstance]);
    }

    public function saveResult (RouteMatch $routeMatch, Request $request) {
        $quizInstanceID = $routeMatch->getRequestAttributes()['id'];
        $score = $request->getParameter('score');
        $this->quizService->saveScore($quizInstanceID, $score);
        $location = "Location: http://local.quizapp.com/admin/results";
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function saveQuiz (RouteMatch $routeMatch, Request $request) {
        $quizInstanceID = $this->session->get('quizInstanceID');
        $this->quizService->save($quizInstanceID);
        return $this->renderer->renderView('quiz-success-page.html', []);
    }

    public function getAllQuizzes(RouteMatch $routeMatch, Request $request)
    {
        $count = $this->quizService->getQuizzesCount();
        $page = $request->getParameter('page');
        $search = $request->getParameter('search');

        if ($search) {
            $count = $this->quizService->getQuizzesCountSearch(null, $search);
        }

        if ($page == null || $page == 0 || $page > $count || !is_numeric($page)) {
            $page = 1;
        }

        $data = $this->quizService->getQuizzes(null, $page);

        if ($search) {
            $data = $this->quizService->getQuizzesSearch(null, $search, $page);
        }

        $count = ceil($count/5);

        return $this->renderer->renderView('admin-results-listing.phtml', ['data' => $data, 'count' => $count, 'page' => $page, 'search' => $search, 'name' => $this->session->get('name')]);
    }

}