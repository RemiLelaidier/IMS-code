<?php
    
namespace App\Ims\Company\Controller;
    
use App\Core\Controller\Controller;
use Slim\Http\Request;
use Slim\Http\Response;
    
class CompanyController  extends Controller {
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function dummy(Request $request, Response $response)
    {
        // Get raw data from stream
        $data = $request->getBody()->getContents();
        // Decode JSON from raw data
        $decoded = json_decode($data, true);
        // Log 
        $this->logger->debug('New data on ' . get_class($this) . ":submit", [
            'data' => $data
        ]);
        // Return response
        return $this->ok($response, [
            'data' => $decoded
        ]);
    }
}