<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Track;
use DateTimeImmutable;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class PlayListAdmin extends AbstractAdmin
{
    protected function prePersist(object $object): void
    {
        $object->setCreatedAt(new DateTimeImmutable);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('title', TextType::class)
            ->add(
                'tracks', ModelAutocompleteType::class, [
                    'class' => Track::class,
//                    'query_builder' => function (EntityRepository $er) {
//                        return $er->createQueryBuilder('t')
//                            ->orderBy('t.title', 'ASC');
//                    },
//                    'choice_label' => 'title',
                    'property' => 'title',
                    'multiple' => true,
                ]
            )
//            ->add(
//                'tracks',
//                CollectionType::class, [
//                    'by_reference' => false,
//                ],
//                [
//                    'edit' => 'disable',
//                    'inline' => 'table',
//                    'sortable' => 'position',
//                    'limit' => 3,
//                ]
//            )
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('title');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('title')
            ->add('createdAt')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('title');
    }
}
