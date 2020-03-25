(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["admin.dashboard"],{"0a6e":function(t,e,r){"use strict";r.r(e);var a=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"viewContainer"},[r("v-container",[r("v-row",[r("v-col",{attrs:{cols:"12",sm:"6",md:"6",lg:"4",xl:"3"}},[r("v-hover",{scopedSlots:t._u([{key:"default",fn:function(e){var a=e.hover;return[r("v-card",[null===t.stats?r("v-skeleton-loader",{attrs:{type:"card"}}):t._e(),null!==t.stats?r("div",[r("v-responsive",{attrs:{"aspect-ratio":2.4}},[r("v-sparkline",{attrs:{labels:t.stats.userChartLabels,value:t.stats.userChartValues,type:"trend",smooth:"","stroke-linecap":"round",color:"grey","line-width":"3",padding:"16",height:"100%"}})],1),r("v-divider"),r("v-card-text",[r("h2",{staticClass:"title"},[t._v(t._s(t.$t("users"))+" "),r("span",[t._v("("+t._s(t.stats.total.users)+")")])]),r("span",{class:{"red--text":t.stats.users.signupsChange<0,"green--text":t.stats.users.signupsChange>0}},[t.stats.users.signupsChange<0?r("v-icon",{class:{"red--text":t.stats.users.signupsChange<0,"green--text":t.stats.users.signupsChange>0},attrs:{size:"14"}},[t._v("mdi-arrow-down")]):t._e(),t.stats.users.signupsChange>0?r("v-icon",{class:{"red--text":t.stats.users.signupsChange<0,"green--text":t.stats.users.signupsChange>0},attrs:{size:"14"}},[t._v("mdi-arrow-up")]):t._e(),t._v(" "+t._s(t.formatNumber(t.stats.users.signupsChange))+" ")],1),t._v(" "+t._s(t.$t("vs_past_7_days"))+" ")])],1):t._e(),r("v-fade-transition",[a?r("v-overlay",{attrs:{absolute:""}},[r("v-btn",{attrs:{"x-large":"",rounded:"",to:{name:"admin.users"},color:"primary"}},[t._v(t._s(t.$t("more"))+" "),r("v-icon",{staticClass:"ml-1",attrs:{size:"15"}},[t._v("mdi-arrow-right")])],1)],1):t._e()],1)],1)]}}])})],1),r("v-col",{attrs:{cols:"12",sm:"6",md:"6",lg:"4",xl:"3"}},[r("v-hover",{scopedSlots:t._u([{key:"default",fn:function(e){var a=e.hover;return[r("v-card",[null===t.stats?r("v-skeleton-loader",{attrs:{type:"card"}}):t._e(),null!==t.stats?r("div",[r("v-responsive",{staticClass:"d-flex align-center",attrs:{"aspect-ratio":2.4,align:"center"}},[r("v-icon",{attrs:{size:"96",color:"grey"}},[t._v("mdi-account-circle")])],1),r("v-divider"),r("v-card-text",[r("h2",{staticClass:"title"},[t._v(t._s(t.$t("profile")))]),t._v(" "+t._s(t.$t("profile_info"))+" ")])],1):t._e(),r("v-fade-transition",[a?r("v-overlay",{attrs:{absolute:""}},[r("v-btn",{attrs:{"x-large":"",rounded:"",to:{name:"profile"},color:"primary"}},[t._v(t._s(t.$t("more"))+" "),r("v-icon",{staticClass:"ml-1",attrs:{size:"15"}},[t._v("mdi-arrow-right")])],1)],1):t._e()],1)],1)]}}])})],1),r("v-col",{attrs:{cols:"12",sm:"6",md:"6",lg:"4",xl:"3"}},[r("v-card",[null===t.stats?r("v-skeleton-loader",{attrs:{type:"card"}}):t._e(),null!==t.stats?r("div",[r("v-responsive",{staticClass:"d-flex align-center",attrs:{"aspect-ratio":2.4,align:"center"}},[r("v-icon",{attrs:{size:"96",color:"grey"}},[t._v("mdi-information-outline")])],1),r("v-divider"),r("v-card-text",[r("h2",{staticClass:"title"},[t._v(t._s(t.$t("version"))+" "+t._s(t.stats.version))]),t._v(" "+t._s(t.$t("version_info"))+" ")])],1):t._e()],1)],1)],1)],1)],1)},n=[],i={data:function(){return{locale:"en",stats:null}},created:function(){this.$vuetify.breakpoint.mdAndUp&&this.$store.dispatch("setDashboardDrawer",!0);var t=Intl.DateTimeFormat().resolvedOptions().locale||"en";t=this.$auth.check()?this.$auth.user().locale:t,this.locale=t,this.moment.locale(this.locale.substr(0,2)),this.loadStats()},methods:{loadStats:function(){var t=this;this.axios.get("/admin/stats",{params:{locale:this.$i18n.locale}}).then((function(e){var r=e.data;t.stats=r;var a=t.$_.map(r.users.signupsCurrentPeriod,(function(e,r){return t.moment(r).format("D")})),n=t.$_.map(r.users.signupsCurrentPeriod,(function(t,e){return t}));t.stats.userChartLabels=a,t.stats.userChartValues=n,t.overlay=!1,t.loading=!1}))},formatNumber:function(t){return new Intl.NumberFormat(this.locale.replace("_","-")).format(t)}}},s=i,o=r("2877"),l=r("6544"),u=r.n(l),c=r("8336"),h=r("b0af"),d=r("99d9"),p=r("62ad"),f=r("a523"),y=r("ce7e"),g=r("0789"),m=r("ce87"),v=r("132d"),b=r("a797"),x=r("6b53"),_=r("0fd9"),S=(r("1f09"),r("c995")),w=r("24b2"),O=r("7560"),$=r("58df"),k=r("80d2");function C(t,e){return B(t)||P(t,e)||L(t,e)||j()}function j(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function L(t,e){if(t){if("string"===typeof t)return D(t,e);var r=Object.prototype.toString.call(t).slice(8,-1);return"Object"===r&&t.constructor&&(r=t.constructor.name),"Map"===r||"Set"===r?Array.from(r):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?D(t,e):void 0}}function D(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,a=new Array(e);r<e;r++)a[r]=t[r];return a}function P(t,e){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(t)){var r=[],a=!0,n=!1,i=void 0;try{for(var s,o=t[Symbol.iterator]();!(a=(s=o.next()).done);a=!0)if(r.push(s.value),e&&r.length===e)break}catch(l){n=!0,i=l}finally{try{a||null==o["return"]||o["return"]()}finally{if(n)throw i}}return r}}function B(t){if(Array.isArray(t))return t}function E(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,a)}return r}function V(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?E(Object(r),!0).forEach((function(e){M(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):E(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function M(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}var z=Object($["a"])(S["a"],w["a"],O["a"]).extend({name:"VSkeletonLoader",props:{boilerplate:Boolean,loading:Boolean,tile:Boolean,transition:String,type:String,types:{type:Object,default:function(){return{}}}},computed:{attrs:function(){return this.isLoading?this.boilerplate?{}:V({"aria-busy":!0,"aria-live":"polite",role:"alert"},this.$attrs):this.$attrs},classes:function(){return V({"v-skeleton-loader--boilerplate":this.boilerplate,"v-skeleton-loader--is-loading":this.isLoading,"v-skeleton-loader--tile":this.tile},this.themeClasses,{},this.elevationClasses)},isLoading:function(){return!("default"in this.$scopedSlots)||this.loading},rootTypes:function(){return V({actions:"button@2",article:"heading, paragraph",avatar:"avatar",button:"button",card:"image, card-heading","card-avatar":"image, list-item-avatar","card-heading":"heading",chip:"chip","date-picker":"list-item, card-heading, divider, date-picker-options, date-picker-days, actions","date-picker-options":"text, avatar@2","date-picker-days":"avatar@28",heading:"heading",image:"image","list-item":"text","list-item-avatar":"avatar, text","list-item-two-line":"sentences","list-item-avatar-two-line":"avatar, sentences","list-item-three-line":"paragraph","list-item-avatar-three-line":"avatar, paragraph",paragraph:"text@3",sentences:"text@2",table:"table-heading, table-thead, table-tbody, table-tfoot","table-heading":"heading, text","table-thead":"heading@6","table-tbody":"table-row-divider@6","table-row-divider":"table-row, divider","table-row":"table-cell@6","table-cell":"text","table-tfoot":"text@2, avatar@2",text:"text"},this.types)}},methods:{genBone:function(t,e){return this.$createElement("div",{staticClass:"v-skeleton-loader__".concat(t," v-skeleton-loader__bone")},e)},genBones:function(t){var e=this,r=t.split("@"),a=C(r,2),n=a[0],i=a[1],s=function(){return e.genStructure(n)};return Array.from({length:i}).map(s)},genStructure:function(t){var e=[];t=t||this.type||"";var r=this.rootTypes[t]||"";if(t===r);else{if(t.indexOf(",")>-1)return this.mapBones(t);if(t.indexOf("@")>-1)return this.genBones(t);r.indexOf(",")>-1?e=this.mapBones(r):r.indexOf("@")>-1?e=this.genBones(r):r&&e.push(this.genStructure(r))}return[this.genBone(t,e)]},genSkeleton:function(){var t=[];return this.isLoading?t.push(this.genStructure()):t.push(Object(k["q"])(this)),this.transition?this.$createElement("transition",{props:{name:this.transition},on:{afterEnter:this.resetStyles,beforeEnter:this.onBeforeEnter,beforeLeave:this.onBeforeLeave,leaveCancelled:this.resetStyles}},t):t},mapBones:function(t){return t.replace(/\s/g,"").split(",").map(this.genStructure)},onBeforeEnter:function(t){this.resetStyles(t),this.isLoading&&(t._initialStyle={display:t.style.display,transition:t.style.transition},t.style.setProperty("transition","none","important"))},onBeforeLeave:function(t){t.style.setProperty("display","none","important")},resetStyles:function(t){t._initialStyle&&(t.style.display=t._initialStyle.display||"",t.style.transition=t._initialStyle.transition,delete t._initialStyle)}},render:function(t){return t("div",{staticClass:"v-skeleton-loader",attrs:this.attrs,on:this.$listeners,class:this.classes,style:this.isLoading?this.measurableStyles:void 0},[this.genSkeleton()])}}),A=r("a9ad");function W(t){return Y(t)||T(t)||I(t)||N()}function N(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}function I(t,e){if(t){if("string"===typeof t)return H(t,e);var r=Object.prototype.toString.call(t).slice(8,-1);return"Object"===r&&t.constructor&&(r=t.constructor.name),"Map"===r||"Set"===r?Array.from(r):"Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)?H(t,e):void 0}}function T(t){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}function Y(t){if(Array.isArray(t))return H(t)}function H(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,a=new Array(e);r<e;r++)a[r]=t[r];return a}function X(t,e){var r=e.minX,a=e.maxX,n=e.minY,i=e.maxY,s=t.length,o=Math.max.apply(Math,W(t)),l=Math.min.apply(Math,W(t)),u=(a-r)/(s-1),c=(i-n)/(o-l||1);return t.map((function(t,e){return{x:r+e*u,y:i-(t-l)*c+1e-5*+(e===s-1)-1e-5*+(0===e),value:t}}))}function G(t,e){var r=e.minX,a=e.maxX,n=e.minY,i=e.maxY,s=t.length,o=Math.max.apply(Math,W(t)),l=Math.min.apply(Math,W(t));l>0&&(l=0),o<0&&(o=0);var u=a/s,c=(i-n)/(o-l),h=i-Math.abs(l*c);return t.map((function(t,e){var a=Math.abs(c*t);return{x:r+e*u,y:h-a+ +(t<0)*a,height:a,value:t}}))}function F(t){return parseInt(t,10)}function R(t,e,r){return F(t.x+r.x)===F(2*e.x)&&F(t.y+r.y)===F(2*e.y)}function q(t,e){return Math.sqrt(Math.pow(e.x-t.x,2)+Math.pow(e.y-t.y,2))}function U(t,e,r){var a={x:t.x-e.x,y:t.y-e.y},n=Math.sqrt(a.x*a.x+a.y*a.y),i={x:a.x/n,y:a.y/n};return{x:e.x+i.x*r,y:e.y+i.y*r}}function J(t,e){var r=arguments.length>2&&void 0!==arguments[2]&&arguments[2],a=arguments.length>3&&void 0!==arguments[3]?arguments[3]:75,n=t.shift(),i=t[t.length-1];return(r?"M".concat(n.x," ").concat(a-n.x+2," L").concat(n.x," ").concat(n.y):"M".concat(n.x," ").concat(n.y))+t.map((function(r,a){var i=t[a+1],s=t[a-1]||n,o=i&&R(i,r,s);if(!i||o)return"L".concat(r.x," ").concat(r.y);var l=Math.min(q(s,r),q(i,r)),u=l/2<e,c=u?l/2:e,h=U(s,r,c),d=U(i,r,c);return"L".concat(h.x," ").concat(h.y,"S").concat(r.x," ").concat(r.y," ").concat(d.x," ").concat(d.y)})).join("")+(r?"L".concat(i.x," ").concat(a-n.x+2," Z"):"")}function Z(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,a)}return r}function K(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?Z(Object(r),!0).forEach((function(e){Q(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):Z(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function Q(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}function tt(t){return tt="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"===typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},tt(t)}var et=Object($["a"])(A["a"]).extend({name:"VSparkline",inheritAttrs:!1,props:{autoDraw:Boolean,autoDrawDuration:{type:Number,default:2e3},autoDrawEasing:{type:String,default:"ease"},autoLineWidth:{type:Boolean,default:!1},color:{type:String,default:"primary"},fill:{type:Boolean,default:!1},gradient:{type:Array,default:function(){return[]}},gradientDirection:{type:String,validator:function(t){return["top","bottom","left","right"].includes(t)},default:"top"},height:{type:[String,Number],default:75},labels:{type:Array,default:function(){return[]}},labelSize:{type:[Number,String],default:7},lineWidth:{type:[String,Number],default:4},padding:{type:[String,Number],default:8},showLabels:Boolean,smooth:{type:[Boolean,Number,String],default:!1},type:{type:String,default:"trend",validator:function(t){return["trend","bar"].includes(t)}},value:{type:Array,default:function(){return[]}},width:{type:[Number,String],default:300}},data:function(){return{lastLength:0}},computed:{parsedPadding:function(){return Number(this.padding)},parsedWidth:function(){return Number(this.width)},parsedHeight:function(){return parseInt(this.height,10)},parsedLabelSize:function(){return parseInt(this.labelSize,10)||7},totalHeight:function(){var t=this.parsedHeight;return this.hasLabels&&(t+=1.5*parseInt(this.labelSize,10)),t},totalWidth:function(){var t=this.parsedWidth;return"bar"===this.type&&(t=Math.max(this.value.length*this._lineWidth,t)),t},totalValues:function(){return this.value.length},_lineWidth:function(){if(this.autoLineWidth&&"trend"!==this.type){var t=this.parsedPadding*(this.totalValues+1);return(this.parsedWidth-t)/this.totalValues}return parseFloat(this.lineWidth)||4},boundary:function(){if("bar"===this.type)return{minX:0,maxX:this.totalWidth,minY:0,maxY:this.parsedHeight};var t=this.parsedPadding;return{minX:t,maxX:this.totalWidth-t,minY:t,maxY:this.parsedHeight-t}},hasLabels:function(){return Boolean(this.showLabels||this.labels.length>0||this.$scopedSlots.label)},parsedLabels:function(){for(var t=[],e=this._values,r=e.length,a=0;t.length<r;a++){var n=e[a],i=this.labels[a];i||(i="object"===tt(n)?n.value:n),t.push({x:n.x,value:String(i)})}return t},normalizedValues:function(){return this.value.map((function(t){return"number"===typeof t?t:t.value}))},_values:function(){return"trend"===this.type?X(this.normalizedValues,this.boundary):G(this.normalizedValues,this.boundary)},textY:function(){var t=this.parsedHeight;return"trend"===this.type&&(t-=4),t},_radius:function(){return!0===this.smooth?8:Number(this.smooth)}},watch:{value:{immediate:!0,handler:function(){var t=this;this.$nextTick((function(){if(t.autoDraw&&"bar"!==t.type&&t.$refs.path){var e=t.$refs.path,r=e.getTotalLength();t.fill?(e.style.transformOrigin="bottom center",e.style.transition="none",e.style.transform="scaleY(0)",e.getBoundingClientRect(),e.style.transition="transform ".concat(t.autoDrawDuration,"ms ").concat(t.autoDrawEasing),e.style.transform="scaleY(1)"):(e.style.transition="none",e.style.strokeDasharray=r+" "+r,e.style.strokeDashoffset=Math.abs(r-(t.lastLength||0)).toString(),e.getBoundingClientRect(),e.style.transition="stroke-dashoffset ".concat(t.autoDrawDuration,"ms ").concat(t.autoDrawEasing),e.style.strokeDashoffset="0"),t.lastLength=r}}))}}},methods:{genGradient:function(){var t=this,e=this.gradientDirection,r=this.gradient.slice();r.length||r.push("");var a=Math.max(r.length-1,1),n=r.reverse().map((function(e,r){return t.$createElement("stop",{attrs:{offset:r/a,"stop-color":e||t.color||"currentColor"}})}));return this.$createElement("defs",[this.$createElement("linearGradient",{attrs:{id:this._uid,x1:+("left"===e),y1:+("top"===e),x2:+("right"===e),y2:+("bottom"===e)}},n)])},genG:function(t){return this.$createElement("g",{style:{fontSize:"8",textAnchor:"middle",dominantBaseline:"mathematical",fill:this.color||"currentColor"}},t)},genPath:function(){var t=X(this.normalizedValues,this.boundary);return this.$createElement("path",{attrs:{id:this._uid,d:J(t,this._radius,this.fill,this.parsedHeight),fill:this.fill?"url(#".concat(this._uid,")"):"none",stroke:this.fill?"none":"url(#".concat(this._uid,")")},ref:"path"})},genLabels:function(t){var e=this,r=this.parsedLabels.map((function(r,a){return e.$createElement("text",{attrs:{x:r.x+t+e._lineWidth/2,y:e.textY+.75*e.parsedLabelSize,"font-size":Number(e.labelSize)||7}},[e.genLabel(r,a)])}));return this.genG(r)},genLabel:function(t,e){return this.$scopedSlots.label?this.$scopedSlots.label({index:e,value:t.value}):t.value},genBars:function(){if(this.value&&!(this.totalValues<2)){var t=G(this.normalizedValues,this.boundary),e=(Math.abs(t[0].x-t[1].x)-this._lineWidth)/2;return this.$createElement("svg",{attrs:{display:"block",viewBox:"0 0 ".concat(this.totalWidth," ").concat(this.totalHeight)}},[this.genGradient(),this.genClipPath(t,e,this._lineWidth,"sparkline-bar-"+this._uid),this.hasLabels?this.genLabels(e):void 0,this.$createElement("g",{attrs:{"clip-path":"url(#sparkline-bar-".concat(this._uid,"-clip)"),fill:"url(#".concat(this._uid,")")}},[this.$createElement("rect",{attrs:{x:0,y:0,width:this.totalWidth,height:this.height}})])])}},genClipPath:function(t,e,r,a){var n=this,i="number"===typeof this.smooth?this.smooth:this.smooth?2:0;return this.$createElement("clipPath",{attrs:{id:"".concat(a,"-clip")}},t.map((function(t){return n.$createElement("rect",{attrs:{x:t.x+e,y:t.y,width:r,height:t.height,rx:i,ry:i}},[n.autoDraw?n.$createElement("animate",{attrs:{attributeName:"height",from:0,to:t.height,dur:"".concat(n.autoDrawDuration,"ms"),fill:"freeze"}}):void 0])})))},genTrend:function(){return this.$createElement("svg",this.setTextColor(this.color,{attrs:K({},this.$attrs,{display:"block","stroke-width":this._lineWidth||1,viewBox:"0 0 ".concat(this.width," ").concat(this.totalHeight)})}),[this.genGradient(),this.hasLabels&&this.genLabels(-this._lineWidth/2),this.genPath()])}},render:function(t){if(!(this.totalValues<2))return"trend"===this.type?this.genTrend():this.genBars()}}),rt=Object(o["a"])(s,a,n,!1,null,null,null);e["default"]=rt.exports;u()(rt,{VBtn:c["a"],VCard:h["a"],VCardText:d["b"],VCol:p["a"],VContainer:f["a"],VDivider:y["a"],VFadeTransition:g["c"],VHover:m["a"],VIcon:v["a"],VOverlay:b["a"],VResponsive:x["a"],VRow:_["a"],VSkeletonLoader:z,VSparkline:et})},"1f09":function(t,e,r){}}]);
//# sourceMappingURL=admin.dashboard.15615bf8.js.map