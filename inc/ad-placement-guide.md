# Premium Ad Placements Guide

This guide explains how to use the premium ad placements in the MyNews WordPress theme.

## Available Ad Placements

The MyNews theme includes 7 strategic ad placements optimized for high visibility and click-through rates:

1. **Header Ad** - High visibility placement below navigation, above content
2. **Below Post Title Ad** - Prime location immediately after the post title and before content
3. **Mid Content Ad** - Inserted within post content for native-looking integration
4. **After Content Ad** - Shown after post content when readers have completed the article
5. **Sidebar Top Ad** - Premium placement at the top of the sidebar
6. **Sidebar Bottom Ad** - Secondary placement at the bottom of the sidebar
7. **Footer Ad** - Site-wide placement above the footer

## Advanced Ad Features

In addition to the standard ad placements, MyNews includes these advanced advertising features:

### Sticky Ads
- Make sidebar ads stick to the viewport as users scroll for increased visibility and CTR
- Configurable top offset to match your site's header size
- Automatically disabled on mobile to preserve user experience

### In-Feed Ads
- Insert native-looking ads between posts in archive, category, and home pages
- Configurable position (after X number of posts)
- Option to repeat ads throughout long lists of posts
- Compatible with both grid and list layouts

### Video Ad Support
- Display video advertisements in any placement
- Supports YouTube, Vimeo, and custom HTML5 video players
- Optional autoplay when in viewport (muted)
- Optimized for responsive layouts and mobile devices

## Setting Up Ad Placements

### Step 1: Access Widget Areas

1. Log in to your WordPress admin dashboard
2. Go to **Appearance > Widgets**
3. You'll see dedicated widget areas for each ad placement:
   - Header Ad
   - Below Post Title Ad
   - Mid Content Ad
   - After Content Ad
   - Sidebar Top Ad
   - Sidebar Bottom Ad
   - Footer Ad

### Step 2: Add Ad Code

1. Expand the ad widget area where you want to place an ad
2. Click "Add a Widget" button
3. For Google AdSense:
   - Add a "Custom HTML" widget 
   - Paste your AdSense code into the widget
   - Click "Save"
4. For affiliate banners or custom ads:
   - Add an "Image" widget for simple banner ads
   - Add a "Custom HTML" widget for ads with tracking scripts
   - Configure the widget settings and save

### Step 3: Customize Ad Settings

1. Go to **Appearance > Customize > Advertisement Settings**
2. Configure global ad settings:
   - Enable/disable all ad placements with the master switch
   - Toggle "Advertisement" labels for compliance
   - Hide ads from admin users for testing
3. Control individual ad placements:
   - Enable/disable specific ad locations
   - Adjust ad density (minimal, balanced, or monetized)
   - Set paragraph count before mid-content ad
4. Configure advanced ad features:
   - Enable/disable sticky sidebar ads
   - Configure in-feed ad positions and repetition
   - Set up video ad placements and autoplay behavior

### Step 4: Setting Up Advanced Ad Features

#### For Sticky Ads:
1. Go to **Appearance > Customize > Advertisement Settings > Advanced Ad Features**
2. Enable "Sticky Sidebar Ad" option
3. Configure the "Sticky Ad Top Offset" to match your header height
4. Add your ad code to the "Sidebar Top Ad" widget area

#### For In-Feed Ads:
1. Go to **Appearance > Customize > Advertisement Settings > Advanced Ad Features**
2. Enable "In-Feed Ads" option 
3. Set "In-Feed Ad Position" to determine placement (e.g., after 3 posts)
4. Optional: Enable "Repeat In-Feed Ads" to show multiple ads in long post lists
5. Add your ad code to the "In-Feed Ad" widget area

#### For Video Ads:
1. Go to **Appearance > Customize > Advertisement Settings > Advanced Ad Features**
2. Enable "Video Ad Support" option
3. Choose "Video Ad Position" (e.g., After Content)
4. Configure autoplay behavior if desired
5. Add your video ad code to the corresponding widget area

## Responsive Design

All ad placements are fully responsive and will adapt to different screen sizes:

- On desktop: Ads display at optimal sizes for maximum visibility with sticky options
- On tablets: Ads scale appropriately to maintain readability
- On mobile: Ads are optimized for smaller screens to maintain a good user experience

## Ad Best Practices

1. **Maintain balance**: Too many ads can hurt user experience. Start with 2-3 strategic placements.
2. **Test different placements**: Monitor which positions generate the highest CTR for your audience.
3. **Use responsive ad units**: For AdSense, use responsive ad units to maximize compatibility.
4. **Comply with regulations**: Always follow Google AdSense policies and other advertising regulations.
5. **Monitor performance**: Regularly check your ad performance metrics and adjust placements as needed.
6. **Use sticky ads strategically**: Don't make all ads sticky; focus on high-value sidebar placements.
7. **Optimize video ads**: Keep video ads short (15-30 seconds) and ensure they're lightweight.
8. **Blend in-feed ads naturally**: Style in-feed ads to match your content layout for better engagement.

## Troubleshooting

- If ads are not displaying, check if they're enabled in the Customizer
- For mid-content ads not appearing, ensure posts have enough paragraphs (default: 4 paragraphs)
- If sticky ads aren't working, check for CSS conflicts with other theme elements
- For video ads not autoplaying, ensure your code is compatible with autoplay policies
- If in-feed ads don't appear, verify they're enabled and the position setting is correct
- Review browser console for JavaScript errors that might affect ad display
- Verify ad code is correctly formatted in the widget

## Performance Considerations

- Video ads may impact page load speed - consider lazy loading options
- For sticky ads, ensure they don't cause layout shifts that could affect Core Web Vitals
- In-feed ads add additional DOM elements - monitor performance on archive pages with many posts
- Consider using the "minimal" or "balanced" ad density settings for optimal user experience

For additional support, please contact the theme developer.
