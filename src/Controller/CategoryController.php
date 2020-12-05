<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Program;


/**
* @Route("/categories", name="category_")
*/
class CategoryController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render(
            'category/index.html.twig', 
            ['website' => 'Wild Séries',
            'categories' => $categories ]
        );
    }

    /**
     * Getting a program by name
     *
     * @Route("/{categoryName}", name="show")
     * @return Response
     */
    public function show(string $categoryName):Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        if (!$category) {
            throw $this->createNotFoundException(
                'No program with id : '.$categoryName.' found in program\'s table.'
            );
        }else{

            $programs = $this->getDoctrine()
            ->getRepository(program::class)
            ->findBy( ['category' => $category], ['id' => 'DESC'], 3, 0);
            return $this->render('category/show.html.twig', [
                'category' => $category,
                'programs' => $programs,
                ]);
        }
    }    
}
