<template lang="html">
	<transition-group name="hide-notification" tag="div" class="notifications" v-if="notifications != 'null'">
		<div class="notification" v-for="notification in notifications" :key="notifications.indexOf(notification)" @click="closeNotification(notification)">{{ notification }}</div>
	</transition-group>
</template>

<script>
export default {
	props: {
		info: {
			type: Array,
		},
	},

	data() {
		return {
			notifications: [],
		}
	},

	created() {
		this.notifications = this.info
	},

	methods: {
		closeNotification(selectedNotification) {
			var notificationKey = this.notifications.indexOf(selectedNotification);
			this.notifications.splice(notificationKey, 1);
		}
	}
}
</script>

<style lang="scss">
@import 'resources/assets/sass/_variables.scss';

.notifications {
	position: fixed;
	bottom: 0;
	left: 50%;
	width: 100%;
	transform: translate3d(-50%, 12px, 0);
	z-index: 99;
}
.notification {
	cursor: pointer;
	margin-bottom: 12px;
	padding: 24px 40px;
  background: rgba($primary, .95);
	color: $white;
	font-size: 16px;
	line-height: 1.4;
	text-align: center;
}
.hide-notification-enter-active,
.hide-notification-leave-active {
	transform: scale(0);
	opacity: 0;
	transition: transform .2s ease-out, opacity .2s ease-out;
}

@media (min-width: 776px) {
	.notifications {
		width: auto;
	}
	.notification {
		border-radius: 4px;
	}
}
</style>
