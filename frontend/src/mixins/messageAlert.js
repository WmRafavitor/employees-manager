export default {
  data() {
    return {
      alert: {
        type: 'error',
        message: null
      },
    }
  },
  methods: {
    showError(error) {
      this.$set(this.alert, 'message', error);
      this.$set(this.alert, 'type', 'error');
      this.$nextTick(() => this.$vuetify.goTo(this.$refs.messageAlert));
    },
    showSuccess(message) {
      this.$set(this.alert, 'message', message);
      this.$set(this.alert, 'type', 'success');
      this.$nextTick(() => this.$vuetify.goTo(this.$refs.messageAlert));
    },
    resetError() {
      this.$set(this.alert, 'message', null);
    }
  },
  computed: {
    error() {
      if (this.alert.type === 'error') {
        return this.alert.message;
      }
      return null;
    }
  }
}