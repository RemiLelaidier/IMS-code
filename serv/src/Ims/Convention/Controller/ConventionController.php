<?php

namespace App\Ims\Convention\Controller;

use App\Core\Controller\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class ConventionController extends Controller
{

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function submit(Request $request, Response $response)
    {
        $conventionData = $request->getBody()->getContents();

        $decoded = json_decode($conventionData, true);

        $this->logger->debug('New convention on ' . get_class($this) . ":submit", [
            'data' => $conventionData
        ]);

        return $this->ok($response, [
            'convention_data' => $decoded
        ]);
    }
}
