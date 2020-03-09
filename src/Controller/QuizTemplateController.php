<?php


namespace Quizapp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use Framework\Routing\RouteMatch;
use Quizapp\Contracts\ServiceInterface;

class QuizTemplateController extends AbstractController
{
    private $quizService;

    public function __construct (RendererInterface $renderer, ServiceInterface $quizService)
    {
        parent::__construct($renderer);
        $this->quizService = $quizService;
    }
    public function getQuizzes( RouteMatch $routeMatch, Request $request)
    {
        $count = $this->quizService->getQuizzesCount();
        $page = $request->getParameter('page');
        $search = $request->getParameter('search');


        if ($search) {
            $count = $this->quizService->getQuizzesCountSearch($search);
        }

        if ($page == null || $page == 0 || $page > $count || !is_numeric($page)) {
            $page = 1;
        }

        $data = $this->quizService->getQuizzes($page);

        if ($search) {
            $data = $this->quizService->getQuizzesSearch($search, $page);
        }

        $count = ceil($count/5);

        return $this->renderer->renderView('admin-quizzes-listing.phtml', ['data' => $data, 'count' => $count, 'page' => $page, 'search' => $search]);
    }

    public function addQuizzes (RouteMatch $routeMatch, Request $request) {
        $questions = $this->quizService->getAllQuestions();
        return $this->renderer->renderView('admin-quiz-details.html', ['questions' => $questions]);
    }

    public function add (RouteMatch $routeMatch, Request $request) {
        $data = $request->getParameters();
        $this->quizService->addQuiz($data);
        $location = "Location: http://local.quizapp.com/admin/quiz";

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }

    public function editQuizzes (RouteMatch $routeMatch, Request $request) {
        $id = $routeMatch->getRequestAttributes()['id'];
        $quiz = $this->quizService->getQuiz($id);
        $questions = $this->quizService->getAllQuestions();

        return $this->renderer->renderView('admin-quiz-edit-details.html', ['quiz' => $quiz, 'questions' => $questions]);
    }

    public function edit (RouteMatch $routeMatch, Request $request) {
        $id = $routeMatch->getRequestAttributes()['id'];
        $data = $request->getParameters();
        $this->quizService->editQuiz($id, $data);

        $location = "Location: http://local.quizapp.com/admin/quiz";

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }
}