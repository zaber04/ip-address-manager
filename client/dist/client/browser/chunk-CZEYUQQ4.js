import{a as u}from"./chunk-MUKAY4P3.js";import{q as d,s as f,t as l,ta as m}from"./chunk-VTQJTHAG.js";var k=new f("JWT_OPTIONS"),c=(()=>{class n{constructor(e=null){this.tokenGetter=e&&e.tokenGetter||function(){}}urlBase64Decode(e){let t=e.replace(/-/g,"+").replace(/_/g,"/");switch(t.length%4){case 0:break;case 2:{t+="==";break}case 3:{t+="=";break}default:throw new Error("Illegal base64url string!")}return this.b64DecodeUnicode(t)}b64decode(e){let t="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",i="";if(e=String(e).replace(/=+$/,""),e.length%4===1)throw new Error("'atob' failed: The string to be decoded is not correctly encoded.");for(let p=0,h,a,g=0;a=e.charAt(g++);~a&&(h=p%4?h*64+a:a,p++%4)?i+=String.fromCharCode(255&h>>(-2*p&6)):0)a=t.indexOf(a);return i}b64DecodeUnicode(e){return decodeURIComponent(Array.prototype.map.call(this.b64decode(e),t=>"%"+("00"+t.charCodeAt(0).toString(16)).slice(-2)).join(""))}decodeToken(e=this.tokenGetter()){return e instanceof Promise?e.then(t=>this._decodeToken(t)):this._decodeToken(e)}_decodeToken(e){if(!e||e==="")return null;let t=e.split(".");if(t.length!==3)throw new Error("The inspected token doesn't appear to be a JWT. Check to make sure it has three parts and see https://jwt.io for more.");let i=this.urlBase64Decode(t[1]);if(!i)throw new Error("Cannot decode the token.");return JSON.parse(i)}getTokenExpirationDate(e=this.tokenGetter()){return e instanceof Promise?e.then(t=>this._getTokenExpirationDate(t)):this._getTokenExpirationDate(e)}_getTokenExpirationDate(e){let t;if(t=this.decodeToken(e),!t||!t.hasOwnProperty("exp"))return null;let i=new Date(0);return i.setUTCSeconds(t.exp),i}isTokenExpired(e=this.tokenGetter(),t){return e instanceof Promise?e.then(i=>this._isTokenExpired(i,t)):this._isTokenExpired(e,t)}_isTokenExpired(e,t){if(!e||e==="")return!0;let i=this.getTokenExpirationDate(e);return t=t||0,i===null?!1:!(i.valueOf()>new Date().valueOf()+t*1e3)}getAuthScheme(e,t){return typeof e=="function"?e(t):e}}return n.\u0275fac=function(e){return new(e||n)(l(k))},n.\u0275prov=d({token:n,factory:n.\u0275fac}),n})();var o=class n{static isBrowser(){return typeof window<"u"}static Supported(){return n.isBrowser()&&typeof Storage<"u"}static set(r,e,t=0){if(n.isBrowser()){let i={value:e,canExpire:t>=0,expireAt:new Date().getTime()/1e3+t};localStorage.setItem(r,JSON.stringify(i))}}static get(r){if(n.isBrowser()){let e=localStorage.getItem(r);if(!e)return null;let t=JSON.parse(e);return t.canExpire&&new Date().getTime()/1e3>t.expireAt?(localStorage.removeItem(r),null):t.value}return null}static clearAll(){n.isBrowser()&&(localStorage.clear(),console.log("LocalStorageUtil: Localstorage has been cleared"))}static clear(r){n.isBrowser()&&(localStorage.removeItem(r),console.log(`LocalStorageUtil: ${r} has been removed from localstorage`))}};var s=class s{constructor(r,e){this.http=r,this.jwtHelper=e,this.baseUrl=`${u.apiUrl}/${u.apiPrefix}/${u.apiVersion}/auth`}register(r){return this.http.post(`${this.baseUrl}/register`,r)}login(r){return this.http.post(`${this.baseUrl}/login`,r)}logout(){return this.http.post(`${this.baseUrl}/logout`,{})}refresh(){return this.http.post(`${this.baseUrl}/refresh`,{})}getUserProfile(){return this.http.post(`${this.baseUrl}/user-profile`,{})}authenticate(r,e,t=1800){o.Supported()&&(o.set("token",e,t),o.set("user",JSON.stringify(r),t))}getUser(){if(o.Supported()&&o.get("token")&&o.get("user")){let r=o.get("user");return JSON.parse(r)}return null}getUserId(){let r=this.getUser();return r?r.id:""}isAuthenticated(){if(o.Supported()){let r=o.get("token");return!this.jwtHelper.isTokenExpired(r)}return!1}getToken(){return o.get("token")}isLoggedIn(){return this.isAuthenticated()}clearStorage(){o.Supported()&&(o.clear("remember"),o.clear("token"),o.clear("user"))}redirectToHome(r){r.navigate(["/"])}static jwtHelperServiceFactory(){return new c}};s.jwtHelperServiceProvider={provide:c,useFactory:s.jwtHelperServiceFactory},s.authServiceAndJwtHelperServiceProviders=[s,s.jwtHelperServiceProvider],s.\u0275fac=function(e){return new(e||s)(l(m),l(c))},s.\u0275prov=d({token:s,factory:s.\u0275fac,providedIn:"root"});var w=s;export{k as a,c as b,w as c};
