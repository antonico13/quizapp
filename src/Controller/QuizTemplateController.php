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

class QuizTemplateController extends AbstractController
{
    private $quizService;
    private $session;

    public function __construct (RendererInterface $renderer, ServiceInterface $quizService, SessionInterface $session)
    {
        parent::__construct($renderer);
        $this->quizService = $quizService;
        $this->session = $session;
    }
    public function getQuizzes( RouteMatch $routeMatch, Request $request)
    {
        $userid = $this->session->get('id');
        $count = $this->quizService->getQuizzesCount($userid);
        $page = $request->getParameter('page');
        $search = $request->getParameter('search');


        if ($search) {
            $count = $this->quizService->getQuizzesCountSearch($userid, $search);
        }

        if ($page == null || $page == 0 || $page > $count || !is_numeric($page)) {
            $page = 1;
        }

        $data = $this->quizService->getQuizzes($userid, $page);

        if ($search) {
            $data = $this->quizService->getQuizzesSearch($userid, $search, $page);
        }

        $count = ceil($count/5);

        return $this->renderer->renderView('admin-quizzes-listing.phtml', ['data' => $data, 'count' => $count, 'page' => $page, 'search' => $search]);
    }

    public function getAllQuizzes( RouteMatch $routeMatch, Request $request)
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

        return $this->renderer->renderView('candidate-quiz-listing.phtml', ['data' => $data, 'count' => $count, 'page' => $page, 'search' => $search, 'name' => $this->session->get('name')]);
    }

    public function addQuizzes (RouteMatch $routeMatch, Request $request) {
        $questions = $this->quizService->getAllQuestions();
        return $this->renderer->renderView('admin-quiz-details.html', ['questions' => $questions]);
    }

    public function add (RouteMatch $routeMatch, Request $request) {
        $userid = $this->session->get('id');
        $data = $request->getParameters();
        $this->quizService->addQuiz($userid, $data);
        $location = "Location: http://local.quizapp.com/admin/quiz";

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }

    public function editQuizzes (RouteMatch $routeMatch, Request $request) {
        $id = $routeMatch->getRequestAttributes()['id'];
        $quiz = $this->quizService->getQuiz($id);
        $questions = $this->quizService->getAllQuestions();

        return $this->renderer->renderView('admin-quiz-edit-details.phtml', ['quiz' => $quiz, 'questions' => $questions]);
    }

    public function edit (RouteMatch $routeMatch, Request $request) {
        $userid = $this->session->get('id');
        $id = $routeMatch->getRequestAttributes()['id'];
        $data = $request->getParameters();
        $this->quizService->editQuiz($userid, $id, $data);

        $location = "Location: http://local.quizapp.com/admin/quiz";

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }

    public function delete (RouteMatch $routeMatch, Request $request) {
        $id = $routeMatch->getRequestAttributes()['id'];
        $this->quizService->deleteQuiz($id);
        $location = "Location: http://local.quizapp.com/admin/quiz";

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }
}