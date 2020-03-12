<?php


namespace Quizapp\Service;


use Framework\Contracts\SessionInterface;
use Quizapp\Contracts\ServiceInterface;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;

class AbstractService implements ServiceInterface
{
    /**
     * @var RepositoryManagerInterface
     */
    protected $repoManager;
    /**
     * @var RepositoryInterface
     */
    protected $entityRepo;

    /**
     * AbstractService constructor.
     * @param RepositoryManagerInterface $repoManager
     * @param RepositoryInterface $entityRepo
     */
    public function __construct(RepositoryManagerInterface $repoManager, RepositoryInterface $entityRepo)
    {
        $this->repoManager = $repoManager;
        $this->entityRepo = $entityRepo;
    }

}