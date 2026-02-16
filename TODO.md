# TODO: Fix NPS Calculation in Yearly View

## Issues Identified in `app/Http/Controllers/ReportController.php`:
1. **Missing customer_ids filter**: The `$customer_recommendation_ratings` query in `generateCSIByUnitYearly` is missing `whereIn('customer_id', $customer_ids)` - causing it to count ALL recommendations for the year, not just for the specific unit.

2. **Incorrect NPS thresholds**: Current code uses:
   - Promoters: `recommend_rate_score > 6` (includes 7,8,9,10)
   - Detractors: `recommend_rate_score < 7` (includes 0,1,2,3,4,5,6)
   - This causes overlap where ratings 7-8 are counted as BOTH promoters and detractors!

## Fix Plan:
- [ ] Fix the customer_recommendation_ratings query to filter by customer_ids
- [ ] Change promoter threshold to `>= 9` (correct NPS standard)
- [ ] Keep detractor threshold as `< 7` (correct NPS standard)
- [ ] This ensures ratings 7-8 are NOT counted in either (they're "Passives" in NPS)
