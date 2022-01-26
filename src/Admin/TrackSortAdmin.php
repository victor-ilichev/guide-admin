<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Excursion;
use App\Entity\PlayList;
use App\Entity\Track;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use DateTimeImmutable;

final class TrackSortAdmin extends AbstractAdmin
{
    protected function prePersist(object $object): void
    {
        $object->setCreatedAt(new DateTimeImmutable);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('playList', ModelAutocompleteType::class, [
                'class' => PlayList::class,
                'required' => false,
                'property' => 'title',
                'multiple' => true,
            ])
//            ->add('track', ModelAutocompleteType::class, [
//                'class' => Track::class,
//                'required' => false,
//                'property' => 'title',
//                'multiple' => true,
//            ])
            ->add('sort', HiddenType::class)
//            ->add('excursion', EntityType::class, [
//                'class' => Excursion::class,
//                'choice_label' => 'title',
//            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('title');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id')
            ->add('playList')
            ->add('track')
            ->add('sort')
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
//создаем свой тип поля для админки
//для сохранения файлов
//сохраняем один файл для трэка
//и ставим тип файла, например видео или аудио
//в мостпартнере сделано с этим
//https://github.com/dustin10/VichUploaderBundle
