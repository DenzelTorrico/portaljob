<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="jobs.data.length === 0" class="col-md-8">
                <p>No jobs found.</p>
            </div>
            <div v-else class="col-md-8">
                <ul class="list-group mb-3">
                    <li class="list-group-item mt-2" v-for="job in jobs.data" :key="job.id">
                        <h5 class="text-primary">{{ job.title }}</h5>
                        <p>{{ job.description }}</p>
                        <p><strong>Salario:</strong> {{ job.salary }}</p>
                        <button 
                            @click="openApplyModal(job)" 
                            :disabled="job.has_applied"
                            class="btn btn-primary">
                            <span v-if="job.has_applied">Aplicado</span>
                            <span v-if="!job.has_applied">Aplicar al trabajo</span>
                        </button>
                    </li>
                </ul>
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item" :class="{ disabled: !jobs.prev_page_url }">
                            <a class="page-link" href="#" @click.prevent="fetchJobs(jobs.prev_page_url)">Anterior</a>
                        </li>
                        <li class="page-item" v-for="page in pages" :key="page" :class="{ active: page === jobs.current_page }">
                            <a class="page-link" href="#" @click.prevent="fetchJobs(getPageUrl(page))">{{ page }}</a>
                        </li>
                        <li class="page-item" :class="{ disabled: !jobs.next_page_url }">
                            <a class="page-link" href="#" @click.prevent="fetchJobs(jobs.next_page_url)">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <div class="modal fade" id="applyJobModal" tabindex="-1" aria-labelledby="applyJobModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="applyJobModalLabel">Applicando para {{ selectedJob.title }}</h5>
                        <button @click="closemodal" type="button" class="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitApplication">
                            <div class="form-group">
                                <label for="message">Mensaje</label>
                                <textarea v-model="application.message" class="form-control" id="message" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="cv">Cargar CV</label>
                                <input type="file" class="form-control" id="cv" @change="handleFileUpload">
                            </div>
                            <button type="submit" class="btn btn-primary">Aplicar al trabajo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            jobs: {
                data: [],
                current_page: 1,
                last_page: 1,
                prev_page_url: null,
                next_page_url: null,
            },
            selectedJob: {},
            application: {
                message: '',
                cv: null
            }
        };
    },
    mounted() {
        this.fetchJobs();
    },
    methods: {
        fetchJobs(url = '/postulante/jobs') {
            axios.get(url)
                .then(response => {
                    console.log('Jobs fetched:', response.data); // Log de depuración
                    this.jobs = response.data;
                })
                .catch(error => {
                    console.error('Error fetching jobs:', error);
                });
        },
        closemodal(){
            $('#applyJobModal').modal('hide');
        },
        getPageUrl(page) {
            return `/postulante/jobs?page=${page}`;
        },
        openApplyModal(job) {
            this.selectedJob = job;
            this.application = { message: '', cv: null };
            $('#applyJobModal').modal('show');
        },
        handleFileUpload(event) {
            this.application.cv = event.target.files[0];
        },
        submitApplication() {
            const formData = new FormData();
            formData.append('job_id', this.selectedJob.id);
            formData.append('message', this.application.message);
            formData.append('cv', this.application.cv);

            axios.post(`/postulante/jobs/${this.selectedJob.id}/apply`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                $('#applyJobModal').modal('hide');
                this.fetchJobs(); // Refetch jobs to update the list if needed
                alert('Application submitted successfully');
            })
            .catch(error => {
                console.error('Error submitting application:', error);
                alert('Failed to submit application');
            });
        }
    },
    computed: {
        pages() {
            let pagesArray = [];
            for (let i = 1; i <= this.jobs.last_page; i++) {
                pagesArray.push(i);
            }
            return pagesArray;
        }
    }
};
</script>
