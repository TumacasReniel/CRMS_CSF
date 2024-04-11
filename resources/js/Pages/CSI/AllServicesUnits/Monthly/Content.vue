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
            <template v-if="data.service_units" v-for="(service_unit, i) in data.service_units.data" :key="service_unit.id">
                <tr  class="text-left bg-blue-200">                     
                    <th colspan="10">
                       <u> {{ service_unit.services_name }}</u>                
                    </th>
                </tr>

                <!-- {{ service_unit.units[index].sub_units }} -->
                <template v-if="service_unit" v-for="(unit , number) in service_unit.units" :key="service_unit.id">
                    <tr >
                         <td colspan="3">{{ unit.unit_name }}</td>
                    </tr>
                    
                    <template v-if="unit.id == 8">
                        <tr>
                            <td colspan="3" class="pl-10">&bull; Internal</td>
                        </tr>
                        <tr >
                            <td colspan="3" class="pl-10">&bull; External</td>
                        </tr>
                    </template>

                    <template v-if="service_unit" v-for="(sub_unit , index) in unit.sub_units" :key="service_unit.id">
                        <tr >             
                            <td colspan="3" class="pl-10"> &bull;  {{ sub_unit.sub_unit_name }}</td>         
                        </tr>
                        <tr v-if="service_unit" v-for="(type , index) in sub_unit.sub_unit_types" :key="type.id">             
                            <td colspan="3" class="pl-15">-  {{ type.type_name }}</td>         
                        </tr>

                        <!-- {{ sub_unit.sub_unit_pstos }} -->
                        <!-- <tr  v-for="(psto , index) in sub_unit.sub_unit_pstos" :key="psto.id">             
                            <td colspan="3" class="pl-15">-  {{ psto.psto_name }}</td>         
                        </tr> -->
                    </template>
                  
                </template>
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