import{A as p}from"./AppLayout-CtRSdcwP.js";import c from"./DeleteUserForm-Bxs-Fd65.js";import l from"./LogoutOtherBrowserSessionsForm-Btpz_Lpl.js";import{S as s}from"./SectionBorder-AvLEhQfY.js";import f from"./TwoFactorAuthenticationForm-DfoZs62c.js";import u from"./UpdatePasswordForm-D82l40zv.js";import d from"./UpdateProfileInformationForm-CeO5hAUo.js";import{o as e,c as _,w as n,a as i,e as r,b as t,f as a,F as h}from"./app-D8es4Nph.js";import"./dost-logo-B4Huuyod.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";import"./DialogModal-DDJDwpIH.js";import"./SectionTitle-Bo-q7th4.js";import"./DangerButton-Bzuim0YH.js";import"./TextInput-CamKiIut.js";import"./SecondaryButton-BF5EMjI-.js";import"./ActionMessage-BOh0y_di.js";import"./PrimaryButton-BwOP5uE8.js";import"./InputLabel-YpEP8UZG.js";import"./FormSection-ktLLkpbK.js";const g=i("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," Profile ",-1),$={class:"max-w-7xl mx-auto py-10 sm:px-6 lg:px-8"},w={key:0},y={key:1},k={key:2},z={__name:"Show",props:{confirmsTwoFactorAuthentication:Boolean,sessions:Array},setup(m){return(o,A)=>(e(),_(p,{title:"Profile"},{header:n(()=>[g]),default:n(()=>[i("div",null,[i("div",$,[o.$page.props.jetstream.canUpdateProfileInformation?(e(),r("div",w,[t(d,{user:o.$page.props.auth.user},null,8,["user"]),t(s)])):a("",!0),o.$page.props.jetstream.canUpdatePassword?(e(),r("div",y,[t(u,{class:"mt-10 sm:mt-0"}),t(s)])):a("",!0),o.$page.props.jetstream.canManageTwoFactorAuthentication?(e(),r("div",k,[t(f,{"requires-confirmation":m.confirmsTwoFactorAuthentication,class:"mt-10 sm:mt-0"},null,8,["requires-confirmation"]),t(s)])):a("",!0),t(l,{sessions:m.sessions,class:"mt-10 sm:mt-0"},null,8,["sessions"]),o.$page.props.jetstream.hasAccountDeletionFeatures?(e(),r(h,{key:3},[t(s),t(c,{class:"mt-10 sm:mt-0"})],64)):a("",!0)])])]),_:1}))}};export{z as default};
