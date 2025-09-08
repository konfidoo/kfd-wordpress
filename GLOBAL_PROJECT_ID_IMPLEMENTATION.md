# Global Project ID Support - Implementation Guide

## Overview
This implementation adds support for using a global Project ID from plugin settings in the konfidoo WordPress plugin when no specific Project ID is set in content blocks.

## Changes Made

### 1. Admin Settings Page (`plugin.php`)
- Added a new settings page under WordPress Admin → Settings → konfidoo
- Users can configure a global Project ID that serves as fallback
- Settings are stored using WordPress options API with key `konfidoo_global_project_id`

### 2. JavaScript Integration (`src/init.php`) 
- Updated `wp_localize_script` to expose global Project ID to block editor
- Added `globalProjectId` to `cgbGlobal` object available in JavaScript

### 3. Block Editor Logic (`src/block/block.js`)
- Added `getEffectiveProjectId()` helper function that returns:
  1. Block-specific Project ID if set
  2. Global Project ID as fallback if block ID is empty
  3. Empty string if neither is available

### 4. UI Updates
- Project ID input shows placeholder indicating global fallback value
- Block preview shows "(Global)" indicator when using global Project ID
- Error messages updated to mention plugin settings option
- Link to project management uses effective Project ID

### 5. Frontend Rendering
- Save function updated to use effective Project ID for all component types:
  - `kfd-intro`
  - `kfd-modal` 
  - `kfd-inline`

## Backward Compatibility
- Existing blocks with specific Project IDs continue to work unchanged
- No changes required for existing installations
- Global setting is optional - blocks still work with just Configuration ID if both Project IDs are empty

## User Experience
1. **Admin sets global Project ID**: Go to Settings → konfidoo, set Project ID, save
2. **Content creator adds block**: Project ID field shows global value as placeholder
3. **Override if needed**: Content creator can still set block-specific Project ID
4. **Clear indication**: Block preview shows whether using global or specific Project ID

## Testing Scenarios
✓ Block with specific Project ID uses that ID
✓ Block without Project ID falls back to global setting  
✓ Blocks work even if no global setting is configured
✓ Error handling when cgbGlobal is not available
✓ UI correctly indicates source of Project ID (global vs specific)

## Files Modified
- `plugin/plugin.php` - Added admin interface
- `plugin/src/init.php` - Added global Project ID to JavaScript
- `plugin/src/block/block.js` - Updated block logic and UI