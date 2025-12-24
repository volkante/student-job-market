<?php

namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    public function __construct(
        private JobRepository $jobRepository,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'pending_jobs_count' => $this->jobRepository->count(['status' => 'pending']),
            'approved_jobs_count' => $this->jobRepository->count(['status' => 'approved']),
            'total_users_count' => $this->userRepository->count([]),
        ]);
    }

    #[Route('/admin/jobs', name: 'app_admin_jobs')]
    #[IsGranted('ROLE_ADMIN')]
    public function jobs(): Response
    {
        return $this->render('admin/jobs.html.twig');
    }

    #[Route('/api/admin/jobs', name: 'api_admin_jobs', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function apiJobs(Request $request): JsonResponse
    {
        $status = $request->query->get('status', 'all');
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = min(100, max(10, (int) $request->query->get('limit', 20)));
        $offset = ($page - 1) * $limit;

        $criteria = [];
        if ($status !== 'all') {
            $criteria['status'] = $status;
        }

        $jobs = $this->jobRepository->findBy(
            $criteria,
            ['createdAt' => 'DESC'],
            $limit,
            $offset
        );

        $total = $this->jobRepository->count($criteria);

        $jobData = array_map(function (Job $job) {
            return [
                'id' => $job->getId(),
                'title' => $job->getTitle(),
                'company' => $job->getCompany(),
                'location' => $job->getLocation(),
                'employmentType' => $job->getEmploymentType(),
                'field' => $job->getField(),
                'status' => $job->getStatus(),
                'createdAt' => $job->getCreatedAt()->format('Y-m-d H:i:s'),
                'startDate' => $job->getStartDate()?->format('Y-m-d'),
            ];
        }, $jobs);

        return $this->json([
            'jobs' => $jobData,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit),
            ],
        ]);
    }

    #[Route('/api/admin/jobs/{id}/approve', name: 'api_admin_job_approve', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function approveJob(int $id): JsonResponse
    {
        $job = $this->jobRepository->find($id);

        if (!$job) {
            return $this->json(['error' => 'Job not found'], Response::HTTP_NOT_FOUND);
        }

        if ($job->getStatus() === 'approved') {
            return $this->json(['message' => 'Job already approved'], Response::HTTP_OK);
        }

        $job->setStatus('approved');
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Job approved successfully',
            'job' => [
                'id' => $job->getId(),
                'title' => $job->getTitle(),
                'status' => $job->getStatus(),
            ]
        ]);
    }

    #[Route('/api/admin/jobs/{id}/reject', name: 'api_admin_job_reject', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function rejectJob(int $id): JsonResponse
    {
        $job = $this->jobRepository->find($id);

        if (!$job) {
            return $this->json(['error' => 'Job not found'], Response::HTTP_NOT_FOUND);
        }

        if ($job->getStatus() === 'rejected') {
            return $this->json(['message' => 'Job already rejected'], Response::HTTP_OK);
        }

        $job->setStatus('rejected');
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Job rejected successfully',
            'job' => [
                'id' => $job->getId(),
                'title' => $job->getTitle(),
                'status' => $job->getStatus(),
            ]
        ]);
    }

    #[Route('/api/admin/jobs/{id}', name: 'api_admin_job_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteJob(int $id): JsonResponse
    {
        $job = $this->jobRepository->find($id);

        if (!$job) {
            return $this->json(['error' => 'Job not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($job);
        $this->entityManager->flush();

        return $this->json(['message' => 'Job deleted successfully']);
    }

    #[Route('/api/admin/stats', name: 'api_admin_stats', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function stats(): JsonResponse
    {
        $stats = [
            'jobs' => [
                'pending' => $this->jobRepository->count(['status' => 'pending']),
                'approved' => $this->jobRepository->count(['status' => 'approved']),
                'rejected' => $this->jobRepository->count(['status' => 'rejected']),
                'total' => $this->jobRepository->count([]),
            ],
            'users' => [
                'total' => $this->userRepository->count([]),
                'admins' => count($this->userRepository->findUsersByRole('ROLE_ADMIN')),
            ],
        ];

        return $this->json($stats);
    }
}
