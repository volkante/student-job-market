import { api } from './main';

interface Job {
    id: number;
    title: string;
    company: string;
    location: string;
    salary?: string;
    employmentType: string;
    field: string;
    description: string;
    createdAt: string;
    startDate?: string;
}

interface JobsResponse {
    jobs: Job[];
    pagination: {
        page: number;
        total: number;
        pages: number;
    };
}

let currentPage = 1;
let currentFilters: Record<string, string> = {};

export function initJobBrowser() {
    loadJobs();
    setupEventListeners();
}

function setupEventListeners() {
    // Search button
    document.getElementById('searchButton')?.addEventListener('click', handleSearch);
    
    // Clear filters button  
    document.getElementById('clearFilters')?.addEventListener('click', handleClearFilters);
    
    // Sort radio buttons
    document.querySelectorAll('input[name="sortBy"]').forEach(radio => {
        radio.addEventListener('change', handleSearch);
    });
    
    // Enter key on search input
    document.getElementById('searchQuery')?.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            handleSearch();
        }
    });
}

function handleSearch() {
    currentPage = 1;
    applyFilters();
    loadJobs();
}

function handleClearFilters() {
    currentPage = 1;
    currentFilters = {};
    
    // Clear all filter inputs
    (document.getElementById('searchQuery') as HTMLInputElement).value = '';
    (document.getElementById('locationFilter') as HTMLSelectElement).value = '';
    (document.getElementById('fieldFilter') as HTMLSelectElement).value = '';
    (document.getElementById('employmentTypeFilter') as HTMLSelectElement).value = '';
    (document.getElementById('sort-newest') as HTMLInputElement).checked = true;
    
    loadJobs();
}

function applyFilters() {
    currentFilters = {};
    
    const searchQuery = (document.getElementById('searchQuery') as HTMLInputElement).value.trim();
    const location = (document.getElementById('locationFilter') as HTMLSelectElement).value;
    const field = (document.getElementById('fieldFilter') as HTMLSelectElement).value;
    const employmentType = (document.getElementById('employmentTypeFilter') as HTMLSelectElement).value;
    const sortBy = (document.querySelector('input[name="sortBy"]:checked') as HTMLInputElement)?.value || 'newest';
    
    if (searchQuery) currentFilters.search = searchQuery;
    if (location) currentFilters.location = location;
    if (field) currentFilters.field = field;
    if (employmentType) currentFilters.employmentType = employmentType;
    if (sortBy !== 'newest') currentFilters.sort = sortBy;
}

async function loadJobs() {
    showLoading();
    
    try {
        const params = new URLSearchParams({
            page: currentPage.toString(),
            limit: '12',
            ...currentFilters
        });
        
        const data: JobsResponse = await api.get(`/jobs?${params}`);
        
        hideLoading();
        
        if (data.jobs.length > 0) {
            renderJobs(data.jobs);
            renderPagination(data.pagination);
            updateJobCount(data.pagination.total);
        } else {
            showEmptyState();
        }
        
    } catch (error) {
        console.error('Error loading jobs:', error);
        hideLoading();
        showError('Failed to load jobs. Please try again.');
    }
}

function showLoading() {
    document.getElementById('loadingSpinner')?.classList.remove('d-none');
    document.getElementById('jobsContainer')?.classList.add('d-none');
    document.getElementById('emptyState')?.classList.add('d-none');
    document.getElementById('paginationContainer')?.classList.add('d-none');
}

function hideLoading() {
    document.getElementById('loadingSpinner')?.classList.add('d-none');
}

