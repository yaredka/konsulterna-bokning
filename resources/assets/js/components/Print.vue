<template>
	<div class="print">
		<h1>Schema för {{ title }}</h1>
		<div v-for="(bookings, time) in grouped_bookings" class="time-row">
			<div class="time">
				{{ time | format_time }}
			</div>
			<div v-for="booking in bookings" class="cell">
				<div v-bind:style="{ background: booking.job_type.primary_color }">
					<h5>{{ booking.title }}</h5>
					<p>{{ booking.description }}</p>
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
		title: String,
		grouped_bookings: Object,
	},
	data() {
		return {
			events: null,
		};
	},

	mounted() {
		window.print();
	},

	filters: {
		format_time: time => (time ? time.substring(0, 5) : 'Obestämd'),
	},
};
</script>
