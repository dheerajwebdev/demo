<script>
  export default {
    props: {
      initialNotes: String,
    },
  
    data() {
      return {
        notes: this.initialNotes,
      }
    },
    methods: {
      updateNotes() {
        this.$emit('updateNotes', this.notes)
      },
      updateNotesAndClose() {
        this.$emit('updateNotes', this.notes)
        this.$emit('close')
      }
    },
    mounted() {
      this.$nextTick(() => {
        this.$refs.textarea.focus()
      })
    }
  }
  </script>
  
  <template>
    <div class="">
      <textarea
        @change="updateNotes()"
        @keydown.esc="updateNotesAndClose()"
        v-model="notes"
        rows="8"
        ref="textarea"
        class="
          bg-white
          border border-black/30
          focus:border-black/50 focus:outline-0
          w-full
          p-2
          rounded-sm
          accent-[transparent]
        "
      ></textarea>
    </div>
  </template>
  
  <style scoped>
  </style>
  