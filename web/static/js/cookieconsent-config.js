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
                description: `By using this website, you agree to our use of optional first-party and third-party cookies to enhance functionality and personalize your experience, perform analytics, and serve you with relevant ads, including on third-party websites.`,
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