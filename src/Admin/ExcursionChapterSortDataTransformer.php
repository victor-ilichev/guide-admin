<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Chapter;
use App\Entity\Excursion;
use App\Entity\ExcursionChapterSort;
use App\Entity\PlayList;
use App\Entity\Track;
use App\Entity\TrackSort;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager;
use Symfony\Component\Form\DataTransformerInterface;

class ExcursionChapterSortDataTransformer implements DataTransformerInterface
{
    /**
     * @var Excursion
     */
    private $excursion;

    /**
     * @var ModelManager
     */
    private $modelManager;

    public function __construct(Excursion $excursion, ModelManager $modelManager)
    {
        $this->excursion = $excursion;
        $this->modelManager = $modelManager;
    }

    public function transform($value)
    {
        if (!is_null($value)) {
            $results = [];

            /** @var ExcursionChapterSort $userHasExpectations */
            foreach ($value as $userHasExpectations) {
                $results[] = $userHasExpectations->getChapter();
            }

            return $results;
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        $results  = new ArrayCollection();
        $position = 0;

        /** @var Chapter $expectation */
        foreach ($value as $expectation) {
            $trackSort = new ExcursionChapterSort();
            $trackSort->setCreatedAt(new DateTimeImmutable());
            $trackSort->setChapter($expectation);
            $trackSort->setSort($position++);

            $results->add($trackSort);
        }

        if (null !== $this->excursion->getId()) {
            // Remove Old values
            $qb = $this->modelManager->getEntityManager(ExcursionChapterSort::class)->createQueryBuilder();
            $expr = $this->modelManager->getEntityManager(ExcursionChapterSort::class)->getExpressionBuilder();

            $trackSortToRemove = $qb->select('ts')
                ->from(ExcursionChapterSort::class, 'ts')
                ->where($expr->eq('ts.excursion', $this->excursion->getId()))
                ->getQuery()
                ->getResult();

            foreach ($trackSortToRemove as $trackSort) {
                $this->modelManager->delete($trackSort);
            }

            $this->modelManager->getEntityManager(ExcursionChapterSort::class)->flush();
        }

        return $results;
    }
}
