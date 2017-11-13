<?php

namespace App\Ims\Convention\Controller;

use App\Core\Controller\Controller;
use App\Ims\Convention\Model\Convention;
use App\Ims\Student\Model\Student;
use App\Ims\Company\Model\Company;
use App\Ims\Internship\Model\Internship;
use Slim\Http\Request;
use Slim\Http\Response;

class ConventionController extends Controller
{

    /**
     * Method submit
     * Put all the datas received from the four stages of filling the convention in the database
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function submit(Request $request, Response $response){
        // Decode datas
        $conventionData = $request->getBody()->getContents();
        $decoded = json_decode($conventionData, true);
        // Log activity
        $this->logger->debug('New convention on ' . get_class($this) . ":submit", [
            'data' => $conventionData
        ]);

        return $this->ok($response, [
            'convention_data' => $decoded
        ]);
    }
}
