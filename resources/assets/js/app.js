/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import moment from 'moment';
import bus from './event-bus';

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

$(function() {
    const app = new Vue({
        el: '#app',

        data: {
            isDragging: false,
        },

        mounted() {
            $('#calendar').fullCalendar({
                themeSystem: 'bootstrap4',
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                height: 'auto',
                customButtons: {
                    addEvent: {
                        text: 'Ny bokning',
                        click: function() {
                            bus.$emit('modal:type', 'add-event');
                            $('.modal').modal('show');
                        },
                    },
                },
                header: {
                    left: 'today prev,next addEvent',
                    center: 'title',
                    right: $(window).width() < 765 ? '' : 'agendaDay,agendaWeek',
                },
                defaultView: $(window).width() < 765 ? 'agendaDay' : 'agendaWeek',
                columnHeaderHtml: function(date) {
                    return `${date.format('ddd D/M')}<i class="fas fa-print"></i>`;
                },
                locale: 'sv',
                buttonText: {
                    today: 'Idag',
                    month: 'Månad',
                    week: 'Vecka',
                    day: 'Dag',
                    list: 'Lista',
                },
                allDayText: 'Obestämd tid',
                slotLabelFormat: 'HH:mm',
                slotLabelInterval: '00:30',
                defaultTimedEventDuration: '00:30',
                minTime: '07:00',
                maxTime: '17:00',
                allDaySlot: true,
                firstDay: 1,
                displayEventTime: false,
                eventStartEditable: true,
                nowIndicator: true,
                events: '/bookings',
                eventDataTransform: function(eventData) {
                    return {
                        id: eventData.id,
                        title: eventData.title,
                        description: eventData.description,
                        jobTypeId: eventData.job_type_id,
                        allDay: eventData.all_day,
                        start: eventData.all_day
                            ? eventData.date
                            : eventData.date + ' ' + eventData.time,
                        color: eventData.job_type_color,
                    };
                },
                eventClick: function(event, jsEvent, view) {
                    event.date = event.start.format('YYYY-MM-DD');
                    event.time = event.start.format('HH:mm');
                    bus.$emit('modal:type', 'edit-event', event);
                    $('.modal').modal('show');
                },
                dayClick: function(date, jsEvent, view) {
                    bus.$emit('modal:type', 'add-event', {
                        date: date.format('YYYY-MM-DD'),
                        time: date.format('HH:mm'),
                        allDay: !date.hasTime(),
                    });
                    $('.modal').modal('show');
                },
                eventDrop: function(event, delta, revertFunc) {
                    event.date = event.start.format('YYYY-MM-DD');
                    event.time = event.start.format('HH:mm');
                    delete event.source;
                    axios.put('/bookings/' + event.id, event);
                },
                eventAfterAllRender: function() {
                    $('#calendar .fc-head-container .fc-day-header')
                        .off('click')
                        .on('click', function() {
                            const date = $(this).attr('data-date');
                            window.location = `print?date=${date}`;
                        });
                },
            });
        },

        components: {
            'event-modal': require('./components/EventModal.vue'),
            print: require('./components/Print.vue'),
        },
    });
});
