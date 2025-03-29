<script>
export default {
  props: {
    initialEstimate: Number,
  },

  data() {
    return {
      estimate: this.initialEstimate,
      inputVisible: false,
    }
  },
  methods: {},

  watch: {
    initialEstimate(newProp, oldProp) {
      this.estimate = newProp
    },
    estimate(newEstimate, oldEstimate) {
      if (newEstimate !== this.initialEstimate) {
        this.$emit('updateEstimate', newEstimate)
      }
    },
  },
  mounted() {
    // console.log(this.$refs)
  },
}
</script>

<template>
  <div class="relative">
    <input
      type="number"
      v-model="estimate"
      ref="input"
      class="
        bg-transparent
        border border-transparent
        group-hover:border-black/20
        focus:border-black/20
        w-16
        py-0.5
        pl-1.5
        pr-0.5
        rounded-sm
        accent-[#36404b]
      "
    />
  </div>
</template>

<style scoped>
</style>
