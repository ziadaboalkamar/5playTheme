import{_ as g}from"./js/_plugin-vue_export-helper.bd508f36.js";import{r as c,o as r,c as l,a as p,F as b,h as v,n as y,i as C,d as u,C as S,b as d,g as P,t as m,w,e as I,E as T}from"./js/vue.runtime.esm-bundler.a49acf4f.js";import{l as x}from"./js/index.68062296.js";import{l as E}from"./js/index.c885ab17.js";import{l as k}from"./js/index.0b123ab1.js";import{u as A,f as L,l as N}from"./js/links.96235554.js";import{C as B}from"./js/Card.02715b2c.js";import{C as $}from"./js/SocialProfiles.fee23c2d.js";import{S as D}from"./js/LogoGear.8c7ffea6.js";import{e as F}from"./js/elemLoaded.9a6eb745.js";import"./js/translations.93cb7f26.js";import"./js/default-i18n.cc9dbff0.js";import"./js/constants.c7984802.js";import"./js/Caret.7f04b0aa.js";import"./js/isArrayLikeObject.3ade28da.js";import"./js/Tooltip.c031cb66.js";import"./js/Slide.f53bd663.js";import"./js/Checkbox.fc67f607.js";import"./js/Checkmark.1b968f7a.js";import"./js/Textarea.0fbcf1ab.js";import"./js/SettingsRow.5de01aba.js";import"./js/Row.85168b37.js";/* empty css                                               */import"./js/Twitter.e2f1f21e.js";const R={setup(){return{rootStore:A(),settingsStore:L()}},components:{CoreCard:B,CoreSocialProfiles:$,SvgLogoGear:D},data(){return{activeTabIndex:0,strings:{socialProfiles:this.$t.__("Social Profiles",this.$td),description:this.$t.__("To let search engines know which profiles are associated with this user, enter them below:",this.$td)}}},methods:{setActiveTab(e){const o=this.activeTabIndex;switch(this.activeTabIndex=e,this.activeTabObject.slug){case"personal-options":document.getElementById("your-profile").childNodes.forEach(t=>{t.style&&(t.style.display="block")});break;case"social-profiles":document.getElementById("your-profile").childNodes.forEach(t=>{t.id==="aioseo-user-profile-tab"||t.className==="submit"||!t.style||(t.style.display="none")});break;case"customer-data":this.activeTabIndex=o,window.location.href=this.rootStore.aioseo.urls.home+"/wp-admin/admin.php?page=followup-emails-reports&tab=reportuser_view&email="+encodeURIComponent(this.settingsStore.userProfile.userData.user_email)+"&user_id="+this.settingsStore.userProfile.userData.ID;break}},updateHiddenInputField(e){document.getElementById("aioseo-user-social-profiles").value=JSON.stringify(e)}},computed:{tabs(){const e=[{label:this.$t.__("Personal Options",this.$td),slug:"personal-options"},{label:this.$t.__("Social Profiles",this.$td),slug:"social-profiles",svg:"svg-logo-gear"}];return this.settingsStore.userProfile.isWooCommerceFollowupEmailsActive&&e.push({label:this.$t.__("Customer Data",this.$td),slug:"customer-data"}),e},activeTabObject(){return this.tabs[this.activeTabIndex]}},async created(){this.updateHiddenInputField(this.settingsStore.userProfile.profiles)},mounted(){const e=new URLSearchParams(window.location.search);e&&e.get("social-profiles")&&this.setActiveTab(1)}},U={id:"aioseo-user-profile-tab"},V={class:"navigation-bar"},H=["onClick"],O={class:"aioseo-settings-row aioseo-section-description"};function G(e,o,t,f,a,i){const h=c("CoreSocialProfiles"),_=c("CoreCard");return r(),l("div",U,[p("div",V,[(r(!0),l(b,null,v(i.tabs,(s,n)=>(r(),l("a",{key:n,class:y({active:n===a.activeTabIndex}),href:"#",onClick:C(J=>i.setActiveTab(n),["prevent"])},[s.svg?(r(),u(S(s.svg),{key:0})):d("",!0),P(" "+m(s.label),1)],10,H))),128))]),i.activeTabObject.slug==="social-profiles"?(r(),u(_,{key:0,slug:"userProfiles","header-text":a.strings.socialProfiles,"no-slide":"",toggles:!1},{default:w(()=>[p("div",O,m(a.strings.description),1),I(h,{userProfiles:f.settingsStore.userProfile.profiles,onUpdated:o[0]||(o[0]=s=>i.updateHiddenInputField(s))},null,8,["userProfiles"])]),_:1},8,["header-text"])):d("",!0)])}const j=g(R,[["render",G]]),q=()=>{const e=document.getElementById("your-profile");if(!e)return;const o=document.createElement("div");o.id="aioseo-user-profile-tab";const t=document.createElement("input");t.id="aioseo-user-social-profiles",t.name="aioseo-user-social-profiles",t.type="hidden",e.prepend(t),e.prepend(o)},z=()=>{let e=T({...j,name:"Standalone/UserProfileTab"});e=x(e),e=E(e),e=k(e),N(e),e.mount("#aioseo-user-profile-tab")};F("#your-profile","profilePageLoaded");document.addEventListener("animationstart",function(e){e.animationName==="profilePageLoaded"&&(q(),z())});