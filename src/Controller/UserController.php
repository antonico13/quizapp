<?php

namespace Quizapp\Controller;

use Framework\Contracts\RendererInterface;
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
     * UserController constructor.
     * @param RendererInterface $renderer
     * @param ServiceInterface $userService
     */
    public function __construct (RendererInterface $renderer, ServiceInterface $userService)
    {
        parent::__construct($renderer);
        $this->userService = $userService;
    }

    public function getLogin (RouteMatch $routeMatch, Request $request)
    {
        return $this->renderer->renderView('login.html', []);
    }

    public function login (RouteMatch $routeMatch, Request $request)
    {
        $email = $request->getParameter('email');
        $password = hash('sha256', $request->getParameter('password'));
        $location =  'Location: http://local.quizapp.com/'.$this->userService->login($email, $password);

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }

    public function getHomepage (RouteMatch $routeMatch, Request $request)
    {
        $name = $this->userService->getName();

        return $this->renderer->renderView('candidate-quiz-listing.html', ['name' => $name]);
    }

    public function getDashboard (RouteMatch $routeMatch, Request $request)
    {
        $name = $this->userService->getName();

        return $this->renderer->renderView('admin-dashboard.html', ['name' => $name]);
    }

    public function logout (RouteMatch $routeMatch, Request $request)
    {
        $this->userService->logout();
        $location = 'Location: http://local.quizapp.com/';

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }

    public function getQuizzes (RouteMatch $routeMatch, Request $request)
    {
        $data = $this->userService->getQuizzes();

        return $this->renderer->renderView('admin-quizzes-listing.html', ['data' => $data]);
    }

    //^^^^maybe should be in security controller

    public function getUsers (RouteMatch $routeMatch, Request $request)
    {
        $count = $this->userService->getUserCount();
        $page = $request->getParameter('page');
        $name = $request->getParameter('search');
        $role = $request->getParameter('role');
        $filters = [];

        if ($page == null) {
            $page = 1;
        }

        if ($name) {
            $filters['name'] = $name;
        }
        if ($role) {
            $filters['role'] = $role;
        }

        if ($filters) {
            $count = $this->userService->getUsersCountSearch($filters);
        }

        $data = $this->userService->getUsers($page);

        if ($filters) {
            $data = $this->userService->getUsersSearch($filters, $page);
        }

        $count = ceil($count/5);

        return $this->renderer->renderView('admin-users-listing.html', ['data' => $data, 'count' => $count, 'page' => $page, 'name' => $name]);
    }

    public function addUsers (RouteMatch $routeMatch, Request $request)
    {

        return $this->renderer->renderView('admin-user-details.html', []);
    }

    public function add (RouteMatch $routeMatch, Request $request)
    {
        $data = $request->getParameters();
        $this->userService->addUser($data);
        $location = "Location: http://local.quizapp.com/admin/user";
        $body = Stream::createFromString("");

        return new Response($body, '1.1', '301', $location);
    }

    public function delete (RouteMatch $routeMatch, Request $request)
    {
        $id = $routeMatch->getRequestAttributes()['id'];
        $this->userService->deleteUser($id);
        $location = "Location: http://local.quizapp.com/admin/user";

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }

    public function editUser (RouteMatch $routeMatch, Request $request)
    {
        $id = $routeMatch->getRequestAttributes()['id'];
        $user = $this->userService->getUser($id);

        return $this->renderer->renderView('admin-user-edit-details.html', ['user' => $user]);
    }

    public function edit (RouteMatch $routeMatch, Request $request)
    {
        $id = $routeMatch->getRequestAttributes()['id'];
        $data = $request->getParameters();
        $this->userService->editUser($id, $data);

        $location = "Location: http://local.quizapp.com/admin/user";

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }
}