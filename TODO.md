# CSI Printing Fix - Remove v-if Conditions

## Task Overview
Remove v-if conditions from CSI printout components that hide data when values are 0, ensuring all data (including 0 values) is displayed in the reports.

## Files to Edit
- resources/js/Pages/CSI/Quarterly/Printouts/ByUnitQuarter1.vue
- resources/js/Pages/CSI/Quarterly/Printouts/ByUnitQuarter2.vue
- resources/js/Pages/CSI/Quarterly/Printouts/ByUnitQuarter3.vue
- resources/js/Pages/CSI/Quarterly/Printouts/ByUnitQuarter4.vue
- resources/js/Pages/CSI/Yearly/ByUnitYearly.vue

## Steps
1. Edit ByUnitQuarter1.vue - Remove v-if="value > 0" from all data display spans
2. Edit ByUnitQuarter2.vue - Remove v-if="value > 0" from all data display spans
3. Edit ByUnitQuarter3.vue - Remove v-if="value > 0" from all data display spans
4. Edit ByUnitQuarter4.vue - Remove v-if="value > 0" from all data display spans
5. Edit ByUnitYearly.vue - Remove v-if="value > 0" from all data display spans
6. Test the printing functionality after changes

## Progress
- [x] Step 1: Edit ByUnitQuarter1.vue
- [ ] Step 2: Edit ByUnitQuarter2.vue
- [ ] Step 3: Edit ByUnitQuarter3.vue
- [ ] Step 4: Edit ByUnitQuarter4.vue
- [ ] Step 5: Edit ByUnitYearly.vue
- [ ] Step 6: Test printing functionality
