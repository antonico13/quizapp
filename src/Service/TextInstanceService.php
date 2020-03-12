<?php


namespace Quizapp\Service;


use HighlightLib\CodeHighlight;
use ReallyOrm\Repository\RepositoryInterface;
use ReallyOrm\Repository\RepositoryManagerInterface;

class TextInstanceService extends AbstractService
{
    /**
     * TextInstanceService constructor.
     * @param RepositoryManagerInterface $repoManager
     * @param RepositoryInterface $entityRepo
     */
    public function __construct(RepositoryManagerInterface $repoManager, RepositoryInterface $entityRepo)
    {
        parent::__construct($repoManager, $entityRepo);
    }

    /**
     * @param int $id
     * @param string $text
     */
    public function save (int $id, string $text) {
        $answer = $this->entityRepo->find($id);
        $answer->setText($text);

        $answer->save();
    }
}