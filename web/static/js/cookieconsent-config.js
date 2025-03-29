import 'https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v3.0.0-rc.17/dist/cookieconsent.umd.js';

CookieConsent.run({
  categories: {
    analytics: {
      enabled: false
    }
  },

  language: {
    default: 'en',
    translations: {
        en: {
            consentModal: {
                // title: 'We use cookies',
                description: `This website uses cookies for analytics purposes, to help understand how our visitors interact with the website, and improve the user experience. <a href="/cookie-policy">View our cookie policy.</a>`,
                acceptAllBtn: 'Allow Cookies',
                acceptNecessaryBtn: 'Decline',
                showPreferencesBtn: false
            },
        }
    },
  },

  guiOptions: {
    consentModal: {
        layout: 'bar inline',
        position: 'bottom right',
        flipButtons: false,
        equalWeightButtons: true
    },
  }
});