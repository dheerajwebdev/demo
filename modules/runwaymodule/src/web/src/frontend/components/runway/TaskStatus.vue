<script>
export default {
  props: {
    initialStatus: Number,
  },

  data() {
    return {
      status: this.initialStatus,
    }
  },
  watch: {
    initialStatus(newProp, oldProp) {
      this.status = newProp
    },
    status(newStatus, oldStatus) {
      if (newStatus !== this.initialStatus) {
        this.$emit('updateStatus', newStatus)
      }
    },
  },
  mounted() {
    // console.log(this.$refs)
  },
}
</script>

<template>
  <div class="relative flex items-center">
    <span class="w-16">{{ status }}%</span>
    <div class="mt-1">
      <input
        type="range"
        class="
          w-full
          accent-[#36404b]
          min-w-[200px]
          opacity-25
          group-hover:opacity-100
          focus:opacity-100
        "
        v-model="status"
        ref="input"
        step="10"
        min="0"
        max="100"
      />
    </div>
  </div>
</template>

<style scoped>
</style>
