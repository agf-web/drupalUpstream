// LOAD THIS GLOBALLY

/* eslint-disable */
window.AGF_CONFIG = {
  useDrupalDetailUrl: true,
  disableLogo: true,
  customLogo: {
    useCustomLogo: false,
    url: '/themes/custom/rivervalley/img/logo.jpg'
  },
  customIntro: {
    useCustomIntro: false,
    text: 'Farm Credit has been helping Georgia grow for generations by providing loans for land, farms & homes. Simply enter your county in the search box below to find the location that serves you.'
  },

  // to filter by state
  byState: false,

  // must be string. can be lower/upper/mixed case.
  stateName: 'sc',

  // to filter by association
  byAssociation: true,

  // must be index from `associations` array. Example: this.associations[7]
  associationName: function () {
    return this.associations[16];
  },

  // associations array
  associations: [
    'AgCarolina Farm Credit, ACA',              // 0
    'AgChoice Farm Credit, ACA',                // 1
    'AgCredit, ACA',                            // 2
    'AgGeorgia Farm Credit, ACA',               // 3
    'AgSouth Farm Credit, ACA',                 // 4
    'ArborOne Farm Credit, ACA',                // 5
    'Cape Fear Farm Credit, ACA',               // 6
    'Carolina Farm Credit, ACA',                // 7
    'Central Kentucky ACA',                     // 8
    'Colonial Farm Credit',                     // 9
    'Farm Credit of Central Florida, ACA',      // 10
    'Farm Credit of Florida, ACA',              // 11
    'Farm Credit of Northwest Florida, ACA',    // 12
    'Farm Credit of the Virginias, ACA',        // 13
    'First South ACA',                          // 14
    'MidAtlantic Farm Credit, ACA',             // 15
    'River Valley AgCredit',                    // 16
    'Southwest Georgia Farm Credit, ACA',       // 17
    'Yankee Farm Credit ACA'                    // 18
  ]
};
/* eslint-enable */
