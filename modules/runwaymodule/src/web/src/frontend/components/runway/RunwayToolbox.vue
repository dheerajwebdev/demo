<template>
  <div class="w-[350px]">
    <div
      ref="toolbox"
      class="
        toolbox
        absolute
        shadow-md
        rounded-md
        overflow-hidden
        font-mono
        text-[12px]
      "
    >
      <div
        class="
          flex
          rounded-t-md
          justify-between
          items-center
          text-white
          w-full
          bg-[#36404c]
        "
      >
        <div id="handle" class="p-2 flex-1 flex">
          <svg
            class="h-5 w-5 mr-1.5 -mt-0.5"
            width="15"
            height="15"
            viewBox="0 0 15 15"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M5.5 4.625C6.12132 4.625 6.625 4.12132 6.625 3.5C6.625 2.87868 6.12132 2.375 5.5 2.375C4.87868 2.375 4.375 2.87868 4.375 3.5C4.375 4.12132 4.87868 4.625 5.5 4.625ZM9.5 4.625C10.1213 4.625 10.625 4.12132 10.625 3.5C10.625 2.87868 10.1213 2.375 9.5 2.375C8.87868 2.375 8.375 2.87868 8.375 3.5C8.375 4.12132 8.87868 4.625 9.5 4.625ZM10.625 7.5C10.625 8.12132 10.1213 8.625 9.5 8.625C8.87868 8.625 8.375 8.12132 8.375 7.5C8.375 6.87868 8.87868 6.375 9.5 6.375C10.1213 6.375 10.625 6.87868 10.625 7.5ZM5.5 8.625C6.12132 8.625 6.625 8.12132 6.625 7.5C6.625 6.87868 6.12132 6.375 5.5 6.375C4.87868 6.375 4.375 6.87868 4.375 7.5C4.375 8.12132 4.87868 8.625 5.5 8.625ZM10.625 11.5C10.625 12.1213 10.1213 12.625 9.5 12.625C8.87868 12.625 8.375 12.1213 8.375 11.5C8.375 10.8787 8.87868 10.375 9.5 10.375C10.1213 10.375 10.625 10.8787 10.625 11.5ZM5.5 12.625C6.12132 12.625 6.625 12.1213 6.625 11.5C6.625 10.8787 6.12132 10.375 5.5 10.375C4.87868 10.375 4.375 10.8787 4.375 11.5C4.375 12.1213 4.87868 12.625 5.5 12.625Z"
              fill="currentColor"
            />
          </svg>
          <span class="text-left uppercase font-bold"> Runway Toolbox </span>
        </div>

        <button
          @click="toggle()"
          class="
            p-2
            flex
            items-center
            justify-center
            bg-transparent
            border-none
            text-white
          "
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 transition duration-100"
            :class="{ ' -rotate-90': open }"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
              clip-rule="evenodd"
            />
          </svg>
        </button>
      </div>
      <div
        v-if="open"
        class="
          bg-gray-100
          border border-[#36404c]
          rounded-b-md
          py-2
          px-3
          text-[11px] text-black
        "
      >
        <ul class="space-y-2.5 pb-2 list-none pl-0 m-0">
          <li
            class="
              flex
              justify-between
              border-solid
              border-t-0
              border-l-0
              border-r-0
              border-b
              border-gray-400/50
            "
          >
            <span class="bg-gray-100 -mb-1.5 pr-1.5">Breakpoint</span>
            <span class="bg-gray-100 -mb-1.5 pl-1.5">
              <span class="md:hidden">SM</span>
              <span class="hidden md:block lg:hidden">MD</span>
              <span class="hidden lg:block xl:hidden">LG</span>
              <span class="hidden xl:block 2xl:hidden">XL</span>
              <span class="hidden 2xl:block">XL</span>
            </span>
          </li>
          <li class="">
            <label
              class="
                flex
                -mr-0.5
                justify-between
                border-solid
                border-t-0
                border-l-0
                border-r-0
                border-b
                border-gray-400/50
              "
              for="gridOverlay"
            >
              <span class="bg-gray-100 -mb-1.5 pr-1.5">Grid overlay</span>
              <span
                class="bg-gray-100 -mb-1.5 pl-0.5 flex items-end justify-end"
              >
                <input
                  id="gridOverlay"
                  v-model="gridOverlay"
                  class="h-3 w-3 -mt-1 accent-[#36404c]"
                  type="checkbox"
                />
              </span>
            </label>
          </li>
          <li class="hidden">
            <label
              class="
                flex
                justify-between
                border-solid
                border-t-0
                border-l-0
                border-r-0
                border-b
                border-gray-400/50
              "
              for="blockDesign"
            >
              <span class="bg-gray-100 -mb-1.5 pr-1.5">Block preview</span>
              <span class="bg-gray-100 -mb-1.5 pl-1.5">
                <input
                  id="blockDesign"
                  v-model="blockDesign"
                  class="h-4 w-4 accent-[#36404c]"
                  type="checkbox"
                />
              </span>
            </label>
          </li>
          <li class="">
            <label
              class="
                flex
                -mr-0.5
                justify-between
                border-solid
                border-t-0
                border-l-0
                border-r-0
                border-b
                border-gray-400/50
              "
              for="debugoutlines"
            >
              <span class="bg-gray-100 -mb-1.5 pr-1.5">Debug outlines</span>
              <span
                class="bg-gray-100 -mb-1.5 pl-0.5 flex items-end justify-end"
              >
                <input
                  id="debugoutlines"
                  v-model="debugOutlines"
                  class="h-3 w-3 -mt-1 accent-[#36404c]"
                  type="checkbox"
                />
              </span>
            </label>
          </li>
          <li
            class="
              hidden
              flex
              justify-between
              border-solid
              border-t-0
              border-l-0
              border-r-0
              border-b
              border-gray-400/50
            "
          >
            <span class="bg-gray-100 -mb-1.5 pr-1.5">Assigned to</span>
            <span class="bg-gray-100 -mb-1.5 pl-1.5">chrisrowe</span>
          </li>
          <li class="hidden">
            <span
              class="
                text-left
                flex
                justify-between
                border-solid
                border-t-0
                border-l-0
                border-r-0
                border-b
                border-gray-400/50
              "
            >
              <span class="bg-gray-100 -mb-1.5 pr-1.5">New GitHub issue</span>
              <a href="" class="bg-gray-100 -mb-1.5 pl-1.5">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5 -mb-1.5"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"
                  />
                  <path
                    d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"
                  />
                </svg>
              </a>
            </span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  // name: "App",
  data() {
    return {
      open: true,
      gridOverlay: false,
      debugOutlines: false,
      blockDesign: false,
    }
  },
  mounted() {
    if (localStorage.toolboxPosition) {
      const coords = JSON.parse(localStorage.toolboxPosition)
      console.log(coords)
      console.log(this.$refs)
      this.$refs.toolbox.style.left = coords.x + 'px'
      this.$refs.toolbox.style.top = coords.y + 'px'
    }
    if (localStorage.debugOutlines) {
      this.debugOutlines = localStorage.debugOutlines == 'true'
    }
    if (localStorage.open) {
      this.open = localStorage.open == 'true'
    }
  },
  methods: {
    // debugOutlines() {
    //   [].forEach.call(document.querySelectorAll("*"),function(a){a.style.outline="1px solid #"+(~~(Math.random()*(1<<24))).toString(16)})
    // },
    toggle() {
      this.open = !this.open
      localStorage.open = this.open
    },
    toolboxMoved(e) {
      const { x, y } = e.target.getBoundingClientRect()
      localStorage.toolboxPosition = JSON.stringify({
        x: parseInt(x),
        y: parseInt(y),
      })
    },
  },
  watch: {
    debugOutlines(val) {
      localStorage.debugOutlines = val
      if (val) {
        const style = document.createElement('style')
        style.setAttribute('id', 'debug-outlines')
        style.textContent = `
        * { outline: 2px dotted red }
* * { outline: 2px dotted green }
* * * { outline: 2px dotted orange }
* * * * { outline: 2px dotted blue }
* * * * * { outline: 1px solid red }
* * * * * * { outline: 1px solid green }
* * * * * * * { outline: 1px solid orange }
* * * * * * * * { outline: 1px solid blue }`
        document.head.appendChild(style)
      } else {
        document.getElementById('debug-outlines').remove()
      }
    },
  },
}
</script>

<style scoped>
@import './../../main.css';
</style>
