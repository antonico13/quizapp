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
use Quizapp\Entity\QuestionTemplate;

class QuestionTemplateController extends AbstractController
{
    private $questionService;
    private $session;

    public function __construct (RendererInterface $renderer, ServiceInterface $questionService, SessionInterface $session)
    {
        parent::__construct($renderer);
        $this->questionService = $questionService;
        $this->session = $session;
    }

    public function add (RouteMatch $routeMatch, Request $request) {
        $data = $request->getParameters();
        $id = $this->session->get('id');
        $this->questionService->addQuestion($data, $id);
        $location = "Location: http://local.quizapp.com/admin/question";

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }

    public function delete (RouteMatch $routeMatch, Request $request) {
        $id = $routeMatch->getRequestAttributes()['id'];
        $this->questionService->deleteQuestion($id);
        $location = "Location: http://local.quizapp.com/admin/question";

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }

    public function edit (RouteMatch $routeMatch, Request $request) {
        $id = $routeMatch->getRequestAttributes()['id'];
        $data = $request->getParameters();
        $this->questionService->editQuestion($id, $data);

        $location = "Location: http://local.quizapp.com/admin/question";

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }

    public function getQuestions (RouteMatch $routeMatch, Request $request) {
        $id = $this->session->get('id');
        $count = $this->questionService->getQuestionsCount($id);
        $page = $request->getParameter('page');
        $search = $request->getParameter('search');

        if ($page == null || $page == 0 || $page > $count || !is_numeric($page)) {
            $page = 1;
        }

        if ($search) {
            $count = $this->questionService->getQuestionsCountSearch($id, $search);
        }

        $data = $this->questionService->getQuestions($page);

        if ($search) {
            $data = $this->questionService->getQuestionsSearch($id, $search, $page);
        }

        $count = ceil($count/5);

        return $this->renderer->renderView('admin-questions-listing.html', ['data' => $data, 'count' => $count, 'page' => $page, 'search' => $search]);
    }

    public function addQuestions (RouteMatch $routeMatch, Request $request) {

        return $this->renderer->renderView('admin-question-details.html', []);
    }

    public function editQuestion (RouteMatch $routeMatch, Request $request) {
        $id = $routeMatch->getRequestAttributes()['id'];
        /**
         * @var $question QuestionTemplate
         */
        $question = $this->questionService->getQuestion($id);
        $answers = $question->getAnswers();

        return $this->renderer->renderView('admin-question-edit-details.phtml', ['question' => $question, 'answer' => $answers[0]]);
    }

}