# DSL Logo Setup Guide

## How to Add Your Logo Image

The login form has a ready-to-use logo container. Follow these steps to add your logo:

### Step 1: Place Your Logo File

Add your logo image to the `public/images/` folder:

```
public/
  └── images/
      └── dsl-logo.png  (or .jpg, .svg - whatever format you have)
```

**If the `public/images/` folder doesn't exist, create it first.**

### Step 2: Add Image Reference in Login Form

Open: `resources/views/auth/login.blade.php`

Find this section (around line 300):

```blade
<!-- DSL Logo Container - Add your logo image here -->
<div class="logo-container">
    <!-- Image will be placed here when added -->
</div>
```

Replace it with:

```blade
<!-- DSL Logo Container -->
<div class="logo-container">
    <img src="{{ asset('images/dsl-logo.png') }}" alt="DSL Logo">
</div>
```

**Note:** Change `dsl-logo.png` to match your actual filename.

### Step 3: Verify Path Structure

Your final file structure should look like:

```
BA_BARCODE_V1/
  ├── public/
  │   └── images/
  │       └── dsl-logo.png
  └── resources/
      └── views/
          └── auth/
              └── login.blade.php
```

### Image Requirements

- **Recommended size:** 200px × 180px maximum
- **Formats supported:** PNG, JPG, SVG
- **Background:** Should work with dark theme (dark navy background)
- **Styling applied automatically:** Drop shadow effect, hover scaling effect

### Current Styling Applied to Image

The CSS will automatically apply these effects to your image:

```css
.logo-container img {
    max-width: 200px;
    max-height: 180px;
    object-fit: contain;
    filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.6));
    transition: transform 0.3s ease;
}

.logo-container img:hover {
    transform: scale(1.05);  /* Scales up when hovered */
}
```

### Example Complete Code

If your logo filename is `dsl-logo.png`, here's what the complete section should look like:

```blade
<body>
    <div class="login-wrapper">
        <!-- DSL Logo Container -->
        <div class="logo-container">
            <img src="{{ asset('images/dsl-logo.png') }}" alt="DSL Logo">
        </div>

        <!-- Login Form -->
        <div class="login-container">
            <!-- Rest of login form -->
        </div>
    </div>
</body>
```

### Theme Information

**Current Theme:** Dark Navy (#1a1f3a → #0f3460)
- White text on dark background
- Glass morphism effect with dark overlay
- Professional drop shadow on logo

### Testing Your Logo

1. Place your image in `public/images/`
2. Update the HTML as shown above
3. Refresh the browser
4. Your logo should appear above the login form with professional styling applied

**If the logo doesn't appear, check:**
- ✓ File is in the correct `public/images/` folder
- ✓ Filename matches exactly (case-sensitive on Linux)
- ✓ File format is supported (PNG, JPG, SVG)
- ✓ Clear browser cache and refresh page
