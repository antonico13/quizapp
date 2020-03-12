<?php

namespace Quizapp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Http\Request;
use Framework\Routing\RouteMatch;
use Quizapp\Contracts\ServiceInterface;

class UserController extends SecurityController
{
    /**
     * @var ServiceInterface
     */
    private $userService;

    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param ServiceInterface $userService
     * @param SessionInterface $session
     */
    public function __construct (RendererInterface $renderer, SessionInterface $session, ServiceInterface $userService)
    {
        parent::__construct($renderer, $session);
        $this->userService = $userService;
    }

    public function getLogin (RouteMatch $routeMatch, Request $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderView('login.phtml', ['notFound' => false]);
        }
        if (!$this->isAdmin()) {
            $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/user';
            return $this->redirect($location, 301);
        }

        $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/admin';
        return $this->redirect($location, 301);
    }

    public function login (RouteMatch $routeMatch, Request $request)
    {
        $email = $request->getParameter('email');
        $password = hash('sha256', $request->getParameter('password'));

        $data = $this->userService->login($email, $password);

        if (!$data) {
            return $this->renderer->renderView('login.phtml', ['notFound' => true]);
        }

        $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/'.$data[0];
        $this->session->set('role', $data[0]);
        $this->session->set('id', $data[1]);
        $this->session->set('name', $data[2]);

        return $this->redirect($location, 301);
    }

    public function getHomepage (RouteMatch $routeMatch, Request $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        $name = $this->session->get('name');

        return $this->renderer->renderView('candidate-quiz-listing.phtml', ['name' => $name,
                                                                                    'userName' => $this->session->get('name')]);
    }

    public function getDashboard (RouteMatch $routeMatch, Request $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        if (!$this->isAdmin()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        $name = $this->session->get('name');

        return $this->renderer->renderView('admin-dashboard.phtml', ['name' => $name,
                                                                            'userName' => $this->session->get('name')]);
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
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        if (!$this->isAdmin()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        $data = $this->userService->getQuizzes($this->session->get('id'));

        return $this->renderer->renderView('admin-quizzes-listing.phtml', ['data' => $data,
                                                                                    'userName' => $this->session->get('name')]);
    }

    public function getUsers (RouteMatch $routeMatch, Request $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        if (!$this->isAdmin()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

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

        return $this->renderer->renderView('admin-users-listing.phtml', ['data' => $data,
                                                                                'count' => $count,
                                                                                'page' => $page,
                                                                                'name' => $name,
                                                                                'userName' => $this->session->get('name')]);
    }

    public function addUsers (RouteMatch $routeMatch, Request $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        if (!$this->isAdmin()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        return $this->renderer->renderView('admin-user-details.phtml', ['userName' => $this->session->get('name'),
                                                                                'emailExists' => false]);
    }

    public function add (RouteMatch $routeMatch, Request $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        if (!$this->isAdmin()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        $data = $request->getParameters();
        if ($this->userService->addUser($data) == null) {
            return $this->renderer->renderView('admin-user-details.phtml', ['userName' => $this->session->get('name'),
                                                                                    'emailExists' => true]);
        }

        $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/admin/user';

        return $this->redirect($location, 301);
    }

    public function delete (RouteMatch $routeMatch, Request $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        if (!$this->isAdmin()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        $id = $routeMatch->getRequestAttributes()['id'];
        $this->userService->deleteUser($id);

        $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/admin/user';

        if ($id == $this->session->get('id')) {
            $location = substr($location, 0, -11).'/logout';
        }

        return $this->redirect($location, 301);
    }

    public function editUser (RouteMatch $routeMatch, Request $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        if (!$this->isAdmin()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        $id = $routeMatch->getRequestAttributes()['id'];
        $user = $this->userService->getUser($id);
        if ($user == null) {
            return $this->renderer->renderException(['message' => 'User not found'], 404);
        }

        return $this->renderer->renderView('admin-user-edit-details.phtml', ['user' => $user,
                                                                                'userName' => $this->session->get('name'),
                                                                                    'emailExists' => false]);
    }

    public function edit (RouteMatch $routeMatch, Request $request)
    {
        if (!$this->isLoggedIn()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        if (!$this->isAdmin()) {
            return $this->renderer->renderException(['message' => 'Forbidden'], 403);
        }

        $id = $routeMatch->getRequestAttributes()['id'];
        $data = $request->getParameters();
        if ($this->userService->editUser($id, $data) == null) {
            $user = $this->userService->getUser($id);
            return $this->renderer->renderView('admin-user-edit-details.phtml', ['user' => $user,
                                                                                        'userName' => $this->session->get('name'),
                                                                                        'emailExists' => true]);
        }

        if ($data['role'] != 'user') {
            $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/admin/user';

            return $this->redirect($location, 301);
        }

        $location = $request->getUri()->getScheme().'://'.substr($request->getUri()->getAuthority(), 0, -3).'/user';

        return $this->redirect($location, 301);
    }
}