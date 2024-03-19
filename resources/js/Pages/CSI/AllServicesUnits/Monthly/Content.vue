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
        <v-card-title class="bg-gray-500 text-white">
            <h5 style="text-transform:capitalize; text-align:center; margin-top: -10px">
                DOST IX CUSTOMER SATISFACTION FEEDBACK <br> for
                <u><span>{{ form.selected_month }}</span>  {{ form.selected_year }}</u>
            </h5><br>
        </v-card-title>
        <!-- <table style="width:100%; border: 1px solid #333; border-collapse: collapse;  padding: 3px" >
            <tr class="text-center">
                <td rowspan="2">Service Quality Attributes</td>
                <td >5</td>
                <td >4</td>
                <td >3</td>
                <td >2</td>
                <td >1</td>
                <td  rowspan="2">TOTAL SCORE</td>
                <td  rowspan="2">Likert Scale Rating</td>
                <td  rowspan="2">GAP</td>
            </tr>
            <tr class="text-center">               
                <td >Very Satisfied</td>
                <td style="border: 1px solid #333;padding:2px">Satisfied </td>
                <td >Neither </td>
                <td  >Dissatisfied </td>
                <td >Very Dissatisfied </td>
            </tr>

            <tr v-for="(dimension, index) in data.dimensions" :key="dimension.id" class="border border-solid hover:bg-gray-100 focus-within:bg-gray-100">                     
                    <td class="border-t p-5 pl-10">
                        {{ index + 1 }}.{{ dimension.name }}
                    </td>
                    <td v-if="data.y_totals" class="text-center"  v-for="total in data.y_totals[index+1]">
                        {{ total }}
                    </td>
                    <td v-if="data.x_totals" class="text-center"  v-for="total in data.x_totals[index+1]">
                        {{ total }}
                    </td>
                        <td v-if="data.likert_scale_rating_totals" class=" text-center"  v-for="total in data.likert_scale_rating_totals[index+1]">
                        {{ total }}
                    </td>          
                    <td v-if="data.likert_scale_rating_totals" class="text-center"  v-for="total in data.gap_totals[index+1]">
                        {{ total }}
                    </td>                   
            </tr>
            <tr class="text-center font-black p-5 m-5 border border-solid hover:bg-gray-100 focus-within:bg-gray-100" >
                <td class="m-5 p-3">TOTAL SCORE</td>
                <td >{{ data.grand_vs_total }}</td>
                <td >{{ data.grand_s_total }}</td>
                <td >{{ data.grand_n_total }}</td>
                <td >{{ data.grand_d_total }}</td>
                <td >{{ data.grand_vd_total }}</td>
                <td >{{ data.x_grand_total }}</td>
                <td >{{ data.lsr_grand_total }}</td>
                <td >{{ data.gap_grand_total }}</td>
            </tr>                                               
        </table> -->
    </v-card> 
    
</template>

<style scoped>
   table {
    border-collapse: collapse;
    width: 100%; /* Optional: Set a width for the table */
  }

  tr, th, td {
    border: 1px solid rgb(145, 139, 139); /* Optional: Add a border for better visibility */
    padding: 8px; /* Optional: Add padding for better spacing */
  }
</style>