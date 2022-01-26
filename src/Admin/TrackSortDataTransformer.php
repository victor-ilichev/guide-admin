<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\PlayList;
use App\Entity\Track;
use App\Entity\TrackSort;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager;
use Symfony\Component\Form\DataTransformerInterface;

class TrackSortDataTransformer implements DataTransformerInterface
{
    /**
     * @var PlayList
     */
    private $playList;

    /**
     * @var ModelManager
     */
    private $modelManager;

    public function __construct(PlayList $playList, ModelManager $modelManager)
    {
        $this->playList = $playList;
        $this->modelManager = $modelManager;
    }

    public function transform($value)
    {
        if (!is_null($value)) {
            $results = [];

            /** @var TrackSort $userHasExpectations */
            foreach ($value as $userHasExpectations) {
                $results[] = $userHasExpectations->getTrack();
            }

            return $results;
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        $results  = new ArrayCollection();
        $position = 0;

        /** @var Track $expectation */
        foreach ($value as $expectation) {
            $trackSort = new TrackSort();
            $trackSort->setCreatedAt(new DateTimeImmutable());
            $trackSort->setTrack($expectation);
            $trackSort->setSort($position++);

            $results->add($trackSort);
        }

        if (null !== $this->playList->getId()) {
            // Remove Old values
            $qb = $this->modelManager->getEntityManager(TrackSort::class)->createQueryBuilder();
            $expr = $this->modelManager->getEntityManager(TrackSort::class)->getExpressionBuilder();

            $trackSortToRemove = $qb->select('ts')
                ->from(TrackSort::class, 'ts')
                ->where($expr->eq('ts.playList', $this->playList->getId()))
                ->getQuery()
                ->getResult();

            foreach ($trackSortToRemove as $trackSort) {
                $this->modelManager->delete($trackSort);
            }

            $this->modelManager->getEntityManager(TrackSort::class)->flush();
        }

        return $results;
    }
}
