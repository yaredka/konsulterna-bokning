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
            isDragging: false
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
                        }
                    }
                },
                header: {
                    left: 'today prev,next addEvent',
                    center: 'title',
                    right: $(window).width() < 765 ? '' : 'agendaDay,agendaWeek',
                },
                defaultView: $(window).width() < 765 ? 'agendaDay' : 'agendaWeek',
                columnHeaderFormat: 'ddd D/M',
                locale: 'sv',
                buttonText: {
                    today: 'Idag',
                    month: 'Månad',
                    week: 'Vecka',
                    day: 'Dag',
                    list: 'Lista'
                },
                allDayText: 'Obestämd tid',
                slotLabelFormat: 'HH:mm',
                slotLabelInterval: '00:30',
                defaultTimedEventDuration: '00:30',
                minTime: '07:00',
                maxTime: '17:00',
                allDaySlot: true,
                firstDay: 'Monday',
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
                        start: eventData.all_day ? eventData.date : eventData.date + ' ' + eventData.time,
                        color: eventData.job_type_color
                    };
                },
                eventRender: function(eventObj, element) {
                    if ($(window).width() > 765) {
                        element.qtip({
                            content: {
                                text: 'Hi. I am a sample tooltip!',
                                title: 'Sample tooltip'
                            }
                        });
                    }
                },
                eventClick: function(calEvent, jsEvent, view) {
                    bus.$emit('modal:type', 'edit-event', {
                        id: calEvent.id,
                        title: calEvent.title,
                        description: calEvent.description,
                        jobTypeId: calEvent.jobTypeId,
                        date: calEvent.start.format('YYYY-MM-DD'),
                        time: calEvent.start.format('HH:mm'),
                        allDay: calEvent.allDay
                    });
                    $('.modal').modal('show');
                },
                dayClick: function (date, jsEvent, view) {
                    bus.$emit('modal:type', 'add-event', {
                        date: date.format('YYYY-MM-DD'),
                        time: date.format('HH:mm'),
                        allDay: !date.hasTime()
                    });
                    $('.modal').modal('show');
                },
                eventDrop: function(event, delta, revertFunc) {
                    axios.put('/bookings/' + event.id, {
                        date: event.start.format('YYYY-MM-DD'),
                        time: event.start.format('HH:mm'),
                        allDay: event.allDay
                    })
                }
            })
        },

        components: {
            'event-modal': require('./components/EventModal.vue')
        }
    });
});
