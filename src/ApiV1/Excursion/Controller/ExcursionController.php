<?php

declare(strict_types=1);

namespace App\ApiV1\Excursion\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/excursion", name="excursion.")
 */
class ExcursionController extends AbstractController
{
    /**
     * @Route(name="index", methods={"GET"})
     */
    public function index(): Response
    {
        return new JsonResponse([
            'data' => [
                [
                    'id' => 1,
                    'title' => 'qwerty 1',
                ],
                [
                    'id' => 2,
                    'title' => 'qwerty 2',
                ],
                [
                    'id' => 3,
                    'title' => 'qwerty 3',
                ],
            ],
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        return new JsonResponse([
            'data' => [
                'id' => 1,
                'title' => 'qwerty 1',
                'tracks' => [
                    'data' => [
                        [
                            'id' => 1,
                            'title' => 'track 01',
                            'name' => 'injected-01.mp4',
                            'type' => 'video',
                            'sort' => 2,
                            'length' => '3.08',
                        ],
                        [
                            'id' => 2,
                            'title' => 'track 02',
                            'name' => 'injected-02.mp4',
                            'type' => 'video',
                            'sort' => 4,
                            'length' => '4.02',
                        ],
                        [
                            'id' => 3,
                            'title' => 'track 03',
                            'name' => 'injected-03.mp4',
                            'type' => 'video',
                            'sort' => 1,
                            'length' => '1.23',
                        ],
                        [
                            'id' => 4,
                            'title' => 'track 04',
                            'name' => 'injected-04.mp4',
                            'type' => 'audio',
                            'sort' => 3,
                            'length' => '3.57',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
