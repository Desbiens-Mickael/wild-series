<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use App\Service\Slugify;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
         ]);
    }

    #[Route('/new', name: 'new')]
    public function new(
        Request $request, 
        ProgramRepository $ProgramRepository,
        SluggerInterface $slugger,
        Slugify $slugify,
        MailerInterface $mailer
        ): Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);

        // Vérifie si le formulaire a été soumis.
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $posterLgFile = $form->get('posterLgFile')->getData();
            $posterFile = $form->get('posterFile')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($posterLgFile) {
                $originalFilename = pathinfo($posterLgFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$posterLgFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $posterLgFile->move(
                        $this->getParameter('images_lg_dir'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $program->setPosterLg($newFilename);
            }
            if ($posterFile) {
                $originalFilename = pathinfo($posterFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$posterFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $posterFile->move(
                        $this->getParameter('images_dir'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $program->setPoster($newFilename);
            }
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);

            $program->setOwner($this->getUser());
            $ProgramRepository->add($program, true);     

            $email = (new Email())
                ->from('wild.serie@wildcodeschool.fr')
                ->to($this->getParameter('mailer_from'))
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('Program/newProgramEmail.html.twig', [
                    'program' => $program
                ]));

            $mailer->send($email);

            // Redirect to categories list
            return $this->redirectToRoute('program_index');
        }

        // Render the form (best practice)
        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/{program_slug<[^0-9]+>}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[ParamConverter('program', options: ['mapping' =>['program_slug' => 'slug']])]
    public function edit(Request $request, Program $program, ProgramRepository $programRepository): Response
    {
        // Check wether the logged in user is the owner of the program
        if (!($this->getUser() == $program->getOwner()) && !($this->isGranted('ROLE_ADMIN'))) {
            // If not the owner, throws a 403 Access Denied exception
            throw new AccessDeniedException("Vous n'avez pas les droits pour modifier cette série!");
        }

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $programRepository->add($program, true);

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{slug<[^0-9]+>}/', methods: ['GET'], name: 'show')]
    public function show(Program $program): Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    #[Route('/{program_slug<[^0-9]+>}/season/{season_id<\d+>}', methods: ['GET'], name: 'season_show')]
    #[ParamConverter('program', options: ['mapping' =>['program_slug' => 'slug']])]
    #[Entity('season', options: ['id' => 'season_id'])]
    public function showSeason(Program $program, Season $season): Response
    {
        return $this->render('program/season_show.html.twig',[
            'program' => $program,
            'season' => $season
        ]);
    }

    #[Route('/{program_slug<[^0-9]+>}/season/{season_id<\d+>}/episode/{episode_slug<[^0-9]+>}',
            methods: ['GET'], name: 'episode_show')]
    #[ParamConverter('program', options: ['mapping' =>['program_slug' => 'slug']])]
    #[Entity('season', options: ['id' => 'season_id'])]
    #[ParamConverter('episode', options: ['mapping' =>['episode_slug' => 'slug']])]
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode'=> $episode,
        ]);
    }


    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Program $program, ProgramRepository $programRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $programRepository->remove($program, true);
        }

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }
}