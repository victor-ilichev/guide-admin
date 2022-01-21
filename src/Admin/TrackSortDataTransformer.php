<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\PlayList;
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

            /** @var UserHasExpectations $userHasExpectations */
            foreach ($value as $userHasExpectations) {
                $results[] = $userHasExpectations->getExpectation();
            }

            return $results;
        }

        return $value;
    }

    public function reverseTransform($value)
    {
        $results  = new ArrayCollection();
        $position = 0;

        /** @var Expectation $expectation */
        foreach ($value as $expectation) {
            $userHasExpectations = $this->create();
            $userHasExpectations->setExpectation($expectation);
            $userHasExpectations->setPosition($position++);

            $results->add($userHasExpectations);
        }

        // Remove Old values
        $qb = $this->modelManager->getEntityManager()->createQueryBuilder();
        $expr = $this->modelManager->getEntityManager()->getExpressionBuilder();

        $userHasExpectationsToRemove = $qb->select('entity')
            ->from($this->getClass(), 'entity')
            ->where($expr->eq('entity.user', $this->playList->getId()))
            ->getQuery()
            ->getResult();

        foreach ($userHasExpectationsToRemove as $userHasExpectations) {
            $this->modelManager->delete($userHasExpectations, false);
        }

        $this->modelManager->getEntityManager()->flush();

        return $results;
    }
}
