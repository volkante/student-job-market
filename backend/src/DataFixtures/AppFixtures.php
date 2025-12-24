<?php

namespace App\DataFixtures;

use App\Entity\Job;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        // Create admin user
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // Create regular user
        $user = new User();
        $user->setUsername('company1');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
        $manager->persist($user);

        // Create 3 sample approved jobs
        $jobs = [
            [
                'title' => 'Junior Web Developer',
                'company' => 'TechCorp A.Ş.',
                'location' => 'İstanbul',
                'salary' => '8.000 - 12.000 TL',
                'startDate' => new \DateTime('+2 weeks'),
                'employmentType' => 'full-time',
                'field' => 'Yazılım Geliştirme',
                'email' => 'hr@techcorp.com',
                'description' => 'We are looking for a motivated Junior Web Developer to join our dynamic team. You will work on exciting projects using modern technologies like React, Node.js, and cloud platforms.',
                'requirements' => 'Basic knowledge of HTML, CSS, JavaScript. Familiarity with React or Vue.js is a plus. Strong problem-solving skills and eagerness to learn.',
                'benefits' => 'Competitive salary, health insurance, flexible working hours, learning budget, modern office environment.',
                'status' => 'approved'
            ],
            [
                'title' => 'Frontend Intern',
                'company' => 'StartupLab',
                'location' => 'Ankara',
                'salary' => '4.000 - 6.000 TL',
                'startDate' => new \DateTime('+1 month'),
                'employmentType' => 'part-time',
                'field' => 'Web Tasarım',
                'email' => 'jobs@startuplab.co',
                'description' => 'Join our innovative startup as a Frontend Intern! You will work closely with senior developers to create beautiful and responsive user interfaces using modern frontend technologies.',
                'requirements' => 'Currently studying Computer Science or related field. Basic knowledge of HTML, CSS, and JavaScript. Interest in modern frontend frameworks.',
                'benefits' => 'Flexible part-time schedule, mentorship program, potential for full-time offer, startup environment.',
                'status' => 'approved'
            ],
            [
                'title' => 'Data Analyst Student Position',
                'company' => 'DataViz Ltd.',
                'location' => 'İzmir',
                'salary' => '6.000 - 8.000 TL',
                'startDate' => new \DateTime('+3 weeks'),
                'employmentType' => 'full-time',
                'field' => 'Veri Analizi',
                'email' => 'careers@dataviz.com',
                'description' => 'Excellent opportunity for a student to gain hands-on experience in data analysis and visualization. Work with real client data to create insightful reports and dashboards.',
                'requirements' => 'Statistics or Mathematics background preferred. Experience with Excel, SQL basics. Python or R knowledge is a plus.',
                'benefits' => 'Professional development, training in advanced analytics tools, collaborative team environment.',
                'status' => 'approved'
            ]
        ];

        foreach ($jobs as $jobData) {
            $job = new Job();
            $job->setTitle($jobData['title']);
            $job->setCompany($jobData['company']);
            $job->setLocation($jobData['location']);
            $job->setSalary($jobData['salary']);
            $job->setStartDate($jobData['startDate']);
            $job->setEmploymentType($jobData['employmentType']);
            $job->setField($jobData['field']);
            $job->setEmail($jobData['email']);
            $job->setDescription($jobData['description']);
            $job->setRequirements($jobData['requirements'] ?? null);
            $job->setBenefits($jobData['benefits'] ?? null);
            $job->setStatus($jobData['status']);
            $manager->persist($job);
        }

        // Create 1 pending job for admin testing
        $pendingJob = new Job();
        $pendingJob->setTitle('Backend Developer Internship');
        $pendingJob->setCompany('CloudTech Solutions');
        $pendingJob->setLocation('Bursa');
        $pendingJob->setSalary('5.000 - 7.000 TL');
        $pendingJob->setStartDate(new \DateTime('+1 month'));
        $pendingJob->setEmploymentType('full-time');
        $pendingJob->setField('Backend Development');
        $pendingJob->setEmail('hr@cloudtech.com');
        $pendingJob->setDescription('Great opportunity for a backend developer intern to work with our cloud infrastructure team. Learn modern backend technologies including APIs, databases, and cloud deployment.');
        $pendingJob->setRequirements('Basic understanding of programming concepts. Familiarity with any backend language (Java, Python, PHP, etc.). Database knowledge is helpful.');
        $pendingJob->setBenefits('Internship stipend, mentorship program, potential conversion to full-time, cloud technology exposure.');
        $pendingJob->setStatus('pending');
        $manager->persist($pendingJob);

        $manager->flush();
    }
}
