<template>
  <div>
    <h2>
      Progress Tracker
      <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs">
        <span v-html="percentComplete"></span>%
      </span>
    </h2>
    <div class="rounded-full relative overflow-hidden h-3 flex mb-3">
      <span class="w-full h-full absolute top-0 left-0" style="background:#E5E7EB"></span>
      <span class="h-full absolute top-0 left-0 rounded-none border-none transition-all" :style="allocated">
          <span class="h-full absolute top-0 left-0 rounded-none border-none transition-all status live" :style="used"></span>
      </span>
    </div>
    <div class="text-gray-600 text-sm leading-relaxed">
          Estimated {{ totalEstimatedHours }} hrs, 
          {{ totalAllocatedHours }} allocated,
          {{ totalUsedHours }} used so far.
          <br/>
          Development deadline: {{ formattedDate }}, {{ countdownDays }} days remaining.
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      percentage: '-',
      totalEstimatedHours: '-',
      totalAllocatedHours: '-',
      totalUsedHours: '-',
      timeline: '-',
    }
  },
  methods: {
    getProgress() {
      fetch('/actions/runway/default/get-progress-tracker')
      .then((res) => res.json())
      .then((res) => {  
        console.log(res)
        this.totalEstimatedHours = res.totalEstimatedHours
        this.totalAllocatedHours = res.totalAllocatedHours
        this.timeline = res.timeline
        this.totalUsedHours = res.totalUsedHours.toFixed(0)
      })
    },
  },
  computed: {
    formattedDate() {
      const d = new Date(this.timeline.end)
      return d.toLocaleDateString()
    },
    countdownDays() {
      const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
      const end = new Date(this.timeline.end);
      const now = new Date();
      const diffDays = Math.round(Math.abs((end - now) / oneDay));
      return diffDays
    },
    percentComplete() {
      return ((this.totalUsedHours /this.totalAllocatedHours) * 100).toFixed(0)
    },
    allocated() {
      return {
        backgroundColor: '#FB923C',
        width: ((this.totalAllocatedHours /this.totalEstimatedHours) * 100) + '%'
      };
    },
    used() {
      return {
        width: this.percentComplete + '%'
      };
    }
  },
  mounted() {
    this.getProgress()

    this.emitter.on("update-progress", () => {
      this.getProgress()
    });
  }
}
</script>
