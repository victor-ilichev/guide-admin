<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Track;
use App\Entity\TrackSort;
use DateTimeImmutable;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
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
//            ->add(
//                'trackSorts',
//                ModelType::class, [
//                    'label' => 'User\'s expectations',
//                    'query' => $this->getModelManager()->createQuery(Track::class),
//                    'required' => false,
//                    'multiple' => true,
//                    'by_reference' => false,
//                    'sortable' => true,
//                ]
//            )
            ->add(
                'trackSorts', ModelAutocompleteType::class, [
                    'class' => Track::class,
                    'template' => 'Form/Type/sonata_type_model_autocomplete.html.twig',
                    'btn_add' => false,
//                    'query' => $this->getModelManager()->createQuery(Track::class),
//                    'query_builder' => function (EntityRepository $er) {
//                        return $er->createQueryBuilder('t')
//                            ->orderBy('t.title', 'ASC');
//                    },
//                    'choice_label' => 'title',
                    'property' => 'title',
                    'multiple' => true,
                    'callback' => static function (AdminInterface $admin, string $property, $searchText): void {
                    // SELECT o FROM App\Entity\TrackSort o INNER JOIN o.track t WHERE t.title = :title AND o.title LIKE :title_0 ORDER BY o.id ASC
                        $datagrid = $admin->getDatagrid();
                        $query = $datagrid->getQuery();

//                        $query = $this->getModelManager()->createQuery(Track::class);
//                        $queryBuilder->from('Post', 'p');
//
//                        $proxyQuery = new ProxyQuery($queryBuilder);
//                        $proxyQuery->leftJoin('p.tags', 't');
//                        $proxyQuery->setSortBy('name');
//                        $proxyQuery->setMaxResults(10);
//
//                        $results = $proxyQuery->execute();
                        $query->resetDQLPart('select');
                        $query->resetDQLPart('from');
                        $r = $admin->getRequest();

                        $query->select('p')
                            ->from(Track::class, 'p')
                            ->where('p.title LIKE :title')
                            ->setParameter('title', '%' . $searchText . '%')
                        ;
//                        $query->innerJoin($query->getRootAlias().'.job','j')
//                            ->innerJoin('j.company','c')
//                            ->where('c.id = :company')
//                            ->setParameter('company', $user->getCompany()->getId());
//                        $query->innerJoin($query->getRootAlias() . '.track', 't')
//                            ->where('t.title LIKE :title')
//                            ->setParameter('title', $admin->getRequest()->get('q'))
//                        ;
//                        $query
//                            ->andWhere($query->getRootAlias() . '.track.title=:barValue')
//                            ->setParameter('barValue', $admin->getRequest()->get('q'))
//                        ;
                        // SELECT o FROM App\Entity\TrackSort o INNER JOIN o.track t WHERE t.title = :title ORDER BY o.id ASC
                        // закоментил строку ниже
                        // $datagrid->setValue($property, null, $searchText);
                    },
//                    'to_string_callback' => function($entity, $property) {
//                        return $entity->getTitle();
//                    },
                ],
                [
                    'edit' => 'disable',
                    'inline' => 'table',
                    'sortable' => 'position',
                    'limit' => 3,
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
            // worked
//            ->add('trackSorts', ModelType::class, [
//                'label'        => 'User\'s expectations',
//                'query'        => $this->getModelManager()->createQuery(Track::class),
//                'required'     => false,
//                'multiple'     => true,
//                'by_reference' => false,
//                'sortable'     => true,
//            ])
//            ->add('trackSorts', ModelAutocompleteType::class, [
//                'label'        => 'User\'s expectations',
//                'required'     => false,
//                'multiple'     => true,
//                'by_reference' => false,
//                'property' => 'title',
//            ])
        ;

        $form
            ->get('trackSorts')
            ->addModelTransformer(new TrackSortDataTransformer($this->getSubject(), $this->getModelManager()));

//        $result = $em->getRepository("Orders")->createQueryBuilder('o')
//            ->where('o.OrderEmail = :email')
//            ->andWhere('o.Product LIKE :product')
//            ->setParameter('email', 'some@mail.com')
//            ->setParameter('product', 'My Products%')
//            ->getQuery()
//            ->getResult();
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
