<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Track;
use App\Entity\TrackSort;
use DateTimeImmutable;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Show\ShowMapper;
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
                'trackSorts', ModelAutocompleteType::class, [
                    'class' => Track::class,
                    'template' => 'Form/Type/sonata_type_model_autocomplete.html.twig',
                    'btn_add' => false,
                    'property' => 'title',
                    'multiple' => true,
                    'callback' => static function (AdminInterface $admin, string $property, $searchText): void {
                        $datagrid = $admin->getDatagrid();
                        $query = $datagrid->getQuery();

                        $query->resetDQLPart('select');
                        $query->resetDQLPart('from');

                        $query->select('p')
                            ->from(Track::class, 'p')
                            ->where('p.title LIKE :title')
                            ->setParameter('title', '%' . $searchText . '%')
                        ;
                    },
                ]
            )
        ;

        $form
            ->get('trackSorts')
            ->addModelTransformer(new TrackSortDataTransformer($this->getSubject(), $this->getModelManager()));
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
