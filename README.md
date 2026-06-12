# Hogwarts Academy Portal
## Responsi Praktikum Pemrograman Web 1

### Informasi Project

| Keterangan    | Detail                                   |
| ------------- | ---------------------------------------- |
| Mata Kuliah   | Praktikum Pemrograman Web 1               |
| Tema Project  | Harry Potter                             |
| Jenis Website | Portal Akademik Bertema Harry Potter     |
| Teknologi     | PHP Native, HTML, CSS, JavaScript, MySQL |
| Tools         | Laragon, VS Code, Figma, GitHub          |
| Database      | MySQL                                    |

---

# Anggota Kelompok

| No | Nama             | NIM       | Role                |
| -- | ---------------- | --------- | ------------------- |
| 1  | Ardina Jihan Mariska| H1H024018 | Front-End Developer |
| 2  | Kaira Meilasya Nayada | H1H024020 | UI/UX Designer |
| 3  | Refan Nur Chandra | H1H024029 | Back-End Developer  |

---

# Deskripsi Project

Hogwarts Academy Portal merupakan website portal akademik bertema Harry Potter yang menghadirkan pengalaman belajar interaktif di dunia sihir Hogwarts.
Pengguna dapat mendaftar sebagai siswa Hogwarts, memilih House, menjelajahi berbagai Course (mata pelajaran sihir), mempelajari Spell (mantra), mengumpulkan XP, dan meningkatkan Level Wizard mereka.
Website ini menggabungkan konsep portal akademik dengan gamification sederhana sehingga pengguna tidak hanya membaca informasi, tetapi juga dapat melakukan eksplorasi course dan spell untuk memperoleh XP dan naik level.

Terdapat dua role utama pada sistem:

### Student / User

* Registrasi akun
* Login
* Melihat Dashboard
* Menjelajahi Course
* Mempelajari Spell
* Melihat informasi House Hogwarts
* Mengumpulkan XP
* Meningkatkan Level Wizard
* Melihat Profile

### Admin / Professor

* Login Admin
* Mengelola Data Student
* Mengelola Data Course
* Mengelola Data Spell
* Melakukan CRUD (Create, Read, Update, Delete)

---

# Konsep Gamification

Sistem gamification dibuat sederhana agar realistis untuk dikembangkan menggunakan PHP Native.

### XP System

User akan memperoleh XP ketika:

* Explore Course
* Learn Spell

Contoh:

| Aktivitas      | XP     |
| -------------- | ------ |
| Explore Course | +200 XP |
| Learn Spell    | +50 XP |

---

### Level System

Level Wizard terdiri dari:

| Level           | XP           |
| --------------- | ------------ |
| Beginner Wizard | 0 - 499 XP   |
| Advanced Wizard | 500 - 1499 XP |
| Expert Wizard   | 1500+ XP      |
Semakin banyak course dan spell yang dieksplorasi, semakin tinggi level wizard pengguna.

---

# Hogwarts Houses

House digunakan sebagai identitas siswa dan dipilih saat registrasi.

### Gryffindor
* Bravery
* Courage
* Determination

### Slytherin
* Ambition
* Leadership
* Cunning

### Ravenclaw
* Wisdom
* Creativity
* Intelligence

### Hufflepuff
* Loyalty
* Patience
* Hard Work

---

# Fitur Utama

### Student

* Register
* Login
* Dashboard
* Course Exploration
* Spell Learning
* Profile
* XP Tracking
* Level Progression

### Admin

* Dashboard
* Manage Students
* Manage Courses
* Manage Spells
* CRUD Students
* CRUD Courses
* CRUD Spells

---

# Sitemap

## Public

```text
Landing Page
├── Home
└── About
```

## Student

```text
Dashboard
├── Courses
│   └── Course Detail (Popup)
├── Spell Book
│   └── Spell Detail (Popup)
├── Profile
└── Logout
```

## Admin

```text
Dashboard
├── Manage Students
│   └── Student Form
├── Manage Courses
│   └── Course Form
├── Manage Spells
│   └── Spell Form
└── Logout
```
---

# UI/UX Concept
Link Figma :
Tema desain menggunakan konsep:

### Modern Dark Fantasy

Inspirasi:

* Hogwarts Castle
* Wizard World
* Magical Library
* Dark Academia

Warna utama:

| Warna       | Hex     |
| ----------- | ------- |
| Dark Navy   | #091821 |
| Gold Accent | #FFC300 |
| Cream       | #F6E9C8 |
| Dark Gray   | #4A4A4A |

Typography:

* Cinzel
* Cinzel Decorative
* Poppins
* Harry P

---

# Database Overview

## users

| Field    |
| -------- |
| id       |
| username |
| email    |
| password |
| house    |
| xp       |
| level    |
| role     |

---

## courses

| Field       |
| ----------- |
| id          |
| course_name |
| professor   |
| difficulty  |
| xp_reward   |
| description |

---

## spells

| Field       |
| ----------- |
| id          |
| spell_name  |
| spell_type  |
| difficulty  |
| xp_reward   |
| description |

---

# Project Structure

```text
hogwarts-academy-portal

├── assets
│   ├── css
│   ├── js
│   ├── img
│   └── fonts
│
├── components
│
├── pages
│   ├── admin
│   └── student
│
├── config
│
├── actions
│
├── database
│
├── README.md
│
└── .gitignore
```


---
HASIL WEBSITE

## Home
![Home](TAMPILAN%20WEBSITE/home.png)

## Login
![Login](TAMPILAN%20WEBSITE/login.png)

## Register
![Register](TAMPILAN%20WEBSITE/register.png)

## Student Dashboard
![Student Dashboard](TAMPILAN%20WEBSITE/studentdashboard.png)

## Admin Dashboard
![Admin Dashboard](TAMPILAN%20WEBSITE/admindashboard.png)

## Profile
![Profile](TAMPILAN%20WEBSITE/profile.png)

## Spell Book
![Spell Book](TAMPILAN%20WEBSITE/spellbook.png)

## Courses
![Courses](TAMPILAN%20WEBSITE/courses.png)

## Student CRUD
![Student CRUD](TAMPILAN%20WEBSITE/studentcrud.png)

## Spell CRUD
![Spell CRUD](TAMPILAN%20WEBSITE/spellcrud.png)

## Course CRUD
![Course CRUD](TAMPILAN%20WEBSITE/coursecrud.png)

## Add Course Form
![Add Course Form](TAMPILAN%20WEBSITE/formcourse.png)

## Add Spell Form
![Add Spell Form](TAMPILAN%20WEBSITE/formspell.png)

## Add Student Form
![Add Student Form](TAMPILAN%20WEBSITE/formstudent.png)

## Course Modal
![Course Modal](TAMPILAN%20WEBSITE/modalcourse.png)

## Spell Modal
![Spell Modal](TAMPILAN%20WEBSITE/modalspel.png)

## Logout
![Logout](TAMPILAN%20WEBSITE/logout.png)

## Logout Confirmation
![Logout Confirmation](TAMPILAN%20WEBSITE/logout2.png)

## About
![About](TAMPILAN%20WEBSITE/about.png)

# Catatan

Project ini dibuat untuk memenuhi tugas Responsi Praktikum Pemrograman Web dengan menerapkan konsep UI/UX modern, PHP Native, MySQL Database, dan sistem gamification sederhana bertema Harry Potter.
