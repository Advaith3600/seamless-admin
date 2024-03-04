import{r as u,a as j,o as $,c as r,b as n,t as d,d as _,w as B,T as D,e as y,f as N,g as c,h as S,v as T,F as U,i as V,j as w,k as E,l as F}from"./common-components-D5IPcBpq.js";const L=["name","value"],A={key:0,class:"absolute top-full left-0 w-full bg-white mt-1 rounded shadow"},M=["placeholder"],O={class:"overflow-auto max-h-48"},q=["onClick"],z={class:"mr-2"},I={key:0,class:"p-2 text-center"},R={__name:"ForeignSelection",props:["type","field","default","column_name","api_endpoint","referenced_table_name","referenced_column_name"],setup(m){const s=m,i=u(!1),p=u(s.default),a=u(""),h=u(null),f=j([]),g={credentials:"same-origin",headers:{Accept:"application/json"}},C=t=>{const e=[];for(const o of Object.keys(t))e.push(`${o}=${t[o]}`);return encodeURI(e.join("&"))},v=()=>{const t={search:a.value,referenced_table_name:s.referenced_table_name,referenced_column_name:s.referenced_column_name};fetch(`${s.api_endpoint}?${C(t)}`,g).then(e=>e.json()).then(e=>f.values=e)},b=()=>{i.value=!0,setTimeout(()=>h.value.focus())};v();const x=t=>{p.value=t.key,a.value="",i.value=!1},k=()=>{i.value=!1,a.value=""};return document.addEventListener("click",k),$(()=>document.removeEventListener("click",k)),(t,e)=>{const o=N("vue-feather");return c(),r("div",{class:"relative",onClick:e[1]||(e[1]=y(()=>{},["stop"]))},[n("input",{type:"hidden",name:m.field,value:p.value},null,8,L),n("div",{class:"input flex justify-between items-center cursor-default",onClick:b},[n("div",null,d(p.value||"Selection"),1),_(o,{type:"chevron-down",size:"16",strokeWidth:"3"})]),_(D,{name:"fade"},{default:B(()=>[i.value?(c(),r("div",A,[n("form",{class:"p-1 search",onSubmit:y(v,["prevent"])},[S(n("input",{ref_key:"search_elem",ref:h,type:"text",class:"input w-full","onUpdate:modelValue":e[0]||(e[0]=l=>a.value=l),placeholder:`search ${m.referenced_table_name}...`},null,8,M),[[T,a.value]]),n("div",{class:"icon",onClick:v},[_(o,{type:"search"})])],32),n("div",O,[(c(!0),r(U,null,V(f.values,l=>(c(),r("div",{class:"p-2 hover:bg-gray-200 transition cursor-pointer",onClick:W=>x(l)},[n("span",z,d(s.referenced_column_name.toUpperCase())+": "+d(l.key),1),n("span",null,d(l.string),1)],8,q))),256)),f.values.length===0?(c(),r("div",I," No results found. ")):w("",!0)])])):w("",!0)]),_:1})])}}};E({components:{"foreign-selection":R,...F}}).mount("#app");
