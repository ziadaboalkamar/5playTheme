import{_ as D}from"./js/_plugin-vue_export-helper.bd508f36.js";import{o as p,c as g,f as S,b as y,r as u,d as q,w as O,a,n as T,i as R,t as N,e as E,E as U,x as W}from"./js/vue.runtime.esm-bundler.a49acf4f.js";import{c as V,a as X}from"./js/vue-router.ef051ae5.js";import{l as Y}from"./js/index.68062296.js";import{l as z}from"./js/index.c885ab17.js";import{k as F,b as H,l as I}from"./js/links.96235554.js";import{a as Q,m as G,T as J}from"./js/postContent.314c716d.js";import{d as f,a as K}from"./js/Caret.7f04b0aa.js";import{b as Z}from"./js/_baseSet.fca5aa57.js";import{i as j}from"./js/isEqual.56699969.js";import{A as tt}from"./js/App.f3e1672e.js";import"./js/translations.93cb7f26.js";import"./js/default-i18n.cc9dbff0.js";import"./js/constants.c7984802.js";import"./js/isArrayLikeObject.3ade28da.js";import"./js/cleanForSlug.77e4c771.js";import"./js/toString.d05e92a1.js";import"./js/get.80957652.js";import"./js/_stringToArray.4de3b1f3.js";import"./js/html.671743b1.js";import"./js/_baseIsEqual.f4e39637.js";import"./js/_getAllKeys.9b0cdc6c.js";import"./js/_getTag.e9f54dd1.js";/* empty css                */import"./js/allowed.d054e5c0.js";import"./js/params.597cd0f5.js";import"./js/SeoRevisions.fd5dbff2.js";/* empty css                                               *//* empty css                                                 */import"./js/JsonValues.870a4901.js";/* empty css                                                 */import"./js/SettingsRow.5de01aba.js";import"./js/Row.85168b37.js";import"./js/Checkbox.fc67f607.js";import"./js/Checkmark.1b968f7a.js";import"./js/LicenseKeyBar.42b79cab.js";import"./js/LogoGear.8c7ffea6.js";import"./js/Tabs.b92dcfbe.js";import"./js/TruSeoScore.25c11e71.js";import"./js/SaveChanges.dad94bb3.js";import"./js/Information.73bebac2.js";import"./js/Slide.f53bd663.js";import"./js/Portal.72ce36ea.js";import"./js/Index.9d3d0eb4.js";import"./js/MaxCounts.12b45bab.js";import"./js/Tags.37458114.js";import"./js/tags.7148445d.js";import"./js/Tooltip.c031cb66.js";import"./js/Plus.aa60dda7.js";import"./js/Editor.29931065.js";import"./js/Blur.f2dbe3c4.js";import"./js/RadioToggle.366d0fc8.js";import"./js/GoogleSearchPreview.c9eff603.js";import"./js/HtmlTagsEditor.e6d6fa05.js";import"./js/UnfilteredHtml.e1d2e179.js";import"./js/popup.b60b699f.js";import"./js/addons.8f1a852a.js";import"./js/upperFirst.0bd33a97.js";import"./js/Index.86dc50d3.js";import"./js/WpTable.cbf26363.js";import"./js/Table.9ab1ff6b.js";import"./js/numbers.8fa607e7.js";import"./js/PostTypes.9ab32454.js";import"./js/InternalOutbound.d0ac4a34.js";import"./js/RequiredPlans.cbd60891.js";import"./js/license.2537a586.js";import"./js/Image.8dc068b7.js";import"./js/Img.0e297167.js";import"./js/FacebookPreview.1d9f18ba.js";import"./js/Profile.ec7b99d8.js";import"./js/TwitterPreview.fe4140a7.js";import"./js/Book.666e0177.js";import"./js/Settings.aa220ff8.js";import"./js/Build.e4149d45.js";import"./js/Redirects.79b051e9.js";import"./js/Index.820bfccd.js";import"./js/strings.f1aab73e.js";import"./js/isString.e1a5f7c9.js";import"./js/ProBadge.269c99d4.js";import"./js/External.db175325.js";import"./js/Exclamation.cce5234f.js";import"./js/Gear.7e798dc4.js";import"./js/Card.02715b2c.js";import"./js/Eye.5a8f97a6.js";import"./js/UserAvatar.374a3d02.js";import"./js/Upsell.e927e0a2.js";function w(t,e,s){return t==null?t:Z(t,e,s)}const C=t=>t.parentElement.removeChild(t),k=()=>{const t=_();document.body.classList.toggle("aioseo-settings-bar-is-active",t),document.body.classList.toggle("aioseo-settings-bar-is-inactive",!t)},et=()=>{const t=h();c(document.body,"aioseo-settings-bar-is"),document.body.classList.add(`aioseo-settings-bar-is-${t}`),b(t)},ot=t=>{const e=document.getElementById(t);return e.contentWindow?e.contentWindow.document:e.contentDocument},st=()=>{m.addEventListener("change",()=>{M(),b(h())}),ct.observe(document.querySelector(".et-fb-page-settings-bar"),{attributeFilter:["class"]}),document.addEventListener("click",B),ot("et-fb-app-frame").addEventListener("click",B),o.addEventListener("click",()=>{const t=new Event("aioseo-divi-toggle-modal");document.dispatchEvent(t)})},it=()=>{const t=h();c(document.body,"aioseo-settings-bar-is"),document.body.classList.add(`aioseo-settings-bar-is-${t}`),k(),M(),at()||b(t)},M=()=>{x()&&(o=C(o))},b=t=>{if(x())return;const e=document.querySelector(".et-fb-page-settings-bar"),s=e.querySelector(".et-fb-page-settings-bar__toggle-button"),n=e.querySelectorAll(".et-fb-page-settings-bar__column");if(nt(t),_())if(m.matches){const i=[...n].filter(r=>r.classList.contains("et-fb-page-settings-bar__column--main"));i.length&&i[0].appendChild(o)}else{const i=[...n].filter(r=>r.classList.contains("et-fb-page-settings-bar__column--left"));i.length&&i[0].insertBefore(o)}else s.insertAdjacentElement("afterend",o)},nt=t=>{c(o,"aioseo-settings-bar-root"),o.classList.add(`aioseo-settings-bar-root-${t}`),c(o,"aioseo-settings-bar-root-is-mobile"),["aioseo-settings-bar-root-is-mobile",`aioseo-settings-bar-root-is-mobile-${t}`].forEach(n=>{o.classList.toggle(n,!m.matches)}),c(o,"aioseo-settings-bar-root-is-desktop"),["aioseo-settings-bar-root-is-desktop",`aioseo-settings-bar-root-is-desktop-${t}`].forEach(n=>{o.classList.toggle(n,m.matches)})},c=(t,e)=>{const s=[`${e}-left`,`${e}-right`,`${e}-top`,`${e}-top-left`,`${e}-top-right`,`${e}-bottom`,`${e}-bottom-left`,`${e}-bottom-right`];t.classList.remove(...s)},h=()=>{const t=document.querySelector(".et-fb-page-settings-bar").classList;return t.contains("et-fb-page-settings-bar--horizontal")&&!t.contains("et-fb-page-settings-bar--top")?"bottom":t.contains("et-fb-page-settings-bar--top")&&!t.contains("et-fb-page-settings-bar--corner")?"top":t.contains("et-fb-page-settings-bar--bottom-corner")?t.contains("et-fb-page-settings-bar--left-corner")?"bottom-left":"bottom-right":t.contains("et-fb-page-settings-bar--top-corner")?t.contains("et-fb-page-settings-bar--left-corner")?"top-left":"top-right":t.contains("et-fb-page-settings-bar--vertical--right")?"right":t.contains("et-fb-page-settings-bar--vertical--left")?"left":""},B=t=>{if(!rt())return;const e=t.target,s=".aioseo-modal",n=".aioseo-app.aioseo-post-settings-modal";if(!e.closest(s)&&!e.closest(n)&&!(e!==document.querySelector(s)&&e.contains(document.querySelector(s)))&&e.getAttribute("class")&&!e.getAttribute("class").includes("aioseo")&&e!==o){const i=new Event("aioseo-divi-toggle-modal",{open:!1});document.dispatchEvent(i)}},rt=()=>!document.querySelector(".aioseo-modal").classList.contains("aioseo-modal-is-closed"),x=()=>document.documentElement!==o&&document.documentElement.contains(o),_=()=>document.querySelector(".et-fb-page-settings-bar").classList.contains("et-fb-page-settings-bar--active"),at=()=>document.querySelector(".et-fb-page-settings-bar").classList.contains("et-fb-page-settings-bar--dragged")&&!_(),m=window.matchMedia("(min-width: 768px)"),ct=new MutationObserver(it),lt="#aioseo-settings";let o=document.querySelector(lt);o=C(o);const pt=()=>{k(),et(),st()};let $={};const l=()=>{const t={...$},e=Q();j(t,e)||($=e,G())},mt=()=>{const t=F();t.saveCurrentPost(t.currentPost).then(()=>{H().fetch()})},dt=()=>{l(),window.addEventListener("message",t=>{t.data.eventType==="et_fb_section_content_change"&&f(l,1e3)}),window.wp&&window.wp.hooks.addFilter("et.builder.store.setting.update","aioseo",(t,e)=>{if(t)switch(e){case"et_pb_post_settings_title":w(ETBuilderBackendDynamic,"postTitle",t),f(l,1e3);break;case"et_pb_post_settings_excerpt":w(ETBuilderBackendDynamic,"postMeta.post_excerpt",t),f(l,1e3);break}return t}),document.querySelector(".et-fb-button--save-draft, .et-fb-button--publish").addEventListener("click",mt)};const gt={props:{completelyDraggable:{type:Boolean,default(){return!0}}},data(){return{position1:0,position2:0,position3:0,position4:0}},methods:{dragMouseDown(t){t=t||window.event,t.preventDefault(),this.position3=t.clientX,this.position4=t.clientY,document.onmousemove=this.elementDrag,document.onmouseup=this.closeDragElement},elementDrag(t){t=t||window.event,t.preventDefault(),this.position1=this.position3-t.clientX,this.position2=this.position4-t.clientY,this.position3=t.clientX,this.position4=t.clientY,this.$el.style.top=this.$el.offsetTop-this.position2+"px",this.$el.style.left=this.$el.offsetLeft-this.position1+"px"},closeDragElement(){document.onmouseup=null,document.onmousemove=null}}},ut={class:"aioseo-draggable"},ft={key:1};function bt(t,e,s,n,i,r){return p(),g("div",ut,[s.completelyDraggable?(p(),g("div",{key:0,"on:dragMouseDown":e[0]||(e[0]=(...d)=>r.dragMouseDown&&r.dragMouseDown(...d))},[S(t.$slots,"default")],32)):y("",!0),s.completelyDraggable?y("",!0):(p(),g("div",ft,[S(t.$slots,"default")]))])}const ht=D(gt,[["render",bt]]),_t={components:{PostSettings:tt,SvgClose:K,UtilDraggable:ht},data(){return{isOpen:!1,strings:{header:this.$t.sprintf(this.$t.__("%1$s settings",this.$td),"All in One SEO")}}},methods:{toggleModal(){this.isOpen=!this.isOpen}},beforeUnmount(){document.removeEventListener("aioseo-divi-toggle-modal",this.toggleModal)},mounted(){this.$nextTick(function(){document.addEventListener("aioseo-divi-toggle-modal",this.toggleModal)})}},vt={class:"aioseo-modal-header-title"},St={class:"aioseo-modal-body edit-post-sidebar"};function yt(t,e,s,n,i,r){const d=u("svg-close"),P=u("PostSettings"),A=u("util-draggable");return p(),q(A,{ref:"modal-container",completelyDraggable:!1},{default:O(()=>[a("div",{class:T(["aioseo-modal",{"aioseo-modal-is-closed":!i.isOpen}])},[a("div",{class:"aioseo-modal-header",onMousedown:e[1]||(e[1]=R(v=>t.$refs["modal-container"].dragMouseDown(v),["prevent"]))},[a("div",vt,N(i.strings.header),1),a("div",{class:"aioseo-modal-header-close",onClick:e[0]||(e[0]=v=>i.isOpen=!1)},[E(d)])],32),a("div",St,[E(P)])],2)]),_:1},512)}const L=D(_t,[["render",yt]]),Et=()=>{const t=V({history:X(),routes:[{path:"/",component:L}]});let e=U({name:"Standalone/Divi",data(){return{tableContext:window.aioseo.currentPost.context,screenContext:"sidebar"}},render:()=>W(L)});e=Y(e),e=z(e),e.use(t),t.app=e,I(e,t),e.config.globalProperties.$truSeo=new J,e.mount("#aioseo-app-modal > div")},wt=()=>{pt(),Et(),dt()};window.addEventListener("message",function(t){t.data.eventType==="et_builder_api_ready"&&wt()});