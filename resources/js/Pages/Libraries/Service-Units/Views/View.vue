<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import VueMultiselect from 'vue-multiselect';
    import { router } from '@inertiajs/vue3';
    import { reactive, ref, watch } from 'vue';
    import QrcodeVue from 'qrcode.vue';
    import { Printd } from 'printd';
    import CSFPrint from '@/Pages/Libraries/Service-Units/Form/PrintCSF.vue';

    const props = defineProps({
        service: Object,
        unit: Object,
        unit_pstos: Object,
        sub_unit_pstos: Object,
        sub_unit_types: Object,
        user: Object,
    });

    const form = reactive({
        generated_url: null,
        selected_sub_unit: '',
        selected_unit_psto: '',
        selected_sub_unit_psto: '',
        sub_unit_type: '',
        client_type: ''
    });

    const qr_link_type = ref(null);
    const generated = ref(false);
    const copied = ref(false);
    const is_printing = ref(false);
    const baseURL = window.location.origin;

    const getSubUnitPSTO = async (sub_unit_id) => {
        const currentQueryParams = new URLSearchParams(window.location.search);
        currentQueryParams.set('sub_unit_id', sub_unit_id);
        const newUrl = `/csi/view?${currentQueryParams.toString()}`;

        await router.visit(newUrl, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    watch(
        () => form.selected_sub_unit,
        (value) => {
            getSubUnitPSTO(value.id);
        }
    );

    const generateURL = async (sub_unit, unit_psto, sub_unit_psto, sub_unit_type) => {
        generated.value = true;

        if (props.unit.data[0].id == 8) {
            qr_link_type.value = 4;
            form.generated_url = baseURL + '/services/csf?' +
                'region_id=' + props.user.region_id +
                '&service_id=' + props.service.id +
                '&unit_id=' + props.unit.data[0].id +
                '&client_type=' + form.client_type;
        } else if (unit_psto) {
            qr_link_type.value = 3;
            form.generated_url = baseURL + '/services/csf?' +
                'region_id=' + props.user.region_id +
                '&service_id=' + props.service.id +
                '&unit_id=' + props.unit.data[0].id +
                '&psto_id=' + unit_psto.id;
        } else if (sub_unit_psto && sub_unit_psto) {
            qr_link_type.value = 2;
            form.generated_url = baseURL + '/services/csf?' +
                'region_id=' + props.user.region_id +
                '&service_id=' + props.service.id +
                '&unit_id=' + props.unit.data[0].id +
                '&sub_unit_id=' + sub_unit.id +
                '&psto_id=' + sub_unit_psto.id;
        } else if (sub_unit) {
            if (sub_unit_type) {
                qr_link_type.value = 1.1;
                form.generated_url = baseURL + '/services/csf?' +
                    'region_id=' + props.user.region_id +
                    '&service_id=' + props.service.id +
                    '&unit_id=' + props.unit.data[0].id +
                    '&sub_unit_id=' + sub_unit.id +
                    '&sub_unit_type=' + sub_unit_type.type_name;
            } else {
                qr_link_type.value = 1.2;
                form.generated_url = baseURL + '/services/csf?' +
                    'region_id=' + props.user.region_id +
                    '&service_id=' + props.service.id +
                    '&unit_id=' + props.unit.data[0].id +
                    '&sub_unit_id=' + sub_unit.id;
            }
        } else {
            qr_link_type.value = 0;
            form.generated_url = baseURL + '/services/csf?' +
                'region_id=' + props.user.region_id +
                '&service_id=' + props.service.id +
                '&unit_id=' + props.unit.data[0].id;
        }
    };

    const copyToClipboard = () => {
        const textarea = document.createElement('textarea');
        textarea.value = form.generated_url;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        copied.value = true;

        setTimeout(() => {
            copied.value = false;
        }, 2000);
    };

    const printCSFForm = async () => {
        is_printing.value = true;
        let d = await new Printd();
        let css = `
            @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;600;800&family=Roboto:wght@100;300;400;500;700;900&display=swap');
            * {
                font-family: 'Time New Roman'
            }
            .new-page {
                page-break-before: always;
            }
            .th-color{
                background-color: #8fd1e8;
            }
            .text-center{
                text-align: center;
            }
            .text-right{
                text-align: end
            }
            table {
                border-collapse: collapse;
                width: 100%;
            }
            tr, th, td {
                border: 1px solid rgb(145, 139, 139);
                padding: 3px;
            }
            .page-break {
                page-break-before: always;
            }
            .form-control {
                display: block;
                width: 100%;
                padding: 0.375rem 0.75rem;
                font-size: 1rem;
                line-height: 1.5;
                color: #495057;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #ced4da;
                border-radius: 0.25rem;
                transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }
        `;

        d.print(document.querySelector('.print-id'), [css]);
    };
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="page-title">
                View
            </h2>
        </template>

        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="card shadow-lg rounded">
                    <div class="card-header m-3">
                        <div v-if="service">
                            SERVICES : {{ service.services_name }}
                        </div>
                        <hr class="border-opacity-100">
                        <div v-if="unit">
                            SERVICE UNIT : {{ props.unit.data[0].unit_name }}
                        </div>
                    </div>

                    <div class="card-body overflow-visible" style="min-height: 600px;">
                        <div class="row p-4">
                            <div
                                class="col-auto my-auto"
                                v-if="unit.data[0].sub_units && unit.data[0].sub_units.length > 0"
                            >
                                <vue-multiselect
                                    v-model="form.selected_sub_unit"
                                    :options="unit.data[0].sub_units || []"
                                    :multiple="false"
                                    placeholder="Select Sub Unit*"
                                    label="sub_unit_name"
                                    track-by="sub_unit_name"
                                    :allow-empty="false"
                                >
                                </vue-multiselect>
                            </div>

                            <div
                                class="col-auto my-auto ms-5"
                                v-if="unit_pstos.length > 0"
                            >
                                <vue-multiselect
                                    v-model="form.selected_unit_psto"
                                    :options="unit_pstos"
                                    :multiple="false"
                                    placeholder="Select Unit PSTO"
                                    label="psto_name"
                                    track-by="psto_name"
                                    :allow-empty="false"
                                >
                                </vue-multiselect>
                            </div>

                            <div
                                class="col-auto my-auto me-5"
                                v-if="sub_unit_pstos.length > 0"
                            >
                                <vue-multiselect
                                    v-model="form.selected_sub_unit_psto"
                                    :options="sub_unit_pstos"
                                    :multiple="false"
                                    placeholder="Select Sub Unit PSTO"
                                    label="psto_name"
                                    track-by="psto_name"
                                    :allow-empty="false"
                                >
                                </vue-multiselect>
                            </div>

                            <div
                                class="col-auto my-auto"
                                v-if="sub_unit_types.length > 0 && form.selected_sub_unit"
                            >
                                <vue-multiselect
                                    v-model="form.sub_unit_type"
                                    :options="sub_unit_types"
                                    :multiple="false"
                                    placeholder="Select Sub Unit Type"
                                    label="type_name"
                                    track-by="type_name"
                                    :allow-empty="false"
                                >
                                </vue-multiselect>
                            </div>

                            <div class="col-auto my-auto text-end">
                                <button
                                    class="btn btn-primary"
                                    :disabled="(unit.data[0].sub_units && unit.data[0].sub_units.length > 0 && form.selected_sub_unit == '') ||
                                        sub_unit_pstos.length > 0 && form.selected_sub_unit_psto == '' ||
                                        unit_pstos.length > 0 && form.selected_unit_psto == '' ||
                                        form.selected_sub_unit == 3 && form.sub_unit_type == ''"
                                    @click="generateURL(form.selected_sub_unit, form.selected_unit_psto, form.selected_sub_unit_psto, form.sub_unit_type)"
                                >
                                    <i class="ri-add-line me-1"></i> Generate URL
                                </button>
                            </div>
                        </div>

                        <div class="p-5 m-5" label="URL">
                            <div class="row">
                                <div class="col-10 col-md-11">
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="form.generated_url"
                                        readonly
                                        placeholder="Generated URL"
                                    />
                                </div>
                                <div class="col-2 col-md-1">
                                    <button
                                        class="btn btn-outline-secondary"
                                        @click="copyToClipboard()"
                                    >
                                        <i class="ri-file-copy-line"></i>
                                    </button>
                                    <span v-if="copied" class="ms-2 text-success">copied</span>
                                </div>
                            </div>
                        </div>

                        <div
                            style="display: flex; justify-content: center; align-items: center;"
                            class="mb-4"
                        >
                            <QrcodeVue
                                v-if="qr_link_type == 0"
                                :render-as="'svg'"
                                :value="`${baseURL}/services/csf?region_id=${user.region_id}&service_id=${props.service.id}&unit_id=${props.unit.data[0].id}`"
                                :size="145"
                                :foreground="'#000'"
                                level="L"
                                style="border: 3px #ffffff solid; width: 300px; height: 300px;"
                            />
                            <QrcodeVue
                                v-if="qr_link_type == 1.1"
                                :render-as="'svg'"
                                :value="`${baseURL}/services/csf?region_id=${user.region_id}&service_id=${props.service.id}&unit_idk=${unit.data[0].id }&sub_unit_id=${form.selected_sub_unit.id}`"
                                :size="145"
                                :foreground="'#000'"
                                level="L"
                                style="border: 3px #ffffff solid; width: 300px; height: 300px;"
                            />
                            <QrcodeVue
                                v-if="qr_link_type == 1.2"
                                :render-as="'svg'"
                                :value="`${baseURL}/services/csf?region_id=${user.region_id}&service_id=${props.service.id}&unit_id=${unit.data[0].id }&sub_unit_id=${form.selected_sub_unit.id}&sub_unit_type=${form.sub_unit_type.type_name}`"
                                :size="145"
                                :foreground="'#000'"
                                level="L"
                                style="border: 3px #ffffff solid; width: 300px; height: 300px;"
                            />
                            <QrcodeVue
                                v-if="qr_link_type == 2"
                                :render-as="'svg'"
                                :value="`${baseURL}/services/csf?region_id=${user.region_id}&service_id=${props.service.id}&unit_id=${unit.data[0].id }&sub_unit_id=${form.selected_sub_unit.id}&psto_id=${form.selected_sub_unit_psto.id}`"
                                :size="145"
                                :foreground="'#000'"
                                level="L"
                                style="border: 3px #ffffff solid; width: 300px; height: 300px;"
                            />
                            <QrcodeVue
                                v-if="qr_link_type == 3"
                                :render-as="'svg'"
                                :value="`${baseURL}/services/csf?region_id=${user.region_id}&service_id=${props.service.id}&unit_id=${unit.data[0].id}&psto_id=${form.selected_unit_psto.id}`"
                                :size="145"
                                :foreground="'#000'"
                                level="L"
                                style="border: 3px #ffffff solid; width: 300px; height: 300px;"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <CSFPrint
            v-if="generated == true"
            :is_printing="is_printing"
            :form="form"
            :data="props"
        />
    </AppLayout>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<style scoped>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    tr, th, td {
        border: 1px solid rgb(145, 139, 139);
        padding: 8px;
    }
</style>
