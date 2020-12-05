<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
// Form 
use App\Form\ProgramType;
use Symfony\Component\HttpFoundation\Request;



/**
* @Route("/programs", name="program_")
*/
class ProgramController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(program::class)
            ->findAll();

        return $this->render('program/index.html.twig', 
                [
                'website' => 'Wild Séries',
                'programs' => $programs 
                ]);
    }

       /**
     * The controller for the category add form
     * Display the form or deal with it
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request) : Response
    {
        // Create a new Program Object
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
        // Get data from HTTP request
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Category Object
            $entityManager->persist($program);
            // Flush the persisted object
            $entityManager->flush();
            // Finally redirect to categories list
            return $this->redirectToRoute('program_index');
        }
        // Render the form
        return $this->render('program/new.html.twig', ["form" => $form->createView()]);
    }

    /**
    * Getting program by id
    *
    * @Route("/show/{program<^[0-9]+$>}", name="show")
    */
    public function show(Program $program): Response
    {
        $seasons = $program->getSeason();

    if (!$program) {
        throw $this->createNotFoundException(
            'No program with id : '.$program.' found in program\'s table.'
        );
    }
    return $this->render('program/show.html.twig', 
        [
            'program'=> $program, 
            'seasons' => $seasons
        ]);
    }


    /**
     * Getting a season by program id and season id
     *
     * @Route("/{program<^[0-9]+$>}/seasons/{season<^[0-9]+$>}", name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season": "id"}})
     * @return Response
     */
    public function showSeason(Program $program, Season $season)
    {
        $episodes = $season->getEpisodes();
        
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
            'website' => 'Wild Séries'
        ]);    
    }

    /**
     * Getting an Episode by program id, season id, and episode id
     *
     * @Route("/{program<^[0-9]+$>}/seasons/{season<^[0-9]+$>}/episode/{episode<^[0-9]+$>}", name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season": "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episode": "id"}})
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode)
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'website' => 'Wild Séries'
        ]);  
    }
}
