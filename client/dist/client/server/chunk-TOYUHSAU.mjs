import './polyfills.server.mjs';
var s=function(n){return typeof n=="function"},u=function(){function n(){this._subs=[]}return n.prototype.add=function(){for(var t=[],r=0;r<arguments.length;r++)t[r]=arguments[r];this._subs=this._subs.concat(t)},Object.defineProperty(n.prototype,"sink",{set:function(t){this._subs.push(t)},enumerable:!0,configurable:!0}),n.prototype.unsubscribe=function(){this._subs.forEach(function(t){return t&&s(t.unsubscribe)&&t.unsubscribe()}),this._subs=[]},n}();var c={production:!1,apiUrl:"http://localhost:8000",apiPrefix:"api",apiVersion:"v1"};export{c as a,u as b};
