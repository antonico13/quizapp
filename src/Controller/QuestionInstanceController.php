<?php


namespace Quizapp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Routing\RouteMatch;
use HighlightLib\CodeHighlight;
use Quizapp\Contracts\ServiceInterface;
use Quizapp\Entity\QuestionInstance;
use Quizapp\Entity\QuizInstance;
use Quizapp\Entity\TextInstance;

class QuestionInstanceController extends AbstractController
{
    private $questionService;
    private $session;

    public function __construct (RendererInterface $renderer, ServiceInterface $questionService, SessionInterface $session)
    {
        parent::__construct($renderer);
        $this->questionService = $questionService;
        $this->session = $session;
    }

    public function getQuestion(RouteMatch $routeMatch, Request $request) {
        $questionInstanceID = $routeMatch->getRequestAttributes()['id'] - 1;
        $quizInstanceID = $this->session->get('quizInstanceID');
        $count = $this->questionService->count($quizInstanceID);
        /**
         * @var QuestionInstance $question
         */
        $question = $this->questionService->getQuestion($quizInstanceID, $questionInstanceID);
        $answers = $question->getAnswers();
        return $this->renderer->renderView('candidate-quiz-page.phtml', ['question' => $question, 'quizInstanceID' => $quizInstanceID,  'questionNumber' => $questionInstanceID, 'count' => $count, 'answers' => $answers]);
    }
}