# TODO: Display PSTOs with region_id = 10 in All Services Units Summary

## Task: Display PSTOs based on survey (region_id = 10) in All Services Units summary

### Changes Made:

1. [x] **app/Http/Controllers/ReportController.php**
   - [x] Update `generateCSIAllUnitMonthly` method to eager load unit PSTOs
   - [x] Fix `getAllUnitsData` method to calculate and include unit-level PSTO data with region_id = 10
   - [x] Added sub_unit_types to services query
   - [x] PSTO queries now use customer_id instead of csf_form_id
   - [x] Rating checks use rate_score (5,4,3,2,1,6) instead of text (SA,A,etc.)
   - [x] Added year filtering with whereYear()
   
2. [x] **resources/js/Pages/CSI/AllServicesUnits/Monthly/Content.vue**
   - [x] Add display logic for unit-level PSTOs under each unit
   - [x] Added helper function getUnitPstosWithData

3. [x] **Yearly Report**
   - [x] Uses same data structure from controller, PSTO display handled by Monthly Content.vue

### Notes:
- Unit PSTOs are stored in `unit_pstos` table and related via `pstos()` relationship in Unit model
- PSTOs are filtered by region_id = 10 (Region IX - Zamboanga Peninsula)
- PSTO data shows: total respondents, VSS %, rating percentages
- The Yearly report has a different structure focused on ORD units
