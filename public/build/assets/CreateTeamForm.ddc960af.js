import{u as _,o as d,c as u,w as a,a as e,b as o,t as n,f as t,n as f,h as m}from"./app.28c5ffc3.js";import{_ as h}from"./Button.52902584.js";import{_ as g}from"./FormSection.cb01fa7e.js";import{_ as v}from"./Input.7cc15f1d.js";import{_ as $}from"./InputError.8a6608b2.js";import{_ as i}from"./Label.dfa35d04.js";import"./SectionTitle.2db581fb.js";import"./_plugin-vue_export-helper.cdc0426e.js";const b=m(" Team Details "),w=m(" Create a new team to collaborate with others on projects. "),T={class:"col-span-6"},V={class:"flex items-center mt-2"},C=["src","alt"],x={class:"ml-4 leading-tight"},y={class:"text-sm text-gray-700"},B={class:"col-span-6 sm:col-span-4"},N=m(" Create "),A={__name:"CreateTeamForm",setup(k){const s=_({name:""}),c=()=>{s.post(route("teams.store"),{errorBag:"createTeam",preserveScroll:!0})};return(r,l)=>(d(),u(g,{onSubmitted:c},{title:a(()=>[b]),description:a(()=>[w]),form:a(()=>[e("div",T,[o(i,{value:"Team Owner"}),e("div",V,[e("img",{class:"object-cover w-12 h-12 rounded-full",src:r.$page.props.user.profile_photo_url,alt:r.$page.props.user.name},null,8,C),e("div",x,[e("div",null,n(r.$page.props.user.name),1),e("div",y,n(r.$page.props.user.email),1)])])]),e("div",B,[o(i,{for:"name",value:"Team Name"}),o(v,{id:"name",modelValue:t(s).name,"onUpdate:modelValue":l[0]||(l[0]=p=>t(s).name=p),type:"text",class:"block w-full mt-1",autofocus:""},null,8,["modelValue"]),o($,{message:t(s).errors.name,class:"mt-2"},null,8,["message"])])]),actions:a(()=>[o(h,{class:f({"opacity-25":t(s).processing}),disabled:t(s).processing},{default:a(()=>[N]),_:1},8,["class","disabled"])]),_:1}))}};export{A as default};
