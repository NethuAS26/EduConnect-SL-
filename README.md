# 🎓 EduConnect-SL

<div align="center">

**Revolutionizing Higher Education in Sri Lanka**

*A centralized web-based platform designed to revolutionise higher education in Sri Lanka by improving transparency, accessibility, and decision-making for students and institutions alike.*

🚀 *Website Link:* 👉 [http://localhost/EduConnectSL/index.php]

[![GitHub Stars](https://img.shields.io/github/stars/NethuAS26/EduConnect-SL-?style=social)](https://github.com/NethuAS26/EduConnect-SL-/stargazers)
[![GitHub Forks](https://img.shields.io/github/forks/NethuAS26/EduConnect-SL-?style=social)](https://github.com/NethuAS26/EduConnect-SL-/network/members)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

[🚀 Live Demo](#) • [🐛 Report Bug](https://github.com/NethuAS26/EduConnect-SL-/issues) • [✨ Request Feature](https://github.com/NethuAS26/EduConnect-SL-/issues)

</div>

## 🌟 About

EduConnect-SL is a transformative digital platform for Sri Lanka's higher education system, providing a unified ecosystem for students, institutions, and educational stakeholders to make informed decisions through enhanced transparency and accessibility.

## ✨ Features

### For Students
- 🔍 Institution discovery and program comparison
- 📊 Academic progress tracking
- 💬 Student community and networking
  
### For Institutions
- 📋 Comprehensive profile management
- 📊 Analytics dashboard and insights
- 📢 Direct student communication
- 📝 Streamlined admission processes

### For Administrators
- 👥 User and role management
- 📊 Platform analytics
- 🔒 Security and privacy controls
- 📋 Content moderation tools

## 🛠️ Tech Stack

**Frontend:** React.js, TypeScript, Tailwind CSS, Redux
**Backend:** Node.js, Express.js, JWT Authentication
**Database:** MongoDB, Redis
**Tools:** Docker, GitHub Actions, AWS

## 🚀 Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/NethuAS26/EduConnect-SL-.git
   cd EduConnect-SL-
   ```

2. **Install dependencies**
   ```bash
   cd server && npm install
   cd ../client && npm install
   ```

3. **Environment setup**
   ```bash
   cp server/.env.example server/.env
   cp client/.env.example client/.env
   # Update environment variables
   ```

4. **Run the application**
   ```bash
   # Terminal 1 - Backend
   cd server && npm run dev
   
   # Terminal 2 - Frontend  
   cd client && npm start
   ```

5. **Access**
   - Frontend: http://localhost:3000
   - Backend API: http://localhost:5000

## 📁 Project Structure

```
EduConnect-SL-/
├── client/          # React frontend
├── server/          # Node.js backend
├── docs/            # Documentation
├── scripts/         # Deployment scripts
└── tests/           # Test files
```

## 🔗 API Endpoints

**Authentication**
```
POST /api/auth/register
POST /api/auth/login  
GET  /api/auth/me
```

**Institutions**
```
GET  /api/institutions
GET  /api/institutions/:id
POST /api/institutions
```

**Programs**
```
GET  /api/programs
GET  /api/programs/search
POST /api/programs
```

## 🤝 Contributing

1. Fork the project
2. Create feature branch (`git checkout -b feature/NewFeature`)
3. Commit changes (`git commit -m 'Add NewFeature'`)
4. Push to branch (`git push origin feature/NewFeature`)
5. Open Pull Request

## 📞 Support

- 📧 Email: support@educonnect-sl.com
- 🐛 Issues: [GitHub Issues](https://github.com/NethuAS26/EduConnect-SL-/issues)

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Ministry of Higher Education, Sri Lanka
- University Grants Commission (UGC)
- Sri Lankan universities and colleges
- Open source community

---

<div align="center">

**Made with ❤️ for Sri Lankan Education System**

</div>
