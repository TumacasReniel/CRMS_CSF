<script setup>
    import { computed } from 'vue'
    
    const props = defineProps({
        data: {
            type: Object,
        },
        form: {
            type: Object,
        },
    });

    // Helper function to get PSTOs with data for a specific sub-unit
    const getPstosWithData = (serviceId, unitId, subUnitId) => {
        if (!props.data?.all_units_data?.units_data?.[serviceId]?.[unitId]?.sub_units_data?.[subUnitId]?.pstos_data) {
            return [];
        }
        const pstosData = props.data.all_units_data.units_data[serviceId][unitId].sub_units_data[subUnitId].pstos_data;
        return Object.entries(pstosData)
            .filter(([pstoId, psto]) => psto.total_respo > 0)
            .map(([pstoId, psto]) => ({ id: pstoId, ...psto }));
    };

    // Helper function to get unit-level PSTOs with data (region_id = 10)
    const getUnitPstosWithData = (serviceId, unitId) => {
        if (!props.data?.all_units_data?.units_data?.[serviceId]?.[unitId]?.unit_pstos_data) {
            return [];
        }
        const unitPstosData = props.data.all_units_data.units_data[serviceId][unitId].unit_pstos_data;
        return Object.entries(unitPstosData)
            .filter(([pstoId, psto]) => psto.total_respo > 0)
            .map(([pstoId, psto]) => ({ id: pstoId, ...psto }));
    };

    // Helper function to check if there are units with sub-units
    const hasUnitsWithSubUnits = () => {
        if (!props.data?.services_units?.data) return false;
        for (const service of props.data.services_units.data) {
            if (service.units && service.units.length > 0) {
                for (const unit of service.units) {
                    if (unit.sub_units && unit.sub_units.length > 0) {
                        return true;
                    }
                }
            }
        }
        return false;
    };

    // Function to check if there's any CC data
    const hasAnyCCData = () => {
        if (!props.data?.cc_data) return false;
        const cc1 = props.data.cc_data.cc1_data;
        const cc2 = props.data.cc_data.cc2_data;
        const cc3 = props.data.cc_data.cc3_data;
        
        return (
            (cc1?.cc1_ans1 > 0) || (cc1?.cc1_ans2 > 0) || (cc1?.cc1_ans3 > 0) || (cc1?.cc1_ans4 > 0) ||
            (cc2?.cc2_ans1 > 0) || (cc2?.cc2_ans2 > 0) || (cc2?.cc2_ans3 > 0) || (cc2?.cc2_ans4 > 0) || (cc2?.cc2_ans5 > 0) ||
            (cc3?.cc3_ans1 > 0) || (cc3?.cc3_ans2 > 0) || (cc3?.cc3_ans3 > 0) || (cc3?.cc3_ans4 > 0)
        );
    };

