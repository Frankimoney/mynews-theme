# MyNews WordPress Theme Documentation

## Table of Contents
1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Theme Customization](#theme-customization)
4. [Premium Features](#premium-features)
   - [Breaking News Ticker](#breaking-news-ticker)
   - [Dark Mode](#dark-mode)
   - [Post Formats](#post-formats)
   - [Post Reactions](#post-reactions)
   - [Social Sharing](#social-sharing)
   - [Related Articles](#related-articles)
   - [Enhanced Author Profiles](#enhanced-author-profiles)
   - [Reading Progress Bar](#reading-progress-bar)
   - [Custom Layouts](#custom-layouts)
   - [Premium Ad System](#premium-ad-system)
5. [Widgets](#widgets)
6. [Shortcodes](#shortcodes)
7. [Performance Optimization](#performance-optimization)
8. [Troubleshooting](#troubleshooting)
9. [Support](#support)

---

## Introduction

**MyNews** is a premium WordPress theme designed specifically for news websites, blogs, magazines, and content publishers. With its clean, modern design and powerful features, MyNews helps you create a professional and engaging news website that keeps visitors coming back.

### Key Benefits

- **Professional Design**: Clean, modern layout that puts your content first
- **Mobile-First Approach**: Fully responsive design optimized for all devices
- **Speed Optimized**: Fast loading times with optimized code
- **SEO Ready**: Built with best SEO practices in mind
- **Premium Features**: Advanced functionality for content publishers
- **Easy Customization**: Intuitive customizer options with live preview
- **Regular Updates**: Ongoing theme improvements and new features

---

## Installation

1. Log in to your WordPress dashboard
2. Navigate to **Appearance > Themes > Add New**
3. Click on **Upload Theme**
4. Choose the `mynews.zip` file from your computer
5. Click **Install Now**
6. After installation, click **Activate**

### Recommended Plugins

For the best experience with MyNews, we recommend installing these plugins:

- **Yoast SEO**: For search engine optimization
- **W3 Total Cache**: For performance optimization
- **Safe SVG**: For SVG file upload support
- **Regenerate Thumbnails**: For regenerating image sizes after theme activation

---

## Theme Customization

MyNews comes with extensive customization options available in the WordPress Customizer:

1. Go to **Appearance > Customize** in your WordPress dashboard
2. Explore the following sections:
   - **Site Identity**: Logo, site title, favicon
   - **Colors**: Primary, secondary, and accent colors
   - **Theme Options**: Layout settings, typography, etc.
   - **Header Options**: Navigation style, search bar, etc.
   - **Footer Options**: Widget areas, copyright text
   - **Breaking News Ticker**: Configure ticker display and behavior
   - **Advertisement Settings**: Ad placement and configuration
   - **Post Display Options**: Featured posts, post meta, etc.

---

## Premium Features

### Breaking News Ticker

The Breaking News Ticker is a sleek, attention-grabbing feature that displays your most important news at the top of your site.

#### Features:
- Two sources: dedicated Breaking News posts or regular posts marked as breaking
- Customizable ticker speed and display options
- Expiration dates for breaking news items
- Display on homepage and/or single post pages
- Different urgency levels with unique styling
- Smooth scrolling or fade transitions

#### How to Use:
1. **Create Breaking News**:
   - Option 1: Go to **Posts > Add New**, then check "Mark as Breaking News" in the post sidebar
   - Option 2: Go to **Breaking News > Add New** to create a dedicated breaking news item
   
2. **Configure Display**:
   - Go to **Appearance > Customize > My News Theme Settings > Breaking News Ticker**
   - Enable/disable the ticker
   - Choose to show on homepage and/or single posts
   - Set transition speed, display mode, and scrolling options
   - Customize colors and fonts

3. **Urgency Levels**:
   - Set breaking news urgency to Normal, Important, or Urgent
   - Each level has distinct styling to indicate importance

4. **Expiration**:
   - Set an optional expiry date for breaking news items
   - Expired items are automatically removed from the ticker

### Dark Mode

MyNews includes a sophisticated dark mode that enhances readability in low-light conditions and reduces eye strain.

#### Features:
- System preference detection (follows device settings)
- User toggle option
- Persistent user preference (saves selection)
- Smooth transition between light/dark modes
- Customizable dark mode colors
- Optimized for readability and reduced eye strain

#### How to Enable:
1. Go to **Appearance > Customize > My News Theme Settings > Dark Mode**
2. Choose from the following options:
   - **Disabled**: No dark mode option
   - **User Choice**: Shows toggle in header for users to switch
   - **System Preference**: Automatically matches device setting
   - **Time-Based**: Switches based on time of day
   - **Always On**: Forces dark mode for all users

3. **Customize Dark Mode Colors**:
   - Background color
   - Text color
   - Accent color
   - Link color

4. **Troubleshooting**:
   If dark mode has display issues, use the troubleshooter:
   - Visit your site with `?dark_mode_troubleshoot=1` added to the URL
   - Use the diagnostic tools to identify and fix issues

### Post Formats

MyNews supports specialized post formats that enhance the presentation of different content types.

#### Audio Post Format

Perfect for podcasts, music releases, interviews, and any audio content.

**Features**:
- Dedicated audio player at the top of posts
- Playlist support for multiple audio files
- Compatible with self-hosted audio and services like SoundCloud
- Special styling in archive/grid/list views
- Automatic transcript support for accessibility

**Usage**:
1. Create a new post and select "Audio" from the Format options
2. Upload audio files or embed from external sources
3. Add optional audio metadata (artist, duration, etc.)

See [full audio post format documentation](doc-post-formats.md) for advanced options.

#### Video Post Format

Ideal for video content, tutorials, vlogs, and interviews.

**Features**:
- Prominent video player at the top of posts
- Responsive video container for all screen sizes
- Support for YouTube, Vimeo, and self-hosted videos
- Custom video poster images
- Special display in archive grids

**Usage**:
1. Create a new post and select "Video" from the Format options
2. Upload or embed your video content
3. Add a featured image to use as video thumbnail in listings

See [full video post format documentation](doc-post-formats.md) for advanced options.

### Post Reactions

Allow readers to react to your content with emoji reactions, similar to social media platforms.

#### Features:
- Five emoji reactions: Like, Love, Haha, Wow, and Sad
- Real-time reaction counter without page refresh
- One reaction per visitor (cookie-based tracking)
- Customizable reaction emojis
- Sortable posts by reaction count
- Popular reactions widget for sidebar

#### How to Use:
1. Reactions are automatically enabled on all posts
2. To customize:
   - Go to **Appearance > Customize > My News Theme Settings > Post Reactions**
   - Enable/disable specific reactions
   - Change emoji icons if desired
   - Adjust position (above/below content or in sidebar)

3. To display popular posts by reactions:
   - Go to **Appearance > Widgets**
   - Add the "MyNews: Popular Posts by Reaction" widget
   - Configure the widget settings (timeframe, reaction type, etc.)

### Social Sharing

Make it easy for readers to share your content across social media platforms.

#### Features:
- Clean, modern sharing buttons
- Multiple button styles (minimal, rounded, square, colorful)
- Customizable position (floating sidebar, above/below content)
- Share counters (optional)
- Support for Facebook, Twitter/X, LinkedIn, WhatsApp, Pinterest, Email, and more
- Copy link to clipboard option

#### Customization:
1. Go to **Appearance > Customize > My News Theme Settings > Social Sharing**
2. Choose which platforms to include
3. Select button style and position
4. Enable/disable share counts
5. Configure colors and hover effects

### Related Articles

Automatically display relevant articles at the end of each post to increase page views and engagement.

#### Features:
- Smart algorithm based on categories, tags, and content
- Customizable number of related posts (2-6)
- Multiple display layouts (grid, carousel, list)
- Options for displaying featured images
- Excerpt length control
- View count and date display options

#### Configuration:
1. Go to **Appearance > Customize > My News Theme Settings > Related Articles**
2. Choose matching criteria (categories, tags, or both)
3. Select number of articles to display
4. Choose layout style
5. Configure image size and meta information to display

### Enhanced Author Profiles

Showcase your writers with detailed author profiles that build audience connection and trust.

#### Features:
- Custom author profile images
- Social media links integration
- Author bio with rich text
- Featured posts by author
- Author expertise/specialty fields
- Author contact form (optional)
- Multiple author box layouts

#### Setting Up Author Profiles:
1. Go to **Users > Your Profile**
2. Fill in the standard WordPress biographical info
3. Upload a custom author image
4. Add social media profile links
5. Specify expertise/specialty areas
6. Select featured posts to highlight

Authors can be showcased on:
- Single post pages (below content)
- Author archive pages
- Dedicated author profile pages
- In the sidebar using the Authors widget

### Reading Progress Bar

Enhance user experience with a visual indicator of reading progress.

#### Features:
- Sleek progress bar at the top of the page
- Shows reading progress as users scroll through articles
- Customizable color and thickness
- Option to display percentage read
- Mobile-friendly
- Smooth animation

#### Configuration:
1. Go to **Appearance > Customize > My News Theme Settings > Single Post**
2. Enable Reading Progress Bar
3. Choose position (top of page or below header)
4. Select bar color and thickness
5. Enable/disable on mobile devices

### Custom Layouts

MyNews offers multiple layout options for different types of content pages.

#### Homepage Layouts:
- **Classic News**: Traditional newspaper-style layout
- **Magazine**: Featured posts with grid below
- **Blog**: Chronological post listing
- **Showcase**: Image-focused with large featured posts

#### Archive Layouts:
- **Grid View**: Card-based layout with featured images
- **List View**: Traditional blog listing with images
- **Masonry**: Pinterest-style variable height grid
- **Compact**: Space-efficient listing for many posts

#### Single Post Layouts:
- **Standard**: Clean, focused reading experience
- **Featured Image Header**: Large image at the top
- **Narrow Content**: Extra-readable centered content
- **Wide**: Full-width layout for immersive content

#### Configuration:
1. Go to **Appearance > Customize > My News Theme Settings > Layout Options**
2. Select default layouts for different page types
3. Configure columns, spacing, and container width
4. Enable/disable sidebar on different page types

### Premium Ad System

Maximize your revenue with the advanced ad management system built into MyNews.

#### Features:
- 7 strategic ad placements optimized for visibility and CTR
- Sticky sidebar ads that stay in view while scrolling
- In-feed ads between posts in archive/category pages
- Ad rotation for multiple advertisers
- Responsive ad containers for all device sizes
- AdSense optimization
- Ad scheduling and expiration
- Device-specific ad targeting

For complete details on the ad system, see the [Ad Placement Guide](inc/ad-placement-guide.md).

---

## Widgets

MyNews includes several custom widgets to enhance your sidebar and footer areas:

1. **MyNews: Featured Posts**: Display visually appealing featured posts
2. **MyNews: Popular Posts**: Show posts by view count or comment count
3. **MyNews: Popular Reactions**: Display posts with the most reactions
4. **MyNews: Social Follow**: Add buttons linking to your social profiles
5. **MyNews: Author Profile**: Showcase specific author information
6. **MyNews: Newsletter**: Email signup form with customizable fields
7. **MyNews: Video Widget**: Display featured video content
8. **MyNews: Ad Space**: Dedicated widget for advertisement code

To use these widgets:
1. Go to **Appearance > Widgets**
2. Drag and drop the desired widgets into sidebar or footer areas
3. Configure each widget's settings as needed

---

## Shortcodes

MyNews includes helpful shortcodes for inserting dynamic content:

### Basic Shortcodes:
- `[mynews_featured_posts]`: Display featured posts anywhere
- `[mynews_breaking_news]`: Insert breaking news ticker
- `[mynews_author_box]`: Show author information
- `[mynews_social_share]`: Insert social sharing buttons
- `[mynews_related_posts]`: Display related articles
- `[mynews_reactions]`: Show post reaction buttons

### Advanced Shortcodes:
- `[mynews_posts category="news" count="4" layout="grid"]`: Display posts with parameters
- `[mynews_ad_space id="sidebar-top"]`: Insert specific ad placement
- `[mynews_subscribe]`: Add newsletter subscription form
- `[mynews_trending]`: Show trending posts based on views or reactions

**Example Usage**:
```
[mynews_featured_posts count="3" layout="carousel" category="politics"]
```

See the shortcode reference in the theme dashboard for complete documentation.

---

## Performance Optimization

MyNews is built with performance in mind:

1. **Optimized Code**: Clean, efficient code base
2. **Lazy Loading**: Images load only when scrolled into view
3. **Minified Resources**: CSS and JS files are minified
4. **Resource Prioritization**: Critical CSS is loaded first
5. **CDN Support**: Compatible with content delivery networks
6. **Cache Support**: Works with popular caching plugins
7. **Reduced HTTP Requests**: Combined CSS/JS files

To further optimize:
1. Go to **Appearance > Customize > My News Theme Settings > Performance**
2. Enable/disable features based on your needs
3. Configure lazy loading settings
4. Choose which scripts to defer loading

---

## Troubleshooting

### Common Issues and Solutions

1. **Breaking News Ticker Not Working**
   - Check if the ticker is enabled in Customizer
   - Verify you have active breaking news items
   - Make sure scripts are not being blocked

2. **Dark Mode Issues**
   - Use the Dark Mode Troubleshooter (`?dark_mode_troubleshoot=1`)
   - Clear browser cache and cookies
   - Check for plugin conflicts

3. **Layout Problems**
   - Verify your content width settings
   - Check for custom CSS conflicts
   - Test with default WordPress content

4. **Ad Display Issues**
   - Confirm ad code is correctly formatted
   - Verify ad dimensions match container
   - Check if ad blockers affect display

5. **Social Sharing Not Working**
   - Ensure the site is publicly accessible
   - Check console for JavaScript errors
   - Verify no security plugins block sharing APIs

---

## Support

For additional help with MyNews:

- **Documentation**: [Online Knowledge Base](https://mynews-theme.com/docs)
- **Video Tutorials**: [YouTube Channel](https://youtube.com/mynews-theme)
- **Support Forum**: [Community Support](https://mynews-theme.com/forum)
- **Priority Support**: [Submit a Ticket](https://mynews-theme.com/support)
- **Theme Updates**: Regular updates via WordPress dashboard

For custom theme modifications, consider hiring a WordPress developer or contact us for custom development services.
