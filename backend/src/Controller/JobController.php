<?php

namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JobController extends AbstractController
{
    public function __construct(
        private JobRepository $jobRepository,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route('/job/{id}', name: 'app_job_detail', requirements: ['id' => '\d+'])]
    public function jobDetail(int $id): Response
    {
        $job = $this->jobRepository->find($id);

        if (!$job || $job->getStatus() !== 'approved') {
            throw $this->createNotFoundException('Job not found or not available');
        }

        return $this->render('job_detail.html.twig', ['job' => $job]);
    }

    #[Route('/post-job', name: 'app_job_post')]
    public function postJobForm(): Response
    {
        return $this->render('job_post.html.twig');
    }

    #[Route('/api/jobs', name: 'api_jobs_list', methods: ['GET'])]
    public function listJobs(Request $request): JsonResponse
    {
        $searchQuery = $request->query->get('q') ?? $request->query->get('search');
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = min(50, max(1, (int) $request->query->get('limit', 12)));

        // Get filter parameters
        $location = $request->query->get('location');
        $field = $request->query->get('field');
        $employmentType = $request->query->get('employmentType');
        $sort = $request->query->get('sort', 'newest');

        // Build query parameters
        $queryParams = [
            'search' => $searchQuery,
            'location' => $location,
            'field' => $field,
            'employmentType' => $employmentType,
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
        ];

        $jobs = $this->jobRepository->findApprovedJobs($searchQuery);

        // Apply filters
        if ($location) {
            $jobs = array_filter($jobs, fn($job) => stripos($job->getLocation(), $location) !== false);
        }
        if ($field) {
            $jobs = array_filter($jobs, fn($job) => stripos($job->getField(), $field) !== false);
        }
        if ($employmentType) {
            $jobs = array_filter($jobs, fn($job) => stripos($job->getEmploymentType(), $employmentType) !== false);
        }

        // Sort jobs
        usort($jobs, function ($a, $b) use ($sort) {
            switch ($sort) {
                case 'oldest':
                    return $a->getCreatedAt() <=> $b->getCreatedAt();
                case 'company':
                    return strcasecmp($a->getCompany(), $b->getCompany());
                case 'title':
                    return strcasecmp($a->getTitle(), $b->getTitle());
                default: // 'newest'
                    return $b->getCreatedAt() <=> $a->getCreatedAt();
            }
        });

        // Calculate pagination
        $total = count($jobs);
        $pages = (int) ceil($total / $limit);
        $offset = ($page - 1) * $limit;
        $jobs = array_slice($jobs, $offset, $limit);

        $jobsData = array_map(function (Job $job) {
            return [
                'id' => $job->getId(),
                'title' => $job->getTitle(),
                'company' => $job->getCompany(),
                'location' => $job->getLocation(),
                'salary' => $job->getSalary(),
                'employmentType' => $job->getEmploymentType() ?? 'full-time',
                'field' => $job->getField(),
                'description' => $job->getDescription() ?? '',
                'createdAt' => $job->getCreatedAt()->format('Y-m-d H:i:s'),
                'startDate' => $job->getStartDate()?->format('Y-m-d'),
            ];
        }, $jobs);

        return $this->json([
            'jobs' => $jobsData,
            'pagination' => [
                'page' => $page,
                'total' => $total,
                'pages' => $pages,
            ]
        ]);
    }

    #[Route('/api/jobs/{id}', name: 'api_job_detail', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getJob(int $id): JsonResponse
    {
        $job = $this->jobRepository->find($id);

        if (!$job || $job->getStatus() !== 'approved') {
            return $this->json(['error' => 'Job not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id' => $job->getId(),
            'title' => $job->getTitle(),
            'company' => $job->getCompany(),
            'location' => $job->getLocation(),
            'salary' => $job->getSalary(),
            'startDate' => $job->getStartDate()?->format('Y-m-d'),
            'employmentType' => $job->getEmploymentType(),
            'field' => $job->getField(),
            'email' => $job->getEmail(),
            'createdAt' => $job->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('/api/jobs', name: 'api_jobs_create', methods: ['POST'])]
    public function createJob(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $data = json_decode($request->getContent(), true);

        // Basic validation
        $required = ['title', 'company', 'location', 'employmentType', 'field', 'email'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return $this->json(['error' => "Field '$field' is required"], Response::HTTP_BAD_REQUEST);
            }
        }

        if (!str_contains($data['email'], '@')) {
            return $this->json(['error' => 'Invalid email format'], Response::HTTP_BAD_REQUEST);
        }

        $job = new Job();
        $job->setTitle($data['title']);
        $job->setCompany($data['company']);
        $job->setLocation($data['location']);
        $job->setSalary($data['salary'] ?? null);
        $job->setEmploymentType($data['employmentType']);
        $job->setField($data['field']);
        $job->setEmail($data['email']);
        $job->setDescription($data['description'] ?? 'Job description not provided');
        $job->setRequirements($data['requirements'] ?? null);
        $job->setBenefits($data['benefits'] ?? null);
        $job->setStatus('pending');

        if (!empty($data['startDate'])) {
            try {
                $job->setStartDate(new \DateTime($data['startDate']));
            } catch (\Exception $e) {
                return $this->json(['error' => 'Invalid date format'], Response::HTTP_BAD_REQUEST);
            }
        }

        $this->entityManager->persist($job);
        $this->entityManager->flush();

        return $this->json([
            'id' => $job->getId(),
            'status' => 'pending',
            'message' => 'Job submitted for approval'
        ], Response::HTTP_CREATED);
    }
}
