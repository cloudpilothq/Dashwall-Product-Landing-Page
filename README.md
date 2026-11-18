
# Dashwall – Customized WordPress Theme with Product Landing Pages

A customized version of the **Dashwall WordPress theme**, extended with a dedicated product landing-page system developed for [MixerDecoded.com](https://mixerdecoded.com/).

The customization adds a purpose-built page template for presenting individual products through structured product information, key features, specifications, images, purchase links, and links to detailed editorial reviews.

## Live Example

See the custom product landing-page design in action:

**[View the HAUSWIRT M5 Max Product Landing Page](https://mixerdecoded.com/hauswirt-m5-max-for-sale/)**

This page demonstrates how the customized template presents product information and purchase options in a focused, standalone layout.

## Overview

This repository contains a customized implementation of the Dashwall WordPress theme.

The original theme has been adapted with additional functionality for creating product-focused landing pages. The landing-page system is intended to complement traditional editorial content, such as product reviews, comparisons, and buying guides.

Rather than replacing the theme's standard website layout, the custom landing-page template uses its own dedicated header, footer, styling, and page structure.

## Features

The custom product landing-page system includes:

- **Dedicated product hero section** – Introduces the product with its name, primary image, and supporting information.
- **Product purchase section** – Presents purchase information and calls to action.
- **Key product highlights** – Displays important product features in a concise format.
- **Product overview** – Provides additional information about the product.
- **Essential specifications** – Organizes relevant product specifications into a structured table.
- **Product image gallery** – Displays additional product images.
- **Optional product video** – Supports an embedded video where one is available.
- **Detailed review link** – Connects visitors to the corresponding in-depth editorial review.
- **Final call to action** – Provides another opportunity for visitors to visit the purchase destination.
- **Dedicated landing-page header and footer** – Gives the landing page its own presentation without replacing the standard theme header and footer.
- **Responsive layout** – Adapts the landing-page presentation to different screen sizes.

The information displayed on each landing page is configured through the WordPress administration interface.

## WordPress Integration

The custom functionality is integrated into the WordPress theme and includes:

- A dedicated product landing-page template.
- Custom administration fields for entering product information.
- WordPress post metadata for storing landing-page content.
- A separate header and footer for the landing-page template.
- Dedicated CSS and JavaScript assets.

The template is designed to work within the customized Dashwall theme rather than as a standalone WordPress plugin.

## Project Structure

The custom landing-page functionality is organized across the following theme files and directories:

```text
mixerdecoded/
├── page-templates/
│   └── premium-product-landing.php
├── inc/
│   └── mixerdecoded-product-landing.php
├── assets/
│   ├── css/
│   │   └── product-landing.css
│   └── js/
│       └── product-landing.js
├── header-landing.php
└── footer-landing.php
```

The remaining files and directories belong to the underlying Dashwall theme and its other customizations.

## Installation

1. Download or clone this repository.
2. Locate the `mixerdecoded` theme directory.
3. Upload the theme directory to your WordPress installation's `wp-content/themes/` directory.
4. Activate the theme from **WordPress Admin → Appearance → Themes**.
5. Create a new WordPress page or edit an existing one.
6. Select the custom product landing-page template in the page template settings.
7. Enter the relevant product information using the available administration fields.
8. Publish or update the page.
9. Review the result on desktop and mobile devices.

**Important:** This is a customized theme implementation. Before installing it on an existing website, back up your files and database. Test the theme in a staging environment first, particularly if your WordPress installation already uses a modified version of Dashwall.

## Customization

The landing-page content is managed through the WordPress administration interface.

Depending on the available fields, page editors can configure information such as:

- Product name and introductory content.
- Product images.
- Key product highlights.
- Product overview.
- Essential specifications.
- Purchase information and destination links.
- Optional video content.
- Links to detailed product reviews.

The visual presentation and functionality can be further customized by editing the dedicated template, CSS, and JavaScript files.

## Intended Use

The custom landing-page system is designed for product-focused publishing websites, particularly websites that publish:

- Product reviews.
- Buying guides.
- Product comparisons.
- Product recommendations.
- Individual product information pages.

It provides a separate presentation format for individual products while allowing the main website to retain its regular editorial structure.

## Original Theme and Attribution

This project is based on the **Dashwall WordPress theme**.

The product landing-page functionality and related customizations were developed for MixerDecoded.com and should be distinguished from the original Dashwall theme.

The original theme's authorship, copyright notices, and licensing terms remain subject to the original theme's license. Review the repository's license file and original theme documentation before redistributing or reusing the underlying theme.

## Project Links

- **Website:** [MixerDecoded.com](https://mixerdecoded.com/)
- **Live Product Landing Page:** [HAUSWIRT M5 Max](https://mixerdecoded.com/hauswirt-m5-max-for-sale/)
- **GitHub Repository:** [Dashwall – Customized WordPress Theme](https://github.com/cloudpilothq/Dashwall-Product-Landing-Page)

---

**Developed and customized for MixerDecoded.com.**
