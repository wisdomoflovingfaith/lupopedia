# NEXT SESSION START HERE NOTES

## The Eye Implementation - DynAPI Library Status

### Current Status (April 1, 2026)

**DynAPI Library Moved:**
- ✅ Copied from `lupo-archive/legacy/craftysyntax-3.7.5/javascript/dynapi/` to `lupo-includes/js/dynapi/`
- ✅ Complete library including LICENSE, README, REVISION, index.html, and js/dynlayer.js
- ✅ Original 1999 dynlayer.js preserved

**Current Eye Implementation Issues:**

1. **Image Paths**: Currently using `lupo-images/` prefix (lines 20, 29-34 in crafty_syntax_eyes.js)
   - Should use `LUPOPEDIA_PUBLIC_PATH` for subdirectory compatibility
   - Example: `<?php echo LUPOPEDIA_PUBLIC_PATH; ?>lupo-images/`

2. **DynLayer Reference**: Using `new DynLayer()` but dynlayer.js path may need updating
   - Current: dynlayer.js is in `lupo-includes/js/` (old location)
   - Should reference: `lupo-includes/js/dynapi/js/dynlayer.js`

### Required Changes for Next Session

1. **Update Image Paths in crafty_syntax_eyes.js:**
   ```javascript
   // Current (line 20):
   lidsblock.src = 'lupo-images/lids3.png';
   
   // Should be (PHP-generated):
   lidsblock.src = '<?php echo LUPOPEDIA_PUBLIC_PATH; ?>lupo-images/lids3.png';
   ```

2. **Ensure DynLayer is Loaded:**
   - Check if pages using the eye include dynlayer.js from the correct path
   - Update any hardcoded references to old dynlayer.js location

3. **Test Subdirectory Installation:**
   - Verify eye works when Lupopedia is installed in a subdirectory
   - Test image loading with LUPOPEDIA_PUBLIC_PATH

### Files to Check/Update

1. `lupo-includes/js/crafty_syntax_eyes.js` - Update image paths
2. Any PHP files that include the eye widget - ensure dynlayer.js is loaded from `/lupo-includes/js/dynapi/js/`
3. Template files that reference the eye - ensure proper path handling

### Implementation Notes

- The DynAPI library is a complete 1999 DHTML framework
- We're using only the DynLayer component for the eye animation
- No need for "complicated crap" - just use the library as-is
- The eye code has worked for 25+ years and should remain unchanged except for path fixes

### Next Session Priority

1. Fix image paths for subdirectory compatibility
2. Verify dynlayer.js loading from new location
3. Test the eye widget in subdirectory installation
4. Commit and push the updated implementation

**Remember**: The goal is minimal changes - just fix paths, don't rewrite working 1999 code!
