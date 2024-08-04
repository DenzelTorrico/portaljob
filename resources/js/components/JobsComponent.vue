<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Publicar un trabajo</h2>
                <button class="btn btn-info" @click="viewAddJob()">Añadir job</button>
                  <!-- Modal de Postulantes -->
        <div v-if="showModalJob" class="modal fade show" tabindex="-1" style="display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Añade un nuevo trabajo</h5>
                <button type="button" class="btn-close" @click="showModalJob = false"></button>
            </div>
            <form @submit.prevent="addJob">
            <div class="modal-body">
                <!-- Formulario para añadir trabajo -->
                    <div class="form-group">
                        <label for="title">Título</label>
                        <input type="text" v-model="newJob.title" class="form-control" id="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Descripción</label>
                        <textarea v-model="newJob.description" class="form-control" id="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="salary">Salario</label>
                        <input type="number" v-model="newJob.salary" class="form-control" id="salary" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Añadir Trabajo</button>
                <button type="button" class="btn btn-secondary" @click="showModalJob = false">Cerrar</button>
            </div>
        </form>

        </div>
    </div>
</div>
               

                <!-- Lista de trabajos -->
                <ul class="list-group mt-3">
                    <li class="list-group-item" v-for="job in jobs.data" :key="job.id">
                        <h5 class="text-primary">{{ job.title }}</h5>
                        <p>{{ job.description }}</p>
                        <p><strong>Salario:</strong> {{ job.salary }}</p>
                        <button class="btn btn-info" @click="viewApplications(job.id)">Ver Postulaciones</button>
                        <button class="btn btn-danger" @click="deleteJob(job.id)">Eliminar Trabajo</button>
                    </li>
                </ul>

                <!-- Paginación -->
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

        <!-- Modal de Postulantes -->
        <div v-if="showModal" class="modal fade show" tabindex="-1" style="display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Postulaciones para {{ selectedJob.title }} ({{ totalApplications }} postulados)</h5>
                <button type="button" class="btn-close" @click="showModal = false"></button>
            </div>
            <div class="modal-body">
                <div v-if="applications.length === 0">
                    <p>No hay postulaciones para este trabajo.</p>
                </div>
                <ul v-else class="list-group">
                    <li class="list-group-item" v-for="application in applications" :key="application.id">
                        <p><strong>Usuario:</strong> {{ application.user.name }}</p>
                        <p><strong>Mensaje:</strong> {{ application.message }}</p>
                        <p><strong>CV:</strong>
                            <a :href="getCvUrl(application.cv_path)" target="_blank">Ver CV</a>
                        </p>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="showModal = false">Cerrar</button>
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
            totalApplications: 0,
            jobs: {
                data: [],
                current_page: 1,
                last_page: 1,
                prev_page_url: null,
                next_page_url: null,
            },
            newJob: {
                title: '',
                description: '',
                salary: ''
            },
            selectedJob: {},
            applications: [],
            showModal: false,
            showModalJob:false,
        };
    },
    mounted() {
        this.fetchJobs();
    },
    methods: {
        fetchJobs(url = '/admin/jobs') {
            axios.get(url)
                .then(response => {
                    this.jobs = response.data;
                })
                .catch(error => {
                    console.error('Error fetching jobs:', error);
                });
        },
        addJob() {
            axios.post('/admin/jobs', this.newJob)
                .then(response => {
                    this.newJob = { title: '', description: '', salary: '' }; // Limpiar formulario
                    this.fetchJobs(); // Refrescar lista de trabajos
                    this.showModalJob = false;
                })
                .catch(error => {
                    console.error('Error adding job:', error);
                });
        },
        viewAddJob(){
            this.showModalJob = true;
        },
        deleteJob(jobId) {
            axios.delete(`/admin/jobs/${jobId}`)
                .then(response => {
                    this.fetchJobs(); // Refrescar lista de trabajos
                })
                .catch(error => {
                    console.error('Error deleting job:', error);
                });
        },
        viewApplications(jobId) {
            axios.get(`/admin/jobs/${jobId}`)
                .then(response => {
                    this.selectedJob = response.data;
                    this.applications = response.data.applications;
                    this.totalApplications = this.applications.length; // Actualiza el conteo
                    this.showModal = true;
                })
                .catch(error => {
                    console.error('Error fetching applications:', error);
                });
        },
        getPageUrl(page) {
            return `/admin/jobs?page=${page}`;
        },
        getCvUrl(cvPath) {
            return `/storage/${cvPath}`;
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

<style>
/* Agrega estilos personalizados si es necesario */
.modal.show {
    display: block;
    opacity: 1;
}
.modal.fade {
    transition: opacity 0.15s linear;
}
.modal.fade.show {
    opacity: 1;
}
</style>
