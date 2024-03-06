import{a as d,o as m,b as i,w as t,d as e,_ as u,u as a,e as c,f as k,c as h}from"./createLucideIcon-Df4F-6av.js";import{_ as f,a as l,b as _,c as p}from"./DropdownMenuItem.vue_vue_type_script_setup_true_lang-CAWRjkcO.js";/**
 * @license lucide-vue-next v0.344.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const y=d("MoonIcon",[["path",{d:"M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z",key:"a7tn18"}]]);/**
 * @license lucide-vue-next v0.344.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const v=d("SunIcon",[["circle",{cx:"12",cy:"12",r:"4",key:"4exip2"}],["path",{d:"M12 2v2",key:"tus03m"}],["path",{d:"M12 20v2",key:"1lh1kg"}],["path",{d:"m4.93 4.93 1.41 1.41",key:"149t6j"}],["path",{d:"m17.66 17.66 1.41 1.41",key:"ptbguv"}],["path",{d:"M2 12h2",key:"1t8f8n"}],["path",{d:"M20 12h2",key:"1q8mjw"}],["path",{d:"m6.34 17.66-1.41 1.41",key:"1m8zz5"}],["path",{d:"m19.07 4.93-1.41 1.41",key:"1shlcs"}]]),g=k("span",{class:"sr-only"},"Toggle theme",-1),w={__name:"ThemeSwitcher",setup(M){const o=n=>{n==="dark"||n==="system"&&window.matchMedia("(prefers-color-scheme: dark)").matches?document.documentElement.classList.add("dark"):document.documentElement.classList.remove("dark"),localStorage.setItem("theme",n)};return(n,s)=>(m(),i(a(p),null,{default:t(()=>[e(a(f),{asChild:""},{default:t(()=>[e(u,{variant:"outline",size:"icon",class:"rounded-full"},{default:t(()=>[e(a(v),{class:"h-[1.2rem] w-[1.2rem] rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0"}),e(a(y),{class:"absolute h-[1.2rem] w-[1.2rem] rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100"}),g]),_:1})]),_:1}),e(a(_),{align:"end"},{default:t(()=>[e(a(l),{onClick:s[0]||(s[0]=r=>o("light"))},{default:t(()=>[c(" Light ")]),_:1}),e(a(l),{onClick:s[1]||(s[1]=r=>o("dark"))},{default:t(()=>[c(" Dark ")]),_:1}),e(a(l),{onClick:s[2]||(s[2]=r=>o("system"))},{default:t(()=>[c(" System ")]),_:1})]),_:1})]),_:1}))}};h({components:{"theme-switcher":w}}).mount("#navbar");
