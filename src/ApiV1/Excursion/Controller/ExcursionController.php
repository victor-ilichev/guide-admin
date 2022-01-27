<?php

declare(strict_types=1);

namespace App\ApiV1\Excursion\Controller;

use App\Entity\Excursion;
use App\Presenter\Presenter;
use App\Repository\ExcursionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/excursion", name="excursion.")
 */
class ExcursionController extends AbstractController
{
    /**
     * @var Presenter
     */
    private $presenter;

    public function __construct(Presenter $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * @Route(name="index", methods={"GET"})
     */
    public function index(Request $request, ExcursionRepository $repository): Response
    {
        return
            new JsonResponse(
                $this->presenter->presentIterable(
                    $repository->findAllWithTrack()
                )
            );
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Excursion $excursion, ExcursionRepository $repository): Response
    {
        /** @var Excursion[] $res */
//        $res = $repository->findWithTracks($id);
        $output = [];

//        foreach ($res as $row) {
//            foreach ($row->getSorts() as $exChSort) {
//                $output['tracks'][] = $exChSort->getChapter()->getPlayListSorts();
//            }
//        }

        foreach ($excursion->getSorts() as $eSort) {
            foreach ($eSort->getChapter()->getPlayListSorts() as $pSort) {
                foreach ($pSort->getPlayList()->getTrackSorts() as $tSort) {
                    $output[] = [
                        'title' => $tSort->getTrack()->getTitle(),
                    ];
                }
            }
        }

        return new JsonResponse($output);

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
