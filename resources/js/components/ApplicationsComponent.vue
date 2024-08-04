<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="applications.data.length === 0" class="col-md-8">
                <p>Sin aplicaciones encontradas.</p>
            </div>
            <div v-else class="col-md-8">
                <ul class="list-group mb-3">
                    <li class="list-group-item" v-for="application in applications.data" :key="application.id">
                        <h5 class="text-primary">Nombre del Trabajo: {{ application.job.title }}</h5>
                        <p>Descripción del Trabajo: {{ application.job.description }}</p>
                        <p>Salario del Trabajo: {{ application.job.salary }}</p>
                        <p>Mensaje: {{ application.message }}</p>
                        <p><strong>CV:</strong> <a :href="getCvUrl(application.cv_path)" target="_blank">Ver CV</a></p>
                    </li>
                </ul>
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item" :class="{ disabled: !applications.prev_page_url }">
                            <a class="page-link" href="#" @click.prevent="fetchApplications(applications.prev_page_url)">Anterior</a>
                        </li>
                        <li class="page-item" v-for="page in pages" :key="page" :class="{ active: page === applications.current_page }">
                            <a class="page-link" href="#" @click.prevent="fetchApplications(getPageUrl(page))">{{ page }}</a>
                        </li>
                        <li class="page-item" :class="{ disabled: !applications.next_page_url }">
                            <a class="page-link" href="#" @click.prevent="fetchApplications(applications.next_page_url)">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            applications: {
                data: [],
                current_page: 1,
                last_page: 1,
                prev_page_url: null,
                next_page_url: null,
            },
        };
    },
    mounted() {
        this.fetchApplications();
    },
    methods: {
        fetchApplications(url = '/postulante/applications') {
            axios.get(url)
                .then(response => {
                    this.applications = response.data;
                })
                .catch(error => {
                    console.error('Error fetching applications:', error);
                });
        },
        getPageUrl(page) {
            return `/postulante/applications?page=${page}`;
        },
        getCvUrl(cvPath) {
            return `/storage/${cvPath}`;
        }
    },
    computed: {
        pages() {
            let pagesArray = [];
            for (let i = 1; i <= this.applications.last_page; i++) {
                pagesArray.push(i);
            }
            return pagesArray;
        }
    }
};
</script>

<style>
/* Agrega estilos personalizados si es necesario */
</style>
