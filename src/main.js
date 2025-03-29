// Select all elements with the class "no-js"
const elements = document.querySelectorAll(".no-js");

// Loop through each element and replace the class
for (const element of elements) {
  element.classList.remove("no-js");
  element.classList.add("js");
}

// Alpine
import Alpine from 'alpinejs'
import focus from '@alpinejs/focus'
import collapse from '@alpinejs/collapse'
import './main.css'

Alpine.plugin(focus)
Alpine.plugin(collapse)
window.Alpine = Alpine
Alpine.start()

// GSAP
import { gsap } from "gsap"
import { ScrollTrigger } from "gsap/ScrollTrigger"

gsap.registerPlugin(ScrollTrigger)

window.onload = function() {
  let isReduced = window.matchMedia(`(prefers-reduced-motion: reduce)`) === true || window.matchMedia(`(prefers-reduced-motion: reduce)`).matches === true
  let hasAnchor = window.location.hash.length

  // Don't animate if reduced motion is enabled or there is a page anchor
  if (!isReduced && !hasAnchor) {

    // REVEALITEMS
    const revealItemsContainers = document.querySelectorAll('.reveal-items-container')
    revealItemsContainers.forEach(revealItemsContainer => {
      gsap.from(revealItemsContainer.querySelectorAll('.reveal-items-item'), {
        scrollTrigger: {
          start: 'top 75%',
          trigger: revealItemsContainer,
        },
        opacity: 0,
        translateY: 30,
        ease: '',
        stagger: 0.2,
      });
    })

    // Parallax backgrounds
    gsap.utils.toArray('.parallax').forEach((px, i) => {
      ScrollTrigger.create({
        trigger: px.querySelectorAll('.bg'),
        start: "top top",
        pin:true,
        scrub: true,
      });
    });

    // Animate in images
    const animateImageContainers = document.querySelectorAll('.animate-image-container')
    animateImageContainers.forEach(animateImageContainer => {
      gsap.set(animateImageContainer.querySelectorAll('img'), {
        width: 1,
        xPercent: -100,
        height: '100%',
        scale: 2,
        autoAlpha: 0
      });
      gsap.to(animateImageContainer.querySelectorAll('img'), {
        scrollTrigger: {
          start: 'top 75%',
          trigger: animateImageContainer,
        },
        ease: "",
        duration: 0,
        immediateRender: false,
        keyframes: [
          {xPercent: 0, autoAlpha: 1, duration: 0},
          {width: '100%', duration: 0.5},
          {scale: 1, duration: 0.5},
        ]
      });
    })
  }
}

// Make all external links open in a new window
document.addEventListener("DOMContentLoaded", function() {
  // Get the current domain of the page
  var currentDomain = window.location.hostname;

  // Select all links with an 'href' attribute
  var links = document.querySelectorAll('a[href]');

  // Iterate through the links
  links.forEach(function(link) {
    // Create a temporary anchor element to extract the domain
    var tempAnchor = document.createElement('a');
    tempAnchor.href = link.href;

    // Check if the link is external
    if (link.href.startsWith('http') && tempAnchor.hostname !== currentDomain) {
      // Set the 'target' attribute to '_blank' to open in a new tab
      link.target = '_blank';

      // Add the 'rel' attribute for security
      link.rel = 'noopener noreferrer';
    }
  });
});
