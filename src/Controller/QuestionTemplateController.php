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
        if ($this->session->get('role') == 'admin') {
            $data = $request->getParameters();
            $id = $this->session->get('id');
            $this->questionService->addQuestion($data, $id);
            $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/admin/question';

            return $this->redirect($location, 301);
        }
    }

    public function delete (RouteMatch $routeMatch, Request $request) {
        $id = $routeMatch->getRequestAttributes()['id'];
        $this->questionService->deleteQuestion($id);
        $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/admin/question';

        return $this->redirect($location, 301);
    }

    public function edit (RouteMatch $routeMatch, Request $request) {
        $id = $routeMatch->getRequestAttributes()['id'];
        $data = $request->getParameters();
        $this->questionService->editQuestion($id, $data);

        $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/admin/question';

        return $this->redirect($location, 301);
    }

    public function getQuestions (RouteMatch $routeMatch, Request $request) {
        $id = $this->session->get('id');
        $page = $request->getParameter('page');
        $search = $request->getParameter('search');
        $sorts = $request->getParameter('sort');

        $count = $this->questionService->getQuestionsCount($id, $search);
        $count = ceil($count/5);

        if ($page == null || $page == 0 || $page > $count || !is_numeric($page)) {
            $page = 1;
        }

        if ($sorts == null) {
            $sorts = [];
        }

        $data = $this->questionService->getQuestions($id, $sorts, $page,  5, $search);

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