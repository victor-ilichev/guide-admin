<?php

declare(strict_types=1);

namespace App\ApiV1\Excursion\Presenter;

use App\Entity\Excursion;
use App\Presenter\Presenter;

class ExcursionPresenter extends Presenter
{
    protected static $wrap = 'data';

    /**
     * @var Excursion
     */
    protected $resource;

    public function toArray(): array
    {
        return [
            'id' => $this->resource->getId(),
            'title' => $this->resource->getTitle(),
            'body' => $this->resource->getBody(),
            'image_url' => $this->resource->getImageUrl(),
            'created_at' => $this->resource->getCreatedAt(),
        ];
    }
}
