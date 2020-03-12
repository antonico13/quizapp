<?php


namespace Quizapp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
use Framework\Routing\RouteMatch;
use Quizapp\Contracts\ServiceInterface;

class QuizTemplateController extends SecurityController
{
    private $quizService;

    public function __construct (RendererInterface $renderer, SessionInterface $session, ServiceInterface $quizService)
    {
        parent::__construct($renderer, $session);
        $this->quizService = $quizService;
    }

    public function getQuizzes (RouteMatch $routeMatch, Request $request)
    {
        if ($this->isLoggedIn()) {
            if ($this->isAdmin()) {
                $userid = $this->session->get('id');
                $page = $request->getParameter('page');
                $search = $request->getParameter('search');
                $sorts = $request->getParameter('sorts');

                if ($sorts == null) {
                    $sorts = [];
                }

                $count = $this->quizService->getQuizzesCount($userid, null, $search);

                if ($page == null || $page == 0 || $page > $count || !is_numeric($page)) {
                    $page = 1;
                }

                $count = ceil($count / 5);
                $data = $this->quizService->getQuizzes($userid, $sorts, $page, 5, $search);

                return $this->renderer->renderView('admin-quizzes-listing.phtml', ['data' => $data, 'count' => $count, 'page' => $page, 'search' => $search, 'userName' => $this->session->get('name')]);
            }
        }
        return $this->renderer->renderException(['message' => 'Forbidden'], 403);
    }

    public function getAllQuizzes (RouteMatch $routeMatch, Request $request)
    {
        if ($this->isLoggedIn()) {
            $userid = $this->session->get('id');
            $page = $request->getParameter('page');
            $search = $request->getParameter('search');
            $sorts = $request->getParameter('sorts');

            if ($sorts == null) {
                $sorts = [];
            }

            $count = $this->quizService->getQuizzesCount(null, null, $search);

            if ($page == null || $page == 0 || $page > $count || !is_numeric($page)) {
                $page = 1;
            }

            $data = $this->quizService->getQuizzes(null, $sorts, $page, 5, $search);

            $count = ceil($count/5);

            return $this->renderer->renderView('candidate-quiz-listing.phtml', ['data' => $data, 'count' => $count, 'page' => $page, 'search' => $search, 'name' => $this->session->get('name')]);
        }
        return $this->renderer->renderException(['message' => 'Forbidden'], 403);
    }

    public function addQuizzes (RouteMatch $routeMatch, Request $request) {
        if ($this->isLoggedIn()) {
            if ($this->isAdmin()) {
                $questions = $this->quizService->getAllQuestions();
                return $this->renderer->renderView('admin-quiz-details.phtml', ['questions' => $questions, 'userName' => $this->session->get('name')]);
            }
        }
        return $this->renderer->renderException(['message' => 'Forbidden'], 403);
    }

    public function add (RouteMatch $routeMatch, Request $request) {
        if ($this->isLoggedIn()) {
            if ($this->isAdmin()) {
                $userid = $this->session->get('id');
                $data = $request->getParameters();
                $this->quizService->addQuiz($userid, $data);
                $location = $request->getUri()->getScheme() . '://' . substr($request->getUri()->getAuthority(), 0, -3) . '/admin/quiz';

                return $this->redirect($location, 301);
            }
        }
        return $this->renderer->renderException(['message' => 'Forbidden'], 403);
    }

    public function editQuizzes (RouteMatch $routeMatch, Request $request) {
        if ($this->isLoggedIn()) {
            if ($this->isAdmin()) {
                $id = $routeMatch->getRequestAttributes()['id'];
                $quiz = $this->quizService->getQuiz($id);
                if ($quiz == null) {
                    return $this->renderer->renderException(['message' => 'Not found'], 404);
                }
                $questions = $this->quizService->getAllQuestions();
                $selectedQuestions = $this->quizService->getSelectedQuestions($id);
                $selected = [];
                foreach ($selectedQuestions as $selectedQuestion) {
                    $selected[$selectedQuestion['questiontemplateid']] = true;
                }

                return $this->renderer->renderView('admin-quiz-edit-details.phtml', ['quiz' => $quiz, 'questions' => $questions, 'selectedQuestions' => $selected, 'userName' => $this->session->get('name')]);
            }
        }

        return $this->renderer->renderException(['message' => 'Forbidden'], 403);
    }

    public function edit (RouteMatch $routeMatch, Request $request) {
        if ($this->isLoggedIn()) {
            if ($this->isAdmin()) {
                $userid = $this->session->get('id');
                $id = $routeMatch->getRequestAttributes()['id'];
                $data = $request->getParameters();
                $this->quizService->editQuiz($userid, $id, $data);

                $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/admin/quiz';

                return $this->redirect($location, 301);
            }
        }
        return $this->renderer->renderException(['message' => 'Forbidden'], 403);
    }

    public function delete (RouteMatch $routeMatch, Request $request) {
        if ($this->isLoggedIn()) {
            if ($this->isAdmin()) {
                $id = $routeMatch->getRequestAttributes()['id'];
                    $this->quizService->deleteQuiz($id);

                    $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/admin/quiz';

                    return $this->redirect($location, 301);
            }
        }
        return $this->renderer->renderException(['message' => 'Forbidden'], 403);
    }
}