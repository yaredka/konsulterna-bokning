<template>
    <div class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-if="modalType == 'add-event'">Ny bokning</h5>
                    <h5 class="modal-title" v-else-if="modalType == 'edit-event'">Ändra bokning</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Titel *" v-model="event.title">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="4" placeholder="Beskrivning" v-model="event.description"></textarea>
                        </div>
                        <div class="form-group">
                            <select class="form-control" v-model="event.jobTypeId">
                                <option :value="null" selected disabled>Välj typ av jobb *</option>
                                <option v-for="jobType in jobTypes" :value="jobType.id">{{ jobType.name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" id="option1" type="radio" v-model="event.allDay" :value="false">
                                <label class="form-check-label" for="option1">
                                    Bestämd tid
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="option2" type="radio" v-model="event.allDay" :value="true">
                                <label class="form-check-label" for="option2" v-model="event.allDay" :value="false">
                                    Obestämd tid
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-inline">
                                <input type="date" class="form-control" v-model="event.date">
                                <input type="time" class="form-control" v-model="event.time" v-show="!event.allDay">
                            </div>
                        </div>


                        <div class="alert alert-danger" v-if="event.validationErrors.length">
                            <ul class="mb-0">
                                <li v-for="error in event.validationErrors" v-text="error"></li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" v-if="modalType == 'add-event'" class="btn btn-outline-secondary" data-dismiss="modal">Stäng</button>
                    <button type="button" v-else-if="modalType == 'edit-event'" class="btn btn-danger" @click="deleteEvent">Ta bort</button>
                    <button type="button" class="btn btn-primary" @click="addEvent">Lägg till</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    require('../bootstrap');
    import moment from 'moment';
    import bus from '../event-bus';

    export default {
        props: {
            jobTypes: Array
        },

        data() {
            return {
                modalType: null,
                event: {
                    id: null,
                    title: '',
                    description: '',
                    jobTypeId: null,
                    allDay: false,
                    date: null,
                    time: null,
                    validationErrors: []
                }
            }
        },

        mounted() {
            $('.modal').on('hidden.bs.modal', (e) => {
                this.event = {
                    id: null,
                    title: '',
                    description: '',
                    jobTypeId: null,
                    allDay: false,
                    date: null,
                    time: null,
                    validationErrors: []
                }
            })

            bus.$on('modal:type', (type, event) => {
                if (event) {
                    if (type == 'edit-event') {
                        this.event.id = event.id;
                        this.event.title = event.title;
                        this.event.description = event.description;
                        this.event.jobTypeId = event.jobTypeId;
                    }

                    this.event.date = event.date;
                    this.event.time = event.time;
                    this.event.allDay = event.allDay;
                }
                this.modalType = type;
            });
        },

        methods: {
            deleteEvent() {
                axios.delete('/bookings/' + this.event.id);
                $('#calendar').fullCalendar('removeEvents', this.event.id);
                $('.modal').modal('hide');
            },
            addEvent() {
                axios.post('/bookings', {
                    title: this.event.title,
                    description: this.event.description,
                    jobTypeId: this.event.jobTypeId,
                    allDay: this.event.allDay,
                    date: this.event.date,
                    time: this.event.time,
                })
                .then(response => {
                    $('#calendar').fullCalendar('renderEvent', {
                        title: this.event.title,
                        start: this.event.allDay ? this.event.date : this.event.date + ' ' + this.event.time,
                        allDay: this.event.allDay
                    });
                    $("#calendar").fullCalendar('unselect');
                    $('.modal').modal('hide');
                })
                .catch(error => {
                    this.validationErrors = [];
                    const errors = error.response.data.errors;

                    for (const key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            this.event.validationErrors.push(errors[key][0]);
                        }
                    }
                });
            }
        }
    }
</script>
