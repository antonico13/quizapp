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
    public function __construct(RendererInterface $renderer, ServiceInterface $userService)
    {
        parent::__construct($renderer);
        $this->userService = $userService;
    }

    /**
     * @param RouteMatch $routeMatch
     * @param Request $request
     * @return Response
     */
    public function delete (RouteMatch $routeMatch, Request $request) {
        return $this->renderer->renderJson($routeMatch->getRequestAttributes());
    }

    public function getLogin (RouteMatch $routeMatch, Request $request) {
        return $this->renderer->renderView('login.html', []);
    }

    /**
     * @param RouteMatch $routeMatch
     * @param Request $request
     * @return Response
     */
    public function add (RouteMatch $routeMatch, Request $request) {
        $user = new User();
        $data = ['name' => $request->getParameter('name'), 'email' => $request->getParameter('email'), 'role' => $request->getParameter('role')];
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setRole($data['role']);
        $this->repositoryManager->register($user);
        $user->save();
        return $this->renderer->renderJson($data);
    }

    public function getQuiz (RouteMatch $routeMatch, Request $request) {
        $id = $routeMatch->getRequestAttributes()['id'];
        $user = $this->repositoryManager->getRepository(User::class)->find($id);
        $quizes = $this->repositoryManager->getRepository(User::class)->getForeignEntities(QuizTemplate::class, $user);
        var_dump($quizes);
    }

    public function login (RouteMatch $routeMatch, Request $request) {
        $email = $request->getParameter('email');
        $password = hash('sha256', $request->getParameter('password'));
        $location =  'Location: http://local.quizapp.com/'.$this->userService->login($email, $password);

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }

    public function getHomepage(RouteMatch $routeMatch, Request $request) {
        $name = $this->userService->getName();

        return $this->renderer->renderView('candidate-quiz-listing.html', ['name' => $name]);
    }

    public function getDashboard(RouteMatch $routeMatch, Request $request) {
        $name = $this->userService->getName();

        return $this->renderer->renderView('admin-dashboard.html', ['name' => $name]);
    }

    public function logout(RouteMatch $routeMatch, Request $request) {
        $this->userService->logout();
        $location = 'Location: http://local.quizapp.com/';

        $body = Stream::createFromString("");
        return new Response($body, '1.1', '301', $location);
    }

    /**
     * @param RouteMatch $routeMatch
     * @param Request $request
     * @return Response
     */
    public function update (RouteMatch $routeMatch, Request $request) {
        $message = $request->getBody()->getContents();
        $toRender = array_merge($routeMatch->getRequestAttributes(), ['message' => $message]);

        $query = $request->getUri()->getQuery();
        $arr = explode('&', $query);
        foreach ($arr as $key => $value) {
            $arr[$key] = explode('=', $value);
            $toRender = array_merge($toRender, [$arr[$key][0] => $arr[$key][1]]);
        }
        return $this->renderer->renderView('user2.phtml', $toRender);
    }

    /**
     * @param RouteMatch $routeMatch
     * @param Request $request
     * @return Response
     */
    public function get(RouteMatch $routeMatch, Request $request) : Response {
        return $this->renderer->renderView('user.phtml', $routeMatch->getRequestAttributes());
    }
}