</script>
<template>
    <!-- PART I: Citizen's Charter -->
    <v-card class="mb-3" v-if="hasAnyCCData()">
        <v-card-title class="bg-gray-500 text-white">
            PART I: CITIZEN'S CHARTER(CC)
        </v-card-title>
        <table style="width:100%; border: 1px solid #333; border-collapse: collapse; padding: 5px" class="text-center">
            <tr>
                <th></th>
                <th></th>
                <th>Number of Respondents</th>
                <th>Percentage</th>
            </tr>
            <tr class="bg-blue-200">
                <th>CC1</th>
                <th colspan="3" class="text-left">Which of the following best describes your awareness of a CC?</th>
            </tr>
            <tr>
                <td>1</td>
                <td class="text-left">I know what a CC is and I saw this office's CC</td>
                <td>{{ props.data.cc_data.cc1_data?.cc1_ans1 || 0 }}</td>
                <td>{{ props.data.cc_data.cc1_data?.cc1_ans1_pct || 0 }}%</td>
            </tr>
            <tr>
                <td>2</td>           
                <td class="text-left">I know what a CC is but I did NOT see this office's CC</td>
                <td>{{ props.data.cc_data.cc1_data?.cc1_ans2 || 0 }}</td>
                <td>{{ props.data.cc_data.cc1_data?.cc1_ans2_pct || 0 }}%</td>
            </tr>
            <tr>
                <td>3</td>
                <td class="text-left">I learned the CC when I saw this office's CC</td>
                <td>{{ props.data.cc_data.cc1_data?.cc1_ans3 || 0 }}</td>
                <td>{{ props.data.cc_data.cc1_data?.cc1_ans3_pct || 0 }}%</td>
            </tr>
            <tr>
                <td>4</td>
                <td class="text-left">I do not know what a CC is and I did not see one in this office. (Answer 'N/A' on CC2 and CC3)</td>
                <td>{{ props.data.cc_data.cc1_data?.cc1_ans4 || 0 }}</td>
                <td>{{ props.data.cc_data.cc1_data?.cc1_ans4_pct || 0 }}%</td>
            </tr>
            <tr class="bg-blue-200" >
                <th >CC2</th>
                <th colspan="3" class="text-left">If aware of CC (answered 1-3 in CC1), would say that the CC of this was...?</th>
            </tr>
            <tr>
                <td>1</td>
                <td class="text-left">Easy to see</td>
                <td>{{ props.data.cc_data.cc2_data?.cc2_ans1 || 0 }}</td>
                <td>{{ props.data.cc_data.cc2_data?.cc2_ans1_pct || 0 }}%</td>
            </tr>
            <tr>
                <td>2</td>
                <td class="text-left">Somewhat easy to see</td>
                <td>{{ props.data.cc_data.cc2_data?.cc2_ans2 || 0 }}</td>
                <td>{{ props.data.cc_data.cc2_data?.cc2_ans2_pct || 0 }}%</td>
            </tr>
            <tr>
                <td>3</td>
                <td class="text-left">Difficult to see</td>
                <td>{{ props.data.cc_data.cc2_data?.cc2_ans3 || 0 }}</td>
                <td>{{ props.data.cc_data.cc2_data?.cc2_ans3_pct || 0 }}%</td>
            </tr>
            <tr>
                <td>4</td>
                <td class="text-left">Not visible at all</td>
                <td>{{ props.data.cc_data.cc2_data?.cc2_ans4 || 0 }}</td>
                <td>{{ props.data.cc_data.cc2_data?.cc2_ans4_pct || 0 }}%</td>
            </tr>
            <tr>
                <td>5</td>
                <td class="text-left">N/A</td>
                <td>{{ props.data.cc_data.cc2_data?.cc2_ans5 || 0 }}</td>
                <td>{{ props.data.cc_data.cc2_data?.cc2_ans5_pct || 0 }}%</td>
            </tr>
            <tr class="bg-blue-200">
                <th >CC3</th>
                <th colspan="3" class="text-left">If aware of CC (answered 1-3 in CC1), how much did the CC help you in your transaction?</th>
            </tr>
            <tr>
                <td>1</td>
                <td class="text-left">Helped Very Much</td>
                <td>{{ props.data.cc_data.cc3_data?.cc3_ans1 || 0 }}</td>
                <td>{{ props.data.cc_data.cc3_data?.cc3_ans1_pct || 0 }}%</td>
            </tr>
            <tr>
                <td>2</td>
                <td class="text-left">Somewhat helped</td>
                <td>{{ props.data.cc_data.cc3_data?.cc3_ans2 || 0 }}</td>
                <td>{{ props.data.cc_data.cc3_data?.cc3_ans2_pct || 0 }}%</td>
            </tr>
            <tr>
                <td>3</td>
                <td class="text-left">Did not help</td>
                <td>{{ props.data.cc_data.cc3_data?.cc3_ans3 || 0 }}</td>
                <td>{{ props.data.cc_data.cc3_data?.cc3_ans3_pct || 0 }}%</td>
            </tr>
            <tr>
                <td>4</td>
                <td class="text-left">N/A</td>
                <td>{{ props.data.cc_data.cc3_data?.cc3_ans4 || 0 }}</td>
                <td>{{ props.data.cc_data.cc3_data?.cc3_ans4_pct || 0 }}%</td>
            </tr>
        </table>
    </v-card>

    <!-- PART II: Service Units Overview -->
    <v-card v-if="props.data && props.data.services_units && props.data.all_units_data && (props.data.total_respondents > 0 || hasUnitsWithSubUnits())">
        <div class="m-5">
            <h4 class="mb-3">PART II: SERVICE UNITS OVERVIEW - MONTHLY SUMMARY</h4>
            <table class="mb-4" style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Service Unit</th>
                        <th>Total No. of Respondents</th>
                        <th>% of Strongly Agree</th>
                        <th>% of Agree</th>
                        <th>% of Neither Agree Nor Disagree</th>
                        <th>% of Disagree</th>
                        <th>% of Strongly Disagree</th>
                        <th>% of N/A</th>

                    </tr>
                </thead>
                <tbody>
                    <template v-for="(service, index) in props.data.services_units.data" :key="index">
                        <template v-if="service.units && service.units.length > 0">
                        <tr class="bg-blue-200">
                            <td colspan="8"><strong>{{ service.services_name }}</strong></td>
                        </tr>
                        <template v-for="(unit, unitIndex) in service.units" :key="unitIndex">
                            <!-- Show unit if it has respondents OR has sub-units -->
                            <template v-if="props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.total_respo > 0 || (unit.sub_units && unit.sub_units.length > 0)">
                            <tr>
                                <td class="pl-5">{{ unit.unit_name }}</td>
                                <td class="text-center">
                                    {{ props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.total_respo > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.total_respo : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.pct_strongly_agree > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.pct_strongly_agree + '%' : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.pct_agree > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.pct_agree + '%' : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.pct_neither > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.pct_neither + '%' : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.pct_disagree > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.pct_disagree + '%' : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.pct_strongly_disagree > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.pct_strongly_disagree + '%' : '-' }}
                                </td>
                                <td class="text-center">
                                    {{ props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.pct_na > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.pct_na + '%' : '-' }}
                                </td>
                            </tr>
                            <!-- Display unit-level PSTOs under each unit (Region IX filtered) - shown as bullet/nested -->
                            <template v-if="getUnitPstosWithData(service.id, unit.id).length > 0">
                                <tr v-for="(unitPsto, unitPstoIndex) in getUnitPstosWithData(service.id, unit.id)" :key="'unitpsto-' + unitPstoIndex" class="bg-green-50">
                                    <td class="pl-10">{{ unitPsto.psto_name || 'PSTO' }}</td>
                                    <td class="text-center">
                                        {{ unitPsto.total_respo > 0 ? unitPsto.total_respo : '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ unitPsto.pct_strongly_agree > 0 ? unitPsto.pct_strongly_agree + '%' : '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ unitPsto.pct_agree > 0 ? unitPsto.pct_agree + '%' : '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ unitPsto.pct_neither > 0 ? unitPsto.pct_neither + '%' : '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ unitPsto.pct_disagree > 0 ? unitPsto.pct_disagree + '%' : '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ unitPsto.pct_strongly_disagree > 0 ? unitPsto.pct_strongly_disagree + '%' : '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ unitPsto.pct_na > 0 ? unitPsto.pct_na + '%' : '-' }}
                                    </td>
                                </tr>
                            </template>
                            
                            <!-- Display sub-units under Administrative Support Services -->
                            <template v-if="unit.sub_units && unit.sub_units.length > 0">
                                <template v-for="(subUnit, subUnitIndex) in unit.sub_units" :key="'sub-' + subUnitIndex">
                                <template v-if="props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.total_respo > 0">
                                <tr class="bg-gray-50">
                                    <td class="pl-10">{{ subUnit.sub_unit_name }}</td>
                                    <td class="text-center">
                                        {{ props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.total_respo > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.total_respo : '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pct_strongly_agree > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pct_strongly_agree + '%' : '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pct_agree > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pct_agree + '%' : '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pct_neither > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pct_neither + '%' : '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pct_disagree > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pct_disagree + '%' : '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pct_strongly_disagree > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pct_strongly_disagree + '%' : '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pct_na > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pct_na + '%' : '-' }}
                                    </td>
                                </tr>
                                </template>
                                <!-- Display sub-unit types under each sub-unit -->
                                <template v-if="props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.sub_unit_types_data">
                                    <tr v-for="(subUnitType, subUnitTypeIndex) in Object.values(props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.sub_unit_types_data || {})" :key="'subtype-' + subUnitTypeIndex" class="bg-yellow-50">
                                        <td class="pl-14">{{ subUnitType.type_name }}</td>
                                        <td class="text-center">
                                            {{ subUnitType.total_respo > 0 ? subUnitType.total_respo : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ subUnitType.pct_strongly_agree > 0 ? subUnitType.pct_strongly_agree + '%' : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ subUnitType.pct_agree > 0 ? subUnitType.pct_agree + '%' : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ subUnitType.pct_neither > 0 ? subUnitType.pct_neither + '%' : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ subUnitType.pct_disagree > 0 ? subUnitType.pct_disagree + '%' : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ subUnitType.pct_strongly_disagree > 0 ? subUnitType.pct_strongly_disagree + '%' : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ subUnitType.pct_na > 0 ? subUnitType.pct_na + '%' : '-' }}
                                        </td>
                                    </tr>
                                </template>
