
# Helcim CTF - Laravel Edition

![image](https://github.com/user-attachments/assets/4b2606db-61a4-4855-872e-cc9183e10745)

This project is a deliberately vulnerable Laravel-based Invoice Management System built for Capture The Flag (CTF) exercises. It simulates real-world security issues and is intended for educational purposes only.

> ⚠️ **Warning:** This application is intentionally vulnerable. The author do not hold any liability to use the project code in production enviroment under any cicumstances.



---

## 🚀 Features

- Role-based access control/User Management
- Invoice creation and public viewing  
- Customer management  
- Internal paid features  
- User feedback mechanism
- New Feature Request functionality
- Dockerized for easy deployment  

---

## 🛠️ Prerequisites

- [Docker](https://www.docker.com/)  
- [Docker Compose](https://docs.docker.com/compose/)  

---

## ⚙️ Installation & Setup

1. **Clone the repository**  
   ```bash
   git clone https://github.com/prashant-singh-cybersec/helcim-ctf-laravel.git
   ```

2. **Change into the project directory**  
   ```bash
   cd helcim-ctf-laravel
   ```

3. **Start the containers**  
   ```bash
   sudo docker compose up --build
   ```
   This command will build the Docker images and launch both the Laravel application  along with Nginx and its PostgreSQL database.

---

## ✅ Verify Installation

Open your browser and navigate to:

```
http://127.0.0.1:8081 Or http://localhost:8081
```

You should see the CTF application’s landing page.

---

---


## 🎯 Usage

1. **Log in** as a **Regular User** into **Organization ID 1**.  
2. **Escalate** your role to **Admin** (explore the intentional privilege escalation path).  
3. **Create Customer** objects.  
4. **Issue Invoices** to those Customers.  
5. Share issued invoices via a **public link**.
6. Play with every **given/hidden** functionality within the application.

### Challenges & Vulnerabilities

- Cross-Site Scripting (XSS)  
- Content Security Policy (CSP) bypasses  
- CSRF bypass  
- Token forgery  
- Broken Access Control (BAC)  
- Insecure Direct Object Reference (IDOR)  
- SQL Injection (SQLi)  
- Server-Side Request Forgery (SSRF)  
- Local File Inclusion (LFI)  
- Passive OSINT–based flags  
- Business logic flaws  

---

## 🙌 Contributing

Feel free to fork the repo and raise pull requests for any improvements or new challenge ideas.

---

## Author

**Prashant Singh**  
[GitHub Profile](https://github.com/prashant-singh-cybersec)

