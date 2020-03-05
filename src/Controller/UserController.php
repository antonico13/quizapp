<?php

namespace Quizapp\Controller;

use Framework\Contracts\RendererInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Routing\RouteMatch;
use Quizapp\Entity\User;

class UserController extends AbstractController
{
    /**
     * UserController constructor.
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        parent::__construct($renderer);
    }

    /**
     * @param RouteMatch $routeMatch
     * @param Request $request
     * @return Response
     */
    public function delete (RouteMatch $routeMatch, Request $request) {
        return $this->renderer->renderJson($routeMatch->getRequestAttributes());
    }

    /**
     * @param RouteMatch $routeMatch
     * @param Request $request
     * @return Response
     */
    public function add (RouteMatch $routeMatch, Request $request) {
        $user = new User();
        $data = ['name' => $request->getParameter('name'), 'email' => $request->getParameter('email'), 'role' => $request->getParameter('role')];
        return $this->renderer->renderJson($data);
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