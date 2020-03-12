<?php


namespace Quizapp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
use Framework\Routing\RouteMatch;
use Quizapp\Contracts\ServiceInterface;

class QuizInstanceController extends SecurityController
{
    private $quizService;

    public function __construct (RendererInterface $renderer, SessionInterface $session, ServiceInterface $quizService)
    {
        parent::__construct($renderer, $session);
        $this->quizService = $quizService;
    }

    public function getQuiz (RouteMatch $routeMatch, Request $request) {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        $quizTemplateID = $routeMatch->getRequestAttributes()['id'];
        $userID = $this->session->get('id');
        $quizInstanceID = $this->quizService->addQuiz($userID, $quizTemplateID);
        if ($quizInstanceID == null) {
            return $this->renderer->renderException(['message' => 'Quiz not found'], 404);
        }
        $this->session->set('quizInstanceID', $quizInstanceID);
        $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/user/quiz/question/1';

        return $this->redirect($location, 301);
    }

    public function getReview (RouteMatch $routeMatch, Request $request) {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }
        $quizInstanceID = $this->session->get('quizInstanceID');
        $quizInstance = $this->quizService->findQuiz($quizInstanceID);
        $questionsAnswers = $this->quizService->getAllQuestions($quizInstanceID);
        return $this->renderer->renderView('candidate-results.phtml', ['questionsAnswers' => $questionsAnswers,
                                                                                'quizInstance' => $quizInstance,
                                                                                'userName' => $this->session->get('name')]);
    }

    public function getResult (RouteMatch $routeMatch, Request $request) {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        if (!$this->isAdmin()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        $quizInstanceID = $routeMatch->getRequestAttributes()['id'];
        $quizInstance = $this->quizService->findQuiz($quizInstanceID);
        $questionsAnswers = $this->quizService->getAllQuestions($quizInstanceID);
        return $this->renderer->renderView('admin-results.phtml', ['questionsAnswers' => $questionsAnswers,
                                                                            'quizInstance' => $quizInstance,
                                                                            'userName' => $this->session->get('name')]);
    }

    public function saveResult (RouteMatch $routeMatch, Request $request) {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        if (!$this->isAdmin()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        $quizInstanceID = $routeMatch->getRequestAttributes()['id'];
        $score = $request->getParameter('score');
        $this->quizService->saveScore($quizInstanceID, $score);
        $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/admin/results';

        return $this->redirect($location, 301);
    }

    public function saveQuiz (RouteMatch $routeMatch, Request $request) {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        $quizInstanceID = $this->session->get('quizInstanceID');
        $this->quizService->save($quizInstanceID);
        return $this->renderer->renderView('quiz-success-page.phtml', ['userName' => $this->session->get('name')]);
    }

    public function getAllQuizzes(RouteMatch $routeMatch, Request $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        if (!$this->isAdmin()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        $page = $request->getParameter('page');
        $search = $request->getParameter('search');
        $sorts = $request->getParameter('sort');

        $count = $this->quizService->getQuizzesCount(null, null, $search);

        if ($page == null || $page == 0 || $page > $count || !is_numeric($page)) {
            $page = 1;
        }

        if ($sorts == null) {
            $sorts = [];
        }

        $data = $this->quizService->getQuizzes(null, $sorts, $page, 5, $search);

        $count = ceil($count/5);

        return $this->renderer->renderView('admin-results-listing.phtml', ['data' => $data,
                                                                                    'count' => $count,
                                                                                    'page' => $page,
                                                                                    'search' => $search,
                                                                                    'userName' => $this->session->get('name')]);
    }

}