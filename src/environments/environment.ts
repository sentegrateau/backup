// This file can be replaced during build by using the `fileReplacements` array.
// `ng build --prod` replaces `environment.ts` with `environment.prod.ts`.
// The list of file replacements can be found in `angular.json`.

export const environment = {
  // apiUrl: 'http://sentegrate-api.test/api',
   //apiUrl: 'https://sentegrate.cyberxstudio.com/sentegrate-api2/public/api',
  production: false,
    //apiUrl: 'http://139.162.199.168/sentegrate/public/api',
    apiUrl: 'https://staging.sentegrate.com.au/public/api',
    siteUrl: 'https://staging.sentegrate.com.au/'

 /* apiUrl:'https://www.sentegrate.com.au/public/api',
  siteUrl:'https://www.sentegrate.com.au/public/'*/
};

/*
 * For easier debugging in development mode, you can import the following file
 * to ignore zone related error stack frames such as `zone.run`, `zoneDelegate.invokeTask`.
 *
 * This import should be commented out in production mode because it will have a negative impact
 * on performance if an error is thrown.
 */
// import 'zone.js/dist/zone-error';  // Included with Angular CLI.
