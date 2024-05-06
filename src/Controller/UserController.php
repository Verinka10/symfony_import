<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Puser;
use App\Repository\PusersRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{

    #[Route('/', name: 'root')]
    public function default(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'NewController'
        ]);
    }

    #[Route('/import', name: 'app_user')]
    public function index(EntityManagerInterface $entityManager, PusersRepository $rep): Response
    {
        try {

            // catch count
            if ($rep->count() > 10000) {
                $rep->deleteAll();
                $entityManager->flush();
            }

            $data = json_decode(file_get_contents('https://randomuser.me/api/?results=500'));

            $data = array_map(function ($_) {
                return [
                    'first_name' => $_->name?->first,
                    'last_name' => $_->name?->last,
                    'email' => $_->email,
                    'age' => $_->dob?->age
                ];
            }, $data->results);

            $importIds = array_map(function ($_) {
                return preg_replace("/([`\"\'])/", '', $_['first_name'] . $_['last_name']);
            }, $data);

            $existUsers = [];
            foreach ($rep->findflowIds($importIds) as $user) {
                if (!$user) continue;
                $existUsers[$user->getFirstName() . $user->getLastName()] = $user;
            }

            $batchSize = 100;
            $count = 0;
            $updated = 0;
            $inserted = 0;

            foreach ($data as $item) {

                $id = $item['first_name'] . $item['last_name'];
                $user = null;
                if (isset($existUsers[$id])) {
                    $user = $existUsers[$id];
                    $updated ++;
                } else {
                    $user = new Puser();
                    $user->setFirstName($item['first_name']);
                    $user->setLastName($item['last_name']);
                    $inserted ++;
                    $entityManager->persist($user);
                    $existUsers[$id] = $user;
                }
                $user->setEmail($item['email']);
                $user->setAge($item['age']);

                if ((++ $count % $batchSize) == 0) {
                    $entityManager->flush();
                    // $entityManager->clear();
                }
            }
            $entityManager->flush();

        } catch (\Exception $e) {
            throw $e;
            return $this->json([
                'result' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }

        return $this->json([
            'result' => 'ok',
            'updated' => $updated,
            'inserted' => $inserted,
            'all' => $rep->count()
        ]);
    }
}
