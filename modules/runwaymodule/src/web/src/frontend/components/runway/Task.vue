<script>
import TaskNotes from './TaskNotes.vue'
import TaskAssignee from './TaskAssignee.vue';
export default {
    props: {
        module: Object,
        folder: String,
    },
    data() {
        return {
            expanded: false,
        };
    },
    computed: {
        unavatarUrl() {
            return 'https://unavatar.io/github/' + (this.module.assigned != "" ? this.module.assigned : 'simplygoodwork');
        },
    },
    watch: {
      // module: {
      //   handler(newValue, oldValue) {
      //     console.log(this.module)
      //     console.log(newValue)
      //   },
      //   deep: true
      // }
    },
    methods: {
        toggle() {
            this.expanded = !this.expanded;
        },
        updateProperty(properties, folder = this.folder, item = this.module.slug) {
          // console.log(properties)
          for (const [key, value] of Object.entries(properties)) {
            this.module[key] = value;
          }
          fetch("/actions/runway/default/update-task", {
              method: "post",
              headers: {
                  Accept: "application/json, text/plain, */*",
                  "Content-Type": "application/json",
              },
              body: JSON.stringify({ folder, item, properties }),
          })
              .then((res) => {
                this.emitter.emit("update-progress")
                return res.json()
              })
              // .then((res) => ))
              // .then((res) => console.log(res));
        },
    },
    components: { TaskNotes, TaskAssignee }
}
</script>

<template class="">
  <tbody>
    <tr class="w-full group border-t border-b">
      <td class="w-3/12 !rounded-none">
        <div
          class="status ml-2"
          :class="{
            live: module.status == 100,
            expired: module.status == 0,
            pending: 100 > module.status > 0,
          }"
        ></div>
        <a :href="module.url">{{ module.slug }}</a>
      </td>
      <td class="w-3/12">
        <div class="flex flex-row items-center">
          <img
            class="w-6 h-6 border-2 border-white outline outline-[#eee] rounded-full mr-2 my-0 bg-white"
            :src="unavatarUrl"
            alt=""
          />
          <TaskAssignee
            :initial="module.assigned"
            @update="(newValue) => updateProperty({ assigned: newValue })"
          ></TaskAssignee>
        </div>
      </td>
      <td class="w-1/12">
          <TaskImages
            :images="module.images"
          ></TaskImages>
      </td>
      <td class="w-1/12">
        <TaskEstimate
          :initial-estimate="module.estimate ?? $root.defaultBlockEstimate"
          @update-estimate="
            (newEstimate) => updateProperty({ estimate: newEstimate })
          "
        />
      </td>
      <td class="w-1/12">
        <button
          @click="toggle()"
          class="status ml-2"
          :class="{
            expired: module.notes,
          }"
        ></button>
        <div
          v-if="expanded"
          class="fixed inset-0 bg-[#e6edf5]/90 z-50 flex items-center justify-center"
          @click="toggle()"
        >
          <div class="w-1/2 p-5 content-pane" @click.stop>
            <h2>{{ module.slug }} Notes</h2>
            <TaskNotes
              :initial-notes="module.notes ?? ''"
              @update-notes="(newNotes) => updateProperty({ notes: newNotes })"
              @close="toggle()"
            />
          </div>
        </div>
      </td>
      <td class="w-4/12 !rounded-none">
        <TaskStatus
          :initial-status="Number(module.status) ?? 0"
          @update-status="(newStatus) => updateProperty({ status: newStatus })"
        />
      </td>
  
    </tr>
    
  </tbody>
</template>

<style scoped>
</style>
