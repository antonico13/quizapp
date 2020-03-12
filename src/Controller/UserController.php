<?php

namespace Quizapp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\RedirectResponse;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\Stream;
use Framework\Routing\RouteMatch;
use Quizapp\Contracts\ServiceInterface;
use Quizapp\Entity\QuizTemplate;
use Quizapp\Entity\User;
use ReallyOrm\Repository\RepositoryManagerInterface;

class UserController extends AbstractController
{
    /**
     * @var ServiceInterface
     */
    private $userService;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param ServiceInterface $userService
     */
    public function __construct (RendererInterface $renderer, ServiceInterface $userService, SessionInterface $session)
    {
        parent::__construct($renderer);
        $this->userService = $userService;
        $this->session = $session;
    }

    public function getLogin (RouteMatch $routeMatch, Request $request)
    {
        return $this->renderer->renderView('login.html', []);
    }

    public function login (RouteMatch $routeMatch, Request $request)
    {
        $email = $request->getParameter('email');
        $password = hash('sha256', $request->getParameter('password'));

        $data = $this->userService->login($email, $password);

        if ($data) {
            $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/'.$data[0];
            $this->session->set('id', $data[1]);
            $this->session->set('role', $data[0]);
            $this->session->set('name', $data[2]);
        }

        return $this->redirect($location, 301);
    }

    public function getHomepage (RouteMatch $routeMatch, Request $request)
    {
        $name = $this->session->get('name');

        return $this->renderer->renderView('candidate-quiz-listing.phtml', ['name' => $name]);
    }

    public function getDashboard (RouteMatch $routeMatch, Request $request)
    {
        $name = $this->session->get('name');

        return $this->renderer->renderView('admin-dashboard.html', ['name' => $name]);
    }

    public function logout (RouteMatch $routeMatch, Request $request)
    {
        $this->session->destroy();
        $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/';

        return $this->redirect($location, 301);
    }

    //^^^^maybe should be in security controller

    public function getQuizzes (RouteMatch $routeMatch, Request $request)
    {
        $data = $this->userService->getQuizzes($this->session->get('id'));

        return $this->renderer->renderView('admin-quizzes-listing.phtml', ['data' => $data]);
    }

    public function getUsers (RouteMatch $routeMatch, Request $request)
    {
        $page = $request->getParameter('page');
        $name = $request->getParameter('search');
        $role = $request->getParameter('role');
        $sorts = $request->getParameter('sort');

        if ($sorts == null) {
            $sorts = [];
        }

        $filters = [];

        if ($name) {
            $filters['name'] = $name;
        }
        if ($role) {
            $filters['role'] = $role;
        }

        $count = $this->userService->getUserCount($filters);

        $count = ceil($count/5);

        if ($page == null || $page == 0 || $page > $count || !is_numeric($page)) {
            $page = 1;
        }

        $data = $this->userService->getUsers($filters, $sorts, $page, 5);

        return $this->renderer->renderView('admin-users-listing.phtml', ['data' => $data, 'count' => $count, 'page' => $page, 'name' => $name]);
    }

    public function addUsers (RouteMatch $routeMatch, Request $request)
    {

        return $this->renderer->renderView('admin-user-details.html', []);
    }

    public function add (RouteMatch $routeMatch, Request $request)
    {
        $data = $request->getParameters();
        $this->userService->addUser($data);

        $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/admin/user';

        return $this->redirect($location, 301);
    }

    public function delete (RouteMatch $routeMatch, Request $request)
    {
        $id = $routeMatch->getRequestAttributes()['id'];
        $this->userService->deleteUser($id);

        $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/admin/user';

        return $this->redirect($location, 301);
    }

    public function editUser (RouteMatch $routeMatch, Request $request)
    {
        $id = $routeMatch->getRequestAttributes()['id'];
        $user = $this->userService->getUser($id);

        return $this->renderer->renderView('admin-user-edit-details.phtml', ['user' => $user]);
    }

    public function edit (RouteMatch $routeMatch, Request $request)
    {
        $id = $routeMatch->getRequestAttributes()['id'];
        $data = $request->getParameters();
        $this->userService->editUser($id, $data);

        $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/admin/user';

        return $this->redirect($location, 301);
    }
}