<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Chapter;
use App\Entity\ChapterPlayListSort;
use App\Entity\Excursion;
use App\Entity\ExcursionChapterSort;
use App\Entity\PlayList;
use App\Entity\Track;
use App\Entity\TrackSort;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager;
use Symfony\Component\Form\DataTransformerInterface;

class ChapterPlayListSortDataTransformer implements DataTransformerInterface
{
    /**
     * @var Chapter
     */
    private $chapter;

    /**
     * @var ModelManager
     */
    private $modelManager;

    public function __construct(Chapter $chapter, ModelManager $modelManager)
    {
        $this->chapter = $chapter;
        $this->modelManager = $modelManager;
    }

    public function transform($value)
    {
        if (!is_null($value)) {
            $results = [];

            /** @var ChapterPlayListSort $userHasExpectations */
            foreach ($value as $userHasExpectations) {
                $results[] = $userHasExpectations->getPlayList();
            }

            return $results;
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        $results  = new ArrayCollection();
        $position = 0;

        /** @var PlayList $playList */
        foreach ($value as $playList) {
            $trackSort = new ChapterPlayListSort();
            $trackSort->setCreatedAt(new DateTimeImmutable());
            $trackSort->setPlayList($playList);
            $trackSort->setSort($position++);

            $results->add($trackSort);
        }

        if (null !== $this->chapter->getId()) {
            // Remove Old values
            $qb = $this->modelManager->getEntityManager(ChapterPlayListSort::class)->createQueryBuilder();
            $expr = $this->modelManager->getEntityManager(ChapterPlayListSort::class)->getExpressionBuilder();

            $trackSortToRemove = $qb->select('ts')
                ->from(ChapterPlayListSort::class, 'ts')
                ->where($expr->eq('ts.chapterPl', $this->chapter->getId()))
                ->getQuery()
                ->getResult();

            foreach ($trackSortToRemove as $trackSort) {
                $this->modelManager->delete($trackSort);
            }

            $this->modelManager->getEntityManager(ChapterPlayListSort::class)->flush();
        }

        return $results;
    }
}
