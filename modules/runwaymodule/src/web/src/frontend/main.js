import { createApp, defineCustomElement } from 'vue'

import RunwayToolbox from './components/runway/RunwayToolbox.vue'
const runwayComponents = import.meta.globEager('./components/runway/*.vue')

const runwayToolbox = defineCustomElement(RunwayToolbox)
customElements.define('runway-toolbox', runwayToolbox)

// import drag from 'v-drag'
import './main.css'

import mitt from 'mitt';
const emitter = mitt();

// * Init Runway Vue Instance
const runway = createApp({
  compilerOptions: {
    delimiters: ['${', '}'],
  },
  data() {
    return {
      pm: null,
      defaultBlockEstimate: undefined,
      totalEstimatedHours: undefined,
    }
  },
  methods: {
    setStatus(folder, item, status, value) {
      fetch('/actions/runway/default/set-status', {
        method: 'post',
        headers: {
          Accept: 'application/json, text/plain, */*',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          folder: folder,
          item: item,
          status: status,
          value: value,
        }),
      })
        .then((res) => res.json())
        // .then((res) => console.log(res))
    },
  },
  mounted() {
    fetch('/actions/runway/default/get-state')
      .then((res) => res.json())
      .then((res) => {
        this.pm = res.pm
        this.defaultBlockEstimate = res.defaultBlockEstimate
        this.totalEstimatedHours = res.totalEstimatedHours
      })
  },
})

//  * Load all Vue components and register them (Thanks, Jake!)
Object.entries(runwayComponents).forEach(([path, definition]) => {
  // Get name of component, based on filename
  // "./components/Fruits.vue" will become "Fruits"
  const componentName = path
    .split('/')
    .pop()
    .replace(/\.\w+$/, '')
  runway.component(componentName, definition.default)
})

// * Add click-outside directive, similar to Alpines x-on:click.outside functionality
// const clickOutside = {
//   beforeMount: (el, binding) => {
//     el.clickOutsideEvent = (event) => {
//       // here I check that click was outside the el and his children
//       if (!(el === event.target || el.contains(event.target))) {
//         // and if it did, call method provided in attribute value
//         binding.value()
//       }
//     }
//     document.addEventListener('click', el.clickOutsideEvent)
//   },
//   unmounted: (el) => {
//     document.removeEventListener('click', el.clickOutsideEvent)
//   },
// }

// runway.use(drag)

// * Attach new click-outside directive to Vue instance
// runway.directive('click-outside', clickOutside)

// * Mount Vue

runway.config.globalProperties.emitter = emitter;

runway.mount('#runway')
