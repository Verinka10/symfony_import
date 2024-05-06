<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

use App\Model\GetppRequest;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

class NewController extends AbstractController
{
    
    #[Route('/new2', name: 'app_new')]
    public function index(): Response
    {
        $this->addFlash('notice','Your changes were saved!');

        return $this->render('new/index.html.twig', [
            'controller_name' => 'NewController',
        ]);
    }
    
    
    //#[Route('/nikola/{id}', name: 'app_new2', methods: ['GET', 'HEAD'],  requirements: ['id' => '10|20'] )]
    #[Route('/nikola/{id<1|2>}', name: 'app_new2')]
    public function aaa(int $id): Response
    {
        // JSON response
        //return new JsonResponse(["a" => 111]);
        //return $this->json(['username' => 'jane.doe']);
        
        //or format: 'json' and return new Response('{"a":1}')
        
        // generate url
        ///dd($this->generateUrl('new_5', ['id' => 10]));
      
    }
    

    public function bbb(Request $request): Response
    {
        //dd($request->attributes->get('_route_params'));
        //dd($request->attributes->get('id'));
        //dd($request->attributes->all());
        // get param
        //$pp = $request->query->get('pp', 1);
        
        // is it an Ajax request?
        $request->isXmlHttpRequest();
        
        // retrieves GET and POST variables respectively
        $request->query->get('page');
        $request->getPayload()->get('page');
        
        // retrieves an instance of UploadedFile identified by foo
        $request->files->get('foo');
        
        //
        //$response = new Response('<style> ... </style>');
        //$response->headers->set('Content-Type', 'text/css');
        
        // dirs
        //$contentsDir = $this->getParameter('kernel.project_dir').'/contents';
        
        // send file
        //return $this->file(__FILE__);
        
        
        return new Response('1', Response::HTTP_OK);
    }
    
    
    #[Route('/ddd')]
    //#[Autowire(service: 'monolog.logger.request')]
    public function number(LoggerInterface $logger): Response
    {
        //$logger->info('We are logging!');
        //throw $this->createNotFoundException('The product does not exist');
        
        return new Response('1');
    }
    
    
    //http://127.0.0.1:8000/getpp?age=21&firstName=lll
    #[Route('/getpp')]
    public function getpp(
        // map get
        #[MapQueryString()] GetppRequest $getppRequest = new GetppRequest("aa", 20)
        // name as dto
        //#[MapRequestPayload()] EmployeesDTO $employeesDto
        
        // Mapping Request Payload
        // curl -H "Content-Type: application/json"   http://127.0.0.1:8000/getpp -d '{"firstName": "fff", "age": 22}
        //#[MapRequestPayload()] GetppRequest $getppRequest
     ): Response
    {
        
        print_r($getppRequest);exit;
        return new Response();
    }
}
