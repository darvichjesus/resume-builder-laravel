# 📄 DevSuite PRO - Resume Builder

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

**DevSuite PRO** is a premium, high-performance Resume Builder designed to create world-class CVs in seconds. Built with Laravel, it features 7 distinct professional templates, real-time preview, and seamless PDF export.

---

## ✨ Key Features

- **7 Professional Templates**: 
  - **Harvard (Traditional)**: Ivy League standard for excellence.
  - **Modern Dark (Premium)**: Sleek, high-contrast digital design.
  - **Creative (Colored)**: Vibrant layouts for creative professionals.
  - **Elegant (Serif)**: Timeless Georgia-based typography.
  - **Minimal (Clean)**: Focus on white space and readability.
  - **Executive (Classic)**: Robust 2-column sidebar layout.
  - **Professional (Compact)**: High-density data layout for experienced pros.
- **Dynamic Portfolio QR**: Real-time toggle to include a QR code linking to your digital portfolio.
- **Smart Skill Ratings**: Pure CSS-based rating system for maximum compatibility across all PDF engines.
- **Profile Photo Support**: Integrated photo management with circular and adjusted cropping.
- **Multilingual Ready**: Built-in translation support for global reach.
- **One-Page Optimized**: Intelligent spacing and layout to keep resumes professional and concise.

---

## 🛠️ Tech Stack

- **Core**: [Laravel 11+](https://laravel.com)
- **PDF Engine**: [Laravel DomPDF](https://github.com/barryvdh/laravel-dompdf)
- **UI Architecture**: Tailwind CSS & Vanilla CSS
- **QR Generation**: [Simple QrCode](https://github.com/SimpleSoftwareIO/simple-qrcode)
- **Logic**: RESTful PHP Architecture

---

## 🚀 Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/darvich/resume-builder-laravel.git
   cd resume-builder-laravel
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database & Storage**:
   ```bash
   php artisan migrate
   php artisan storage:link
   ```

5. **Run the application**:
   ```bash
   npm run dev
   php artisan serve
   ```

---

## 📸 Screenshots

*(Add your screenshots here after deployment)*

---

## 🛡️ License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

<p align="center">Built with ❤️ for professionals worldwide by <b>DevSuite PRO</b></p>
