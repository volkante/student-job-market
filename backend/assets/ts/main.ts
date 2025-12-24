import 'bootstrap';
import '../styles/main.scss';

// Import functionality modules
import { initJobBrowser } from './job-browser';
import { initJobPoster } from './job-poster';

// Initialize jQuery for dynamic functionality
declare global {
    interface Window {
        $: typeof import('jquery');
        jQuery: typeof import('jquery');
        jobBrowser: { init: () => void };
        jobPoster: { init: () => void };
    }
}

// Simple API client functions
export const api = {
    async get(url: string) {
        const response = await fetch(`/api${url}`);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    },

    async post(url: string, data: any) {
        const response = await fetch(`/api${url}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    }
};

// Make modules available globally for template scripts
window.jobBrowser = { init: initJobBrowser };
window.jobPoster = { init: initJobPoster };

console.log('Student Job Market app initialized!');