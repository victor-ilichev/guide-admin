<?php

declare(strict_types=1);

namespace App\Presenter;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Form\FormError;

abstract class Presenter
{
    /**
     * @var string
     */
    protected static $wrap;

    /**
     * @var object
     */
    protected $resource;

    /**
     * @param FormError[] $errors
     */
    public function presentFormErrors(iterable $errors): array
    {
        $output = [];

        foreach ($errors as $error) {
            $output[] = [
                'field' => $error->getOrigin()->getName(),
                'message' => $error->getMessage(),
            ];
        }

        return [
            'status' => 'error',
            'errors' => $output,
        ];
    }

    public function present(object $resource): array
    {
        $this->resource = $resource;

        return $this->toArray();
    }

    public function presentIterable(iterable $rows): array
    {
        $output = [];

        foreach ($rows as $row) {
            $this->resource = $row;

            $output[] = $this->toArray();
        }

        return $this->wrapDataIfNecessary($output);
    }

    public function presentPagination(PaginationInterface $pagination): array
    {
        $output = $this->presentIterable($pagination->getItems());

        return array_merge(
            $output,
            [
                'pagination' => [
                    'current_page' => $pagination->getCurrentPageNumber(),
                    'item_number_per_page' => $pagination->getItemNumberPerPage(),
                    'total_items_count' => $pagination->getTotalItemCount(),
                ]
            ]
        );
    }

    public abstract function toArray(): array;

    private function wrapDataIfNecessary(array $output): array
    {
        return
            (static::$wrap === null)
                ? $output
                : [static::$wrap => $output];
    }
}
