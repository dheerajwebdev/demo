<script>
  export default {
    props: {
      initial: String,
      // modelValue: String,
    },
    data() {
      return {
        assigned: this.initial,
        team: [
          'andrewmenich',
          'antcooper',
          'chrisrowe',
          'gavinplatt',
          'geenious',
          'robinsm',
          'zjensendev',
        ]
      }
    },
    methods: {
      changed() {
        this.$emit('update', this.assigned)
        // this.$emit('update', this.value)
      },
    },
  }
  </script>
  
  <template>
    <div class="">
      <select
        v-model="assigned"
        @change="changed()"
        class="bg-transparent border border-transparent group-hover:border-black/20 focus:border-black/20 p-1 pl-2 pr-12 rounded-sm accent-[#36404b]"
        style="-webkit-appearance: none;-moz-appearance: none;"
      >
        <option value=""></option>
        <option v-for="person in team" :key="person" :value="person">{{ person }}</option>
      </select>
    </div>
  </template>
  
  <style scoped>
  </style>
  