<!-- Display sub-unit PSTOs under each sub-unit (Region IX filtered) -->
                                <template v-if="subUnit.sub_unit_pstos && subUnit.sub_unit_pstos.length > 0">
                                    <tr v-for="(subUnitPsto, subUnitPstoIndex) in subUnit.sub_unit_pstos" :key="'subpsto-' + subUnitPstoIndex" class="bg-green-50" v-show="(props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pstos_data?.[subUnitPsto.id]?.total_respo || 0) > 0">
                                        <td class="pl-14">{{ subUnitPsto.psto_name || 'PSTO' }} (PSTO)</td>
                                        <td class="text-center">
                                            {{ (props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pstos_data?.[subUnitPsto.id]?.total_respo || 0) > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pstos_data?.[subUnitPsto.id]?.total_respo : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ (props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pstos_data?.[subUnitPsto.id]?.pct_strongly_agree || 0) > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pstos_data?.[subUnitPsto.id]?.pct_strongly_agree + '%' : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ (props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pstos_data?.[subUnitPsto.id]?.pct_agree || 0) > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pstos_data?.[subUnitPsto.id]?.pct_agree + '%' : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ (props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pstos_data?.[subUnitPsto.id]?.pct_neither || 0) > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pstos_data?.[subUnitPsto.id]?.pct_neither + '%' : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ (props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pstos_data?.[subUnitPsto.id]?.pct_disagree || 0) > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pstos_data?.[subUnitPsto.id]?.pct_disagree + '%' : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ (props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pstos_data?.[subUnitPsto.id]?.pct_strongly_disagree || 0) > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pstos_data?.[subUnitPsto.id]?.pct_strongly_disagree + '%' : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ (props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pstos_data?.[subUnitPsto.id]?.pct_na || 0) > 0 ? props.data.all_units_data?.units_data?.[service.id]?.[unit.id]?.sub_units_data?.[subUnit.id]?.pstos_data?.[subUnitPsto.id]?.pct_na + '%' : '-' }}
                                        </td>
                                    </tr>
                                </template>
                                </template>
                            </template>
                            </template>
                        </template>
                        </template>
                    </template>
                
                    <tr class="total-row" style="font-weight: bold; background-color: #e3f2fd;">
                        <td><strong>Total:</strong></td>
                        <td class="text-center"><strong>{{ props.data.total_respondents > 0 ? props.data.total_respondents : '-' }}</strong></td>
                        <td class="text-center"><strong>{{ props.data.all_units_data?.grand_pct_strongly_agree > 0 ? props.data.all_units_data?.grand_pct_strongly_agree + '%' : '-' }}</strong></td>
                        <td class="text-center"><strong>{{ props.data.all_units_data?.grand_pct_agree > 0 ? props.data.all_units_data?.grand_pct_agree + '%' : '-' }}</strong></td>
                        <td class="text-center"><strong>{{ props.data.all_units_data?.grand_pct_neither > 0 ? props.data.all_units_data?.grand_pct_neither + '%' : '-' }}</strong></td>
                        <td class="text-center"><strong>{{ props.data.all_units_data?.grand_pct_disagree > 0 ? props.data.all_units_data?.grand_pct_disagree + '%' : '-' }}</strong></td>
                        <td class="text-center"><strong>{{ props.data.all_units_data?.grand_pct_strongly_disagree > 0 ? props.data.all_units_data?.grand_pct_strongly_disagree + '%' : '-' }}</strong></td>
                        <td class="text-center"><strong>{{ props.data.all_units_data?.grand_pct_na > 0 ? props.data.all_units_data?.grand_pct_na + '%' : '-' }}</strong></td>
                    </tr>
                </tbody>
            </table>

            <!-- Assessment Summary -->
            <div class="assessment m-5">
                <h5 class="mb-3">ASSESSMENT SUMMARY - {{ props.form?.selected_month }} {{ props.form?.selected_year || '' }}</h5>
                <p v-if="props.data.total_respondents > 0">
                    The Department of Science and Technology IX for the month of <strong>{{ props.form?.selected_month }}</strong> <strong>{{ props.form?.selected_year }}</strong> 
                    had a total of <strong>{{ props.data.total_respondents || 0 }}</strong> respondents who filled out and rated the Customer Satisfaction Feedback. 
                    <strong>{{ props.data.total_vss_respondents || 0 }}</strong> (or <strong>{{ props.data.percentage_vss_respondents || 0 }}%</strong>) 
                    of the respondents rated the CSF as either very satisfied (VS) or satisfied (S), which resulted in an overall average 
                    Customer Satisfaction Index (CSI) of <strong>{{ props.data.csi_total || 0 }}%</strong> and a Net Promoter Score of <strong>{{ props.data.nps_total || 0 }}%</strong>.
                </p>
                <p v-else>
                    No data available for the selected month and year.
                </p>
            </div>
        </div>
    </v-card>

    <!-- No Data State -->
    <v-card v-else>
        <div class="m-5 text-center">
            <p class="text-muted">No data available. Please generate a report first.</p>
        </div>
    </v-card>
</template>

<style scoped>
body {
  font-family: Arial, sans-serif;
  margin: 20px;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}
tr,th, td {
  border: 1px solid #dddddd;
  padding: 8px;
}
.total-row {
  font-weight: bold;
  background-color: #f9f9f9;
}
.section-title {
  font-weight: bold;
  text-align: left;
}
.assessment {
  margin-top: 20px;
}
.bg-blue-200 {
    background-color: #e3f2fd;
}
.bg-yellow-50 {
    background-color: #fef9e7;
}
.bg-green-50 {
    background-color: #e8f5e9;
}
</style>
