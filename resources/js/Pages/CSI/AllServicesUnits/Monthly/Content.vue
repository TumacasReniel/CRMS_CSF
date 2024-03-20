<script setup>
    const props = defineProps({
        data: {
            type: Object,
        },
        form: {
            type: Object,
        },
    });
</script>
<template>
    <v-card class="mb-3">
        <v-card-title class="bg-gray-500 text-white text-center">
            <h5>
                DOST IX CUSTOMER SATISFACTION FEEDBACK <br> for
                <u><span>{{ form.selected_month }}</span>  {{ form.selected_year }}</u>
            </h5><br>
        </v-card-title>
        <table style="width:100%; border: 1px solid #333; border-collapse: collapse;  padding: 3px" >
            <tr class="text-center">
                <th colspan="3">Service Unit</th>
                <th >Total No. of Respondents</th>
                <th >Total No. of Respondents who rated VS and S</th>
                <th >Percentage of Respondents who rated VS and S</th>
                <th >Customer Satisfaction Rating</th>
                <th >Customer Satisfaction Index(CSI)</th>
                <th >Net Promoter Score</th>
                <th >Likert Scale Rating <br>(Attribute Average)</th>
            </tr>
        
            <template v-if="data.service_units" v-for="(service_unit, index) in data.service_units.data" :key="service_unit.id">
                <tr  class="text-left bg-blue-200">                     
                    <th colspan="10">
                       <u> {{ service_unit.services_name }}</u>
                    </th>
                </tr>
                <tr v-if="service_unit" v-for="(unit , index) in service_unit.units" :key="service_unit.id">
                  <td colspan="1">{{ unit.unit_name }}</td>
                </tr>
            </template >
              
                                       
        </table>
    </v-card> 
    
</template>

<style scoped>
   table {
    border-collapse: collapse;
    width: 100%; /* Optional: Set a width for the table */
    margin: 5px;
  }

  tr, th, td {
    border: 1px solid rgb(145, 139, 139); /* Optional: Add a border for better visibility */
    padding: 4px; /* Optional: Add padding for better spacing */
  }
</style>