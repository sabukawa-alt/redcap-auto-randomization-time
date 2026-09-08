[日本語版はこちら](README.ja.md)

# Auto Randomization Time

REDCap external module that automatically saves the randomization date/time
to a field on your form — works with automatic (real-time) randomization,
not just the manual Randomize button.

## Features
- Automatically captures the date/time when a record is randomized
- Works with both the manual "Randomize" button and automatic
  (real-time trigger) randomization
- Choose the date/time format (with or without time, with or without seconds)
- Choose which event the destination field belongs to

## Installation
1. Clone this repo into `<redcap-root>/modules/auto_randomization_time_v1.0.0`
2. Go to **Control Center > External Modules** and enable "Auto Randomization Time"
3. On your project, go to **Applications > External Modules** and enable the module
4. Configure the module settings:
   - **Destination field**: the text field where the randomization date/time will be saved
   - **Date/time format**: match this with the field's Text Validation setting
   - **Event**: the event where the destination field lives

## Notes
- REDCap's built-in Randomization module must be enabled on the project
- The destination field should have **no other action tags** (e.g. @DEFAULT,
  @SETVALUE, @CALCTEXT) that could conflict with this module's save
- We recommend setting **@READONLY** or **@HIDDEN** on the destination field
  to prevent it from being edited manually

## Authors
- Shoco & Aco & Coji

## License
MIT
