<?php


namespace Quizapp\Controller;


use Framework\Contracts\RendererInterface;
use Framework\Contracts\SessionInterface;
use Framework\Controller\AbstractController;
use Framework\Http\Request;
use Quizapp\Contracts\ServiceInterface;

class SecurityController extends AbstractController
{
    protected $session;

    public function __construct (RendererInterface $renderer, SessionInterface $session)
    {
        parent::__construct($renderer);
        $this->session = $session;
    }

    public function isLoggedIn() {
        if ($this->session->get('id')) {
            return true;
        }

        return false;
    }

    public function isAdmin() {
        if (!$this->isLoggedIn()) {
            return false;
        }
        if ($this->session->get('role') != 'admin') {
            return false;
        }

        return true;
    }
}