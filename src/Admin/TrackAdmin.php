<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Track;
use App\Service\FileManager\Exception\UploadException;
use App\Service\FileManager\FileManager;
use DateTimeImmutable;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints;

final class TrackAdmin extends AbstractAdmin
{
    /**
     * @var FileManager
     */
    private $fileManager;

    protected function prePersist(object $object): void
    {
        $object->setCreatedAt(new DateTimeImmutable);
        $this->manageFileUpload($object, $this->getForm()->get('file')->getData());
    }

    public function preUpdate(object $object): void
    {
        $this->manageFileUpload($object, $this->getForm()->get('file')->getData());
    }

    private function manageFileUpload(Track $track, ?UploadedFile $uploadedFile): void
    {
        if ($uploadedFile) {
            try {
                $filePath = $this->fileManager->upload($uploadedFile);
                $track->setFileName($filePath);
            } catch (UploadException $exception) {
                throw $exception;
            }
        }
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('title', TextType::class)
            ->add('file', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Constraints\File([
                        'maxSize' => '33m',
                        'mimeTypes' => [
                            'audio/mpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid png|jpeg file',
                    ])
                ],
            ])
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
            ->addIdentifier('title')
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

    public function setFileManager(FileManager $fileManager): void
    {
        $this->fileManager = $fileManager;
    }
}
//создаем свой тип поля для админки
//для сохранения файлов
//сохраняем один файл для трэка
//и ставим тип файла, например видео или аудио
//в мостпартнере сделано с этим
//https://github.com/dustin10/VichUploaderBundle
