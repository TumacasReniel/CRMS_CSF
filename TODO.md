# Fix Account Features TODO

## Issues to Fix:
1. Prop naming mismatch in Modal.vue (modelValue vs value) - FIXED
2. Account list not refreshing after add/update
3. No validation in AccountController

## Plan:
- [x] Fix Modal.vue: Change `modelValue` prop to `value` and update emit
- [ ] Fix Index.vue: Update `reloadAccounts` to actually reload accounts from server
- [ ] Fix AccountController.php: Add validation for store and update methods
