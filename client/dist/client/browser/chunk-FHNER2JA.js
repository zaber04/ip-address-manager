import{a as x,b as H,c as R}from"./chunk-CB7VP26C.js";import{c as B}from"./chunk-CZEYUQQ4.js";import{b as D}from"./chunk-MUKAY4P3.js";import{$ as d,Ea as O,G as n,H as g,Ia as U,M as c,O as m,P as s,Q as l,R as v,S as _,T as b,X as u,Z as L,_ as o,aa as S,ba as P,e as T,fa as E,ga as w,ha as M,ia as I,m as h,na as $,oa as F,pa as j,qa as k,r as C,ra as N,u as y,v as A}from"./chunk-VTQJTHAG.js";function G(e,i){if(e&1&&(s(0,"p",19),o(1),l()),e&2){let t=u().$implicit;n(1),d(t.new_data)}}function J(e,i){if(e&1&&(s(0,"p",19),o(1," from "),s(2,"span",20),o(3),l(),o(4," to "),s(5,"span",21),o(6),l()()),e&2){let t=u().$implicit;n(3),d(t.old_data),n(3),d(t.new_data)}}var K=(e,i)=>({"badge-success":e,"badge-info":i});function Q(e,i){if(e&1&&(s(0,"div",14)(1,"div",15),o(2),l(),m(3,G,2,1,"p",16)(4,J,7,2,"p",16),s(5,"small",17),o(6),s(7,"span",18),o(8),l(),o(9),w(10,"date"),l()()),e&2){let t=i.$implicit,r=u(3);n(1),c("ngClass",E(11,K,t.action=="insert",t.action=="update")),n(1),d(t.action),n(1),c("ngIf",t.action=="insert"),n(1),c("ngIf",t.action=="update"),n(2),S(" ",r.textLabels.createdBy,": "),n(2),P("",t.user==null?null:t.user.last_name," / ",t.user==null?null:t.user.first_name,""),n(1),S(" at ",M(10,8,t.created_at,"MMM d, y, h:mm:ss a"),". ")}}function V(e,i){if(e&1&&(_(0),m(1,Q,11,14,"div",12),v(2,"app-pagination",13),b()),e&2){let t=u(2);n(1),c("ngForOf",t.auditLogList==null||t.auditLogList.data==null?null:t.auditLogList.data.data),n(1),c("baseUrl","/")("currentPageIndex",t.auditLogList==null||t.auditLogList.data==null?null:t.auditLogList.data.current_page)("lastPageIndex",t.auditLogList==null||t.auditLogList.data==null?null:t.auditLogList.data.last_page)("pageLinks",t.auditLogList==null||t.auditLogList.data==null?null:t.auditLogList.data.links)}}function X(e,i){if(e&1&&(s(0,"div",22)(1,"p",23),o(2),l()()),e&2){let t=u(2);n(2),d(t.textLabels.noAuditLogs)}}function Y(e,i){if(e&1&&(_(0),m(1,V,3,5,"ng-container",9)(2,X,3,1,"ng-template",null,11,I),b()),e&2){let t=L(3),r=u();n(1),c("ngIf",((r.auditLogList==null||r.auditLogList.data==null?null:r.auditLogList.data.total)||0)>0)("ngIfElse",t)}}function Z(e,i){if(e&1&&(s(0,"div",24)(1,"p",25),o(2),l(),s(3,"p"),v(4,"i",26),l()()),e&2){let t=u();n(2),d(t.textLabels.pleaseWait)}}var W=(()=>{let i=class i{constructor(r,a,p,f){this.ipHandlerService=r,this.paginationService=a,this.route=p,this.authService=f,this.subscribe=new D,this.auditTrailSubscribe=new T,this.currentPageIndex=1,this.isLoading=!1,this.textLabels={pageTitle:"Audit Trail",createdBy:"Performed by",noAuditLogs:"No audit trail available.",pleaseWait:"Please wait for a while..."}}ngOnInit(){this.subscribe.sink=this.route.queryParams.subscribe(r=>{r.page?(this.isLoading=!0,this.paginationService.ipAddress?.list&&this.currentPageIndex===r.page?this.auditTrails$=this.paginationService.getAuditLogs():(this.currentPageIndex=r.page,this.paginationService.setAuditLogs(null),this.paginationService.setAuditSelectedPageIndex(null),this.auditTrails$=this.ipHandlerService.getAuditTrailsByUserId(this.authService.getUserId(),this.currentPageIndex)),this.auditTrailSubscribe&&this.auditTrailSubscribe.unsubscribe(),this.auditTrailSubscribe=this.auditTrails$.pipe(h(()=>this.isLoading=!1)).subscribe({next:a=>{this.auditLogList=a,this.paginationService.setAuditLogs(this.auditLogList),this.paginationService.setAuditSelectedPageIndex(this.currentPageIndex)},error:({error:a})=>{this.errors=a?.errors}})):(this.isLoading=!0,this.currentPageIndex=1,this.paginationService.auditLog?.list&&this.currentPageIndex===this.paginationService.auditLog.pageSelected?this.auditTrails$=this.paginationService.getAuditLogs():this.auditTrails$=this.ipHandlerService.getAuditTrailsByUserId(this.authService.getUserId(),this.currentPageIndex),this.auditTrailSubscribe&&this.auditTrailSubscribe.unsubscribe(),this.auditTrailSubscribe=this.auditTrails$?.pipe(h(()=>this.isLoading=!1)).subscribe({next:a=>{this.auditLogList=a,this.paginationService.setAuditLogs(this.auditLogList),this.paginationService.setAuditSelectedPageIndex(this.currentPageIndex)},error:({error:a})=>{this.errors=a?.errors}}))})}ngOnDestroy(){this.subscribe.unsubscribe(),this.auditTrailSubscribe.unsubscribe()}};i.\u0275fac=function(a){return new(a||i)(g(H),g(R),g(O),g(B))},i.\u0275cmp=y({type:i,selectors:[["app-audit-trails"]],decls:14,vars:4,consts:[[1,"pageheader","pd-y-25"],[1,"pd-t-5","pd-b-5"],[1,"pd-0","mg-0","tx-20","text-overflow"],[1,"row","clearfix"],[1,"col-md-12","col-lg-12","col-xl-12"],[1,"card","mg-y-10","shadow-1"],[1,"card-header"],[1,"card-header-title"],[1,"card-body","collapse","show"],[4,"ngIf","ngIfElse"],["loading",""],["noAuditLogs",""],["class","pb-1 mb-2",4,"ngFor","ngForOf"],[3,"baseUrl","currentPageIndex","lastPageIndex","pageLinks"],[1,"pb-1","mb-2"],[1,"float-right",3,"ngClass"],["class","text-muted mg-b-0 text-base",4,"ngIf"],[1,"text-muted"],[1,"text-dark"],[1,"text-muted","mg-b-0","text-base"],[1,"badge-secondary"],[1,"badge-primary"],[1,"text-left"],[1,"text-base","text-red-800"],[1,"text-center"],[1,"text-3xl"],[1,"fa","fa-circle-o-notch","fa-spin","text-3xl"]],template:function(a,p){if(a&1&&(s(0,"div",0)(1,"div",1)(2,"h1",2),o(3),l()()(),s(4,"div",3)(5,"div",4)(6,"div",5)(7,"div",6)(8,"h4",7),o(9),l()(),s(10,"div",8),m(11,Y,4,2,"ng-container",9)(12,Z,5,1,"ng-template",null,10,I),l()()()()),a&2){let f=L(13);n(3),d(p.textLabels.pageTitle),n(6),d(p.textLabels.pageTitle),n(2),c("ngIf",p.isLoading===!1)("ngIfElse",f)}},dependencies:[$,F,j,x,k],styles:[".collapse[_ngcontent-%COMP%]{visibility:visible!important}"]});let e=i;return e})();var pt=(()=>{let i=class i{};i.\u0275fac=function(a){return new(a||i)},i.\u0275mod=A({type:i}),i.\u0275inj=C({imports:[N,U.forChild([{path:"",component:W,title:"IP Address Manager - Audit Trail"}]),x]});let e=i;return e})();export{pt as AuditTrailsModule};
