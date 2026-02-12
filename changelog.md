# Changelog

All notable changes to this project will be documented in this file.

## [1.0.6] - 2026-02-12
### Added
- **"Free" Badge**: Integrated a prominent "Free" badge in the admin dashboard banner.
- **Updater Loading State**: Added a smooth rotation animation to the "Check Now" button to provide visual feedback during update checks.

### Changed
- **Dashboard Spacing**: Optimized overall admin UI spacing (margins, padding, and grid gaps) for a cleaner, more professional look.
- **Updater Reliability**: Reinforced GitHub API authentication headers for seamless private repository access.
- **Sidebar Header**: Enhanced sidebar header alignment to ensure branding elements are perfectly centered.

### Fixed
- Dashboard grid cards "jumbling" or overlapping on certain screen resolutions.
- Admin dashboard layout misalignments within the WordPress admin wrapper.

## [1.0.2] - 2026-02-12
### Added
- **Podify Branding**: Integrated the official Podify logo into the admin sidebar and dashboard banner.
- **Updater Assets**: Enhanced the WordPress "View version details" modal with the plugin icon and banner.

### Changed
- **GitHub Updater Overhaul**: Switched from zipball downloads to manual release assets for improved reliability and folder structure control.
- **Header Injection**: Implemented specific octet-stream header injection for secure private repository asset downloads.

## [1.0.1] - 2026-02-11
### Added
- **Modern Admin UI**: Complete overhaul of the admin dashboard matching the new blue gradient design.
- **Mobile Responsiveness**: Enhanced mobile-first layout for the admin dashboard with sticky navigation and stacked settings.
- **Clear Cache Action**: New "Clear Cache" quick action in the admin dashboard.

### Changed
- **Branding Update**: Updated all references to "Podify Inc." and "https://podify.com".
- **Contact Integration**: Renamed all "Support" sections to "Contact" and updated links to podify.com/contact.
- **UI Improvements**: Removed unnecessary "Visit Website" and "Tutorials" buttons for a cleaner interface.
- **Performance**: Removed debug logs from frontend scripts to improve execution speed.

### Fixed
- Admin dashboard layout misalignments on mobile devices.
- CSS versioning for better browser cache busting.

## [1.0.0] - 2026-02-11
### Added
- Private GitHub updater implementation.
- Support for `SEIC_GITHUB_TOKEN` constant for private repository updates.
- Folder name correction logic for GitHub ZIP downloads.

### Fixed
- Plugin versioning reset to 1.0.0.

### Changed
- Migrated from `readme.txt` to `changelog.md`.
