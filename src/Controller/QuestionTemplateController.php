<?php


namespace Quizapp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use Framework\Routing\RouteMatch;
use Quizapp\Contracts\ServiceInterface;
use SebastianBergmann\Version;

class QuestionTemplateController extends AbstractController
{
    private $questionService;

    public function __construct (RendererInterface $renderer, ServiceInterface $questionService)
    {
        parent::__construct($renderer);
        $this->questionService = $questionService;
    }

    public function add (RouteMatch $routeMatch, Request $request) {
        $data = $request->getParameters();
        $this->questionService->addQuestion($data);
        $location = "Location: http://local.quizapp.com/admin/questions";

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }

    public function delete (RouteMatch $routeMatch, Request $request) {
        $id = $request->getUri()->getQueryArray()[0][1];
        $this->questionService->deleteQuestion($id);
        $location = "Location: http://local.quizapp.com/admin/questions";

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }

    public function edit (RouteMatch $routeMatch, Request $request) {
        $id = $request->getUri()->getQueryArray()[0][1];
        $data = $request->getParameters();
        $this->questionService->editQuestion($id, $data);

        $location = "Location: http://local.quizapp.com/admin/questions";

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }

    public function getQuestions (RouteMatch $routeMatch, Request $request) {
        $text = $request->getParameter('search');
        $data = $this->questionService->getQuestions($text);

        return $this->renderer->renderView('admin-questions-listing.html', ['data' => $data]);
    }

    public function addQuestions (RouteMatch $routeMatch, Request $request) {

        return $this->renderer->renderView('admin-question-details.html', []);
    }

    public function editQuestion (RouteMatch $routeMatch, Request $request) {
        $id = $request->getUri()->getQueryArray()[0][1];
        $question = $this->questionService->getQuestion($id);

        return $this->renderer->renderView('admin-question-edit-details.html', ['question' => $question]);
    }

}