function renderJobs(jobs: Job[]) {
    const container = document.getElementById('jobsContainer');
    const template = document.getElementById('jobCardTemplate') as HTMLTemplateElement;
    
    if (!container || !template) return;
    
    container.innerHTML = '';
    
    jobs.forEach(job => {
        const clone = template.content.cloneNode(true) as DocumentFragment;
        
        // Fill in job data
        const card = clone.querySelector('.job-card') as HTMLElement;
        card.dataset.jobId = job.id.toString();
        
        clone.querySelector('.job-title')!.textContent = job.title;
        clone.querySelector('.job-company')!.textContent = job.company;
        clone.querySelector('.job-description')!.textContent = job.description.length > 150 
            ? job.description.substring(0, 150) + '...' 
            : job.description;
        clone.querySelector('.job-type')!.textContent = job.employmentType.charAt(0).toUpperCase() + job.employmentType.slice(1);
        clone.querySelector('.job-field')!.textContent = job.field;
        clone.querySelector('.job-location')!.textContent = job.location;
        
        if (job.salary) {
            clone.querySelector('.job-salary')!.textContent = job.salary;
        } else {
            clone.querySelector('.job-salary')!.textContent = 'Salary not specified';
        }
        
        const createdDate = new Date(job.createdAt);
        clone.querySelector('.job-date')!.textContent = `Posted ${formatDate(createdDate)}`;
        
        const link = clone.querySelector('.job-link') as HTMLAnchorElement;
        link.href = `/job/${job.id}`;
        
        container.appendChild(clone);
    });
    
    container.classList.remove('d-none');
}

function renderPagination(pagination: { page: number; total: number; pages: number }) {
    const container = document.getElementById('paginationContainer');
    const paginationUl = document.getElementById('pagination');
    
    if (!container || !paginationUl || pagination.pages <= 1) {
        container?.classList.add('d-none');
        return;
    }
    
    let html = '';
    
    // Previous button
    if (pagination.page > 1) {
        html += `<li class="page-item">
            <a class="page-link" href="#" data-page="${pagination.page - 1}">Previous</a>
        </li>`;
    }
    
    // Page numbers
    const startPage = Math.max(1, pagination.page - 2);
    const endPage = Math.min(pagination.pages, pagination.page + 2);
    
    for (let i = startPage; i <= endPage; i++) {
        html += `<li class="page-item ${i === pagination.page ? 'active' : ''}">
            <a class="page-link" href="#" data-page="${i}">${i}</a>
        </li>`;
    }
    
    // Next button
    if (pagination.page < pagination.pages) {
        html += `<li class="page-item">
            <a class="page-link" href="#" data-page="${pagination.page + 1}">Next</a>
        </li>`;
    }
    
    paginationUl.innerHTML = html;
    
    // Add click event listeners to pagination links
    paginationUl.addEventListener('click', (e) => {
        e.preventDefault();
        const target = e.target as HTMLAnchorElement;
        if (target.hasAttribute('data-page')) {
            const page = parseInt(target.getAttribute('data-page')!);
            if (page !== currentPage) {
                currentPage = page;
                loadJobs();
                
                // Scroll to top of results
                document.querySelector('.col-lg-9')?.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
    
    container.classList.remove('d-none');
}

function showEmptyState() {
    document.getElementById('emptyState')?.classList.remove('d-none');
    updateJobCount(0);
}

function updateJobCount(count: number) {
    const countElement = document.getElementById('jobCount');
    if (countElement) {
        countElement.textContent = `${count} job${count !== 1 ? 's' : ''} found`;
    }
}

function showError(message: string) {
    // Show error state similar to empty state but with error message
    const emptyState = document.getElementById('emptyState');
    if (emptyState) {
        emptyState.innerHTML = `
            <i class="fas fa-exclamation-triangle fa-4x text-danger mb-3"></i>
            <h3>Error Loading Jobs</h3>
            <p class="text-muted">${message}</p>
            <button class="btn btn-primary" onclick="window.location.reload()">
                <i class="fas fa-refresh me-2"></i>
                Try Again
            </button>
        `;
        emptyState.classList.remove('d-none');
    }
    updateJobCount(0);
}

function formatDate(date: Date): string {
    const now = new Date();
    const diffMs = now.getTime() - date.getTime();
    const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
    
    if (diffDays === 0) {
        return 'today';
    } else if (diffDays === 1) {
        return '1 day ago';
    } else if (diffDays < 7) {
        return `${diffDays} days ago`;
    } else if (diffDays < 30) {
        const weeks = Math.floor(diffDays / 7);
        return `${weeks} week${weeks > 1 ? 's' : ''} ago`;
    } else {
        return date.toLocaleDateString();
    }
}