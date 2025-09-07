<?php
session_start();
require_once 'config.php';

// Redirect if already logged in
if(isset($_SESSION['user_id'])) {
    header('Location: student-dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign Up form - EduConnect SL</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #2563eb;
      --primary-dark: #1d4ed8;
      --primary-light: #dbeafe;
      --secondary-color: #10b981;
      --secondary-dark: #059669;
      --accent-color: #f59e0b;
      --text-primary: #1f2937;
      --text-secondary: #6b7280;
      --text-light: #9ca3af;
      --bg-primary: #ffffff;
      --bg-secondary: #f9fafb;
      --bg-tertiary: #f3f4f6;
      --border-color: #e5e7eb;
      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
      --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
      --border-radius: 8px;
      --border-radius-lg: 12px;
      --transition: all 0.3s ease;
      --gradient-primary: linear-gradient(135deg, #2563eb, #1d4ed8);
      --gradient-secondary: linear-gradient(135deg, #10b981, #059669);
      --gradient-hero: linear-gradient(135deg, rgba(37, 99, 235, 0.95), rgba(16, 185, 129, 0.9));
      --gradient-subtle: linear-gradient(135deg, #f8fafc, #f1f5f9);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #93c5fd 0%, #60a5fa 25%, #6ee7b7 50%, #34d399 75%, #93c5fd 100%);
      color: var(--text-primary);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: 
        radial-gradient(circle at 20% 80%, rgba(147, 197, 253, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(110, 231, 183, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(96, 165, 250, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 60% 60%, rgba(52, 211, 153, 0.1) 0%, transparent 50%);
      pointer-events: none;
      z-index: -1;
    }

    body::after {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: 
        linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.06) 50%, transparent 70%),
        linear-gradient(-45deg, transparent 30%, rgba(255, 255, 255, 0.03) 50%, transparent 70%);
      pointer-events: none;
      z-index: -1;
    }

    .page-title {
      position: absolute;
      top: 30px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 2.5rem;
      font-weight: 800;
      background: var(--gradient-primary);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      text-align: center;
      letter-spacing: -0.02em;
    }

    .signup-container {
      width: 100%;
      max-width: 1100px;
      background: var(--bg-primary);
      border-radius: 24px;
      box-shadow: var(--shadow-xl);
      overflow: hidden;
      display: flex;
      min-height: 500px;
      position: relative;
      border: 1px solid rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(20px);
    }

    .signup-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--gradient-primary);
    }

    .form-section {
      flex: 1;
      padding: 40px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background: var(--bg-primary);
      position: relative;
    }

    .welcome-section {
      flex: 1;
      background: var(--gradient-hero);
      padding: 40px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .welcome-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: 
        radial-gradient(circle at 30% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 70% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
      pointer-events: none;
    }

    .welcome-content {
      position: relative;
      z-index: 2;
    }

    .welcome-title {
      font-size: 2.5rem;
      font-weight: 800;
      margin-bottom: 15px;
      color: white;
      line-height: 1.1;
      letter-spacing: -0.02em;
    }

    .welcome-text {
      font-size: 1rem;
      line-height: 1.6;
      opacity: 0.95;
      max-width: 400px;
      margin: 0 auto;
      font-weight: 400;
      color: #f8fafc;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .welcome-features {
      margin-top: 30px;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .feature-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: var(--border-radius-lg);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .feature-icon {
      width: 35px;
      height: 35px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
    }

    .feature-text {
      font-size: 0.9rem;
      font-weight: 500;
    }

    .form-header {
      margin-bottom: 30px;
      text-align: center;
    }

    .form-header h2 {
      font-size: 2rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 8px;
      background: var(--gradient-primary);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .form-header p {
      color: var(--text-secondary);
      font-size: 1rem;
      font-weight: 400;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: var(--text-primary);
      font-weight: 600;
      font-size: 0.95rem;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 14px 18px;
      border: 2px solid var(--border-color);
      border-radius: var(--border-radius-lg);
      font-size: 1rem;
      transition: var(--transition);
      background: var(--bg-secondary);
      color: var(--text-primary);
      font-weight: 500;
    }

    .form-group input:focus,
    .form-group select:focus {
      outline: none;
      border-color: var(--primary-color);
      background: var(--bg-primary);
      box-shadow: 0 0 0 4px var(--primary-light);
    }

    .input-icon {
      position: relative;
    }

    .input-icon i {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-light);
      font-size: 1.1rem;
      transition: var(--transition);
    }

    .input-icon input:focus + i {
      color: var(--primary-color);
    }

    .input-icon input {
      padding-left: 50px;
    }

    .form-row {
      display: flex;
      gap: 15px;
    }

    .form-row .form-group {
      flex: 1;
    }

    .form-options {
      display: flex;
      align-items: flex-start;
      margin-bottom: 20px;
      font-size: 0.9rem;
      text-align: left;
    }

    .form-options label {
      display: flex;
      align-items: flex-start;
      cursor: pointer;
      line-height: 1.5;
      color: var(--text-primary);
      font-weight: 500;
    }

    .form-options input[type="checkbox"] {
      margin-right: 12px;
      width: auto;
      margin-top: 2px;
      accent-color: var(--primary-color);
    }

    .form-options a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
      transition: var(--transition);
    }

    .form-options a:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    .auth-btn {
      width: 100%;
      padding: 14px;
      background: var(--gradient-primary);
      color: white;
      border: none;
      border-radius: var(--border-radius-lg);
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      transition: var(--transition);
      margin-bottom: 20px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .auth-btn:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
      background: var(--gradient-secondary);
    }

    .auth-btn.loading {
      position: relative;
      color: transparent;
    }

    .auth-btn.loading::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 20px;
      height: 20px;
      border: 2px solid transparent;
      border-top: 2px solid white;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: translate(-50%, -50%) rotate(0deg); }
      100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    .auth-divider {
      margin: 25px 0;
      display: flex;
      align-items: center;
      color: var(--text-secondary);
      font-size: 0.9rem;
      font-weight: 500;
    }

    .auth-divider::before,
    .auth-divider::after {
      content: "";
      flex: 1;
      height: 1px;
      background: var(--border-color);
    }

    .auth-divider span {
      padding: 0 20px;
    }

    .social-login {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
    }

    .social-btn {
      flex: 1;
      padding: 14px;
      border: 2px solid var(--border-color);
      border-radius: var(--border-radius-lg);
      background: var(--bg-primary);
      color: var(--text-primary);
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      font-size: 0.95rem;
    }

    .social-btn:hover {
      border-color: var(--primary-color);
      background: var(--primary-light);
      color: var(--primary-color);
      transform: translateY(-1px);
      box-shadow: var(--shadow-md);
    }

    .auth-footer {
      text-align: center;
      color: var(--text-secondary);
      font-size: 0.95rem;
      font-weight: 500;
    }

    .auth-footer a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
      transition: var(--transition);
    }

    .auth-footer a:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    .back-home {
      position: absolute;
      top: 30px;
      left: 30px;
      display: flex;
      align-items: center;
      gap: 10px;
      color: var(--text-primary);
      text-decoration: none;
      font-weight: 600;
      padding: 12px 20px;
      border-radius: var(--border-radius-lg);
      transition: var(--transition);
      background: var(--bg-primary);
      box-shadow: var(--shadow-md);
      border: 1px solid var(--border-color);
    }

    .back-home:hover {
      background: var(--primary-light);
      color: var(--primary-color);
      transform: translateX(-5px);
      box-shadow: var(--shadow-lg);
    }

    /* Floating geometric elements */
    .floating-shapes {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: -1;
      overflow: hidden;
    }

    .floating-shape {
      position: absolute;
      opacity: 0.08;
    }

    .floating-shape:nth-child(1) {
      width: 80px;
      height: 80px;
      background: linear-gradient(45deg, #93c5fd, #60a5fa);
      border-radius: 50%;
      top: 10%;
      left: 10%;
    }

    .floating-shape:nth-child(2) {
      width: 60px;
      height: 60px;
      background: linear-gradient(45deg, #6ee7b7, #34d399);
      border-radius: 20px;
      top: 20%;
      right: 15%;
    }

    .floating-shape:nth-child(3) {
      width: 100px;
      height: 100px;
      background: linear-gradient(45deg, #93c5fd, #6ee7b7);
      border-radius: 50%;
      bottom: 20%;
      left: 20%;
    }

    .floating-shape:nth-child(4) {
      width: 40px;
      height: 40px;
      background: linear-gradient(45deg, #34d399, #93c5fd);
      border-radius: 8px;
      bottom: 30%;
      right: 25%;
    }

    .floating-shape:nth-child(5) {
      width: 70px;
      height: 70px;
      background: linear-gradient(45deg, #60a5fa, #6ee7b7);
      border-radius: 50%;
      top: 60%;
      left: 5%;
    }

    .floating-shape:nth-child(6) {
      width: 50px;
      height: 50px;
      background: linear-gradient(45deg, #6ee7b7, #60a5fa);
      border-radius: 12px;
      top: 40%;
      right: 5%;
    }

    .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 16px 24px;
      border-radius: var(--border-radius-lg);
      color: white;
      font-weight: 600;
      z-index: 1000;
      max-width: 400px;
      animation: slideInRight 0.3s ease;
      box-shadow: var(--shadow-lg);
    }

    .notification.success {
      background: var(--secondary-color);
    }

    .notification.error {
      background: #ef4444;
    }

    @keyframes slideInRight {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    .password-strength {
      margin-top: 8px;
      font-size: 0.85rem;
      color: var(--text-secondary);
    }

    .password-strength.weak {
      color: #ef4444;
    }

    .password-strength.medium {
      color: #f59e0b;
    }

    .password-strength.strong {
      color: var(--secondary-color);
    }

    .strength-bars {
      display: flex;
      gap: 4px;
      margin-bottom: 6px;
    }

    .strength-bar-fill,
    .strength-bar-empty {
      height: 4px;
      border-radius: 2px;
      flex: 1;
      transition: var(--transition);
    }

    .strength-bar-empty {
      background: var(--border-color);
    }

    .password-strength.weak .strength-bar-fill {
      background: #ef4444;
    }

    .password-strength.medium .strength-bar-fill {
      background: #f59e0b;
    }

    .password-strength.strong .strength-bar-fill {
      background: var(--secondary-color);
    }

    @media (max-width: 768px) {
      .page-title {
        font-size: 2rem;
        top: 20px;
      }

      .signup-container {
        flex-direction: column;
        max-width: 500px;
        min-height: auto;
      }

      .form-section,
      .welcome-section {
        padding: 40px 30px;
      }

      .welcome-section {
        order: -1;
        padding: 30px 20px;
      }

      .welcome-title {
        font-size: 2.25rem;
      }

      .form-header h2 {
        font-size: 1.875rem;
      }

      .form-row {
        flex-direction: column;
        gap: 0;
      }

      .social-login {
        flex-direction: column;
      }

      .back-home {
        position: relative;
        top: auto;
        left: auto;
        margin-bottom: 20px;
        align-self: flex-start;
      }

      body {
        padding: 10px;
        flex-direction: column;
        align-items: center;
      }

      .welcome-features {
        margin-top: 30px;
      }

      .feature-item {
        padding: 12px 16px;
      }
    }

    @media (max-width: 480px) {
      .form-section,
      .welcome-section {
        padding: 30px 20px;
      }

      .form-header h2 {
        font-size: 1.75rem;
      }

      .welcome-title {
        font-size: 2rem;
      }

      .form-options {
        flex-direction: column;
        gap: 15px;
      }

      .page-title {
        font-size: 1.75rem;
      }
    }
  </style>
</head>
<body>
  <div class="floating-shapes">
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
  </div>
  
  <h1 class="page-title">Sign Up form</h1>
  
  <a href="index.php" class="back-home">
    <i class="fas fa-arrow-left"></i>
    Back to Home
  </a>
  
  <div class="signup-container">
    <div class="form-section">
      <div class="form-header">
        <h2>Hello!</h2>
        <p>Create Your account</p>
      </div>
      
      <form id="signupForm" method="POST" action="register.php">
        <div class="form-row">
          <div class="form-group">
            <label for="firstName">First Name</label>
            <div class="input-icon">
              <i class="fas fa-user"></i>
              <input type="text" id="firstName" name="firstName" placeholder="First name" required>
            </div>
          </div>
          
          <div class="form-group">
            <label for="lastName">Last Name</label>
            <div class="input-icon">
              <i class="fas fa-user"></i>
              <input type="text" id="lastName" name="lastName" placeholder="Last name" required>
            </div>
          </div>
        </div>
        
        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="input-icon">
            <i class="fas fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
          </div>
        </div>
        
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <div class="input-icon">
            <i class="fas fa-phone"></i>
            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
          </div>
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-icon">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Create a password" required>
          </div>
          <div class="password-strength" id="passwordStrength"></div>
        </div>
        
        <div class="form-group">
          <label for="confirmPassword">Confirm Password</label>
          <div class="input-icon">
            <i class="fas fa-lock"></i>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
          </div>
        </div>
        
        <div class="form-options">
          <label>
            <input type="checkbox" name="terms" required>
            I agree to the <a href="terms-of-service.php">Terms of Service</a> and <a href="privacy-policy.php">Privacy Policy</a>
          </label>
        </div>
        
        <div class="form-options">
          <label>
            <input type="checkbox" name="newsletter">
            I want to receive updates about new courses and universities
          </label>
        </div>
        
        <button type="submit" class="auth-btn">CREATE ACCOUNT</button>
      </form>
      
      <div class="auth-footer">
        Already have an account? <a href="login.php">Sign in</a>
      </div>
    </div>
    
    <div class="welcome-section">
      <div class="welcome-content">
        <h2 class="welcome-title">Join EduConnect SL</h2>
        <p class="welcome-text">
          Start your educational journey today! Connect with top universities, discover amazing courses, and build your future with EduConnect SL.
        </p>
        
        <div class="welcome-features">
          <div class="feature-item">
            <div class="feature-icon">
              <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="feature-text">Access Premium Courses</div>
          </div>
          
          <div class="feature-item">
            <div class="feature-icon">
              <i class="fas fa-users"></i>
            </div>
            <div class="feature-text">Join Student Community</div>
          </div>
          
          <div class="feature-item">
            <div class="feature-icon">
              <i class="fas fa-chart-line"></i>
            </div>
            <div class="feature-text">Track Your Progress</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    // Password strength checker with visual indicator
    document.getElementById('password').addEventListener('input', function() {
      const password = this.value;
      const strengthDiv = document.getElementById('passwordStrength');
      
      let strength = 0;
      let message = '';
      let strengthBar = '';
      
      if (password.length >= 8) strength++;
      if (/[a-z]/.test(password)) strength++;
      if (/[A-Z]/.test(password)) strength++;
      if (/[0-9]/.test(password)) strength++;
      if (/[^A-Za-z0-9]/.test(password)) strength++;
      
      // Create strength bar
      for (let i = 0; i < 5; i++) {
        if (i < strength) {
          strengthBar += '<div class="strength-bar-fill"></div>';
        } else {
          strengthBar += '<div class="strength-bar-empty"></div>';
        }
      }
      
      if (strength < 3) {
        message = 'Weak password';
        strengthDiv.className = 'password-strength weak';
      } else if (strength < 5) {
        message = 'Medium strength password';
        strengthDiv.className = 'password-strength medium';
      } else {
        message = 'Strong password';
        strengthDiv.className = 'password-strength strong';
      }
      
      strengthDiv.innerHTML = `
        <div class="strength-bars">${strengthBar}</div>
        <span>${message}</span>
      `;
    });

    // Password visibility toggle
    function setupPasswordToggle(inputId) {
      const input = document.getElementById(inputId);
      const icon = input.parentElement.querySelector('i');
      
      icon.addEventListener('click', function() {
        if (input.type === 'password') {
          input.type = 'text';
          this.className = 'fas fa-eye-slash';
        } else {
          input.type = 'password';
          this.className = 'fas fa-lock';
        }
      });
    }

    // Setup password visibility toggle for both password inputs
    setupPasswordToggle('password');
    setupPasswordToggle('confirmPassword');

    // Form validation
    document.getElementById('signupForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const firstName = document.getElementById('firstName').value.trim();
      const lastName = document.getElementById('lastName').value.trim();
      const email = document.getElementById('email').value.trim();
      const phone = document.getElementById('phone').value.trim();
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      const terms = document.querySelector('input[name="terms"]').checked;
      
      // Basic validation
      if (!firstName || !lastName || !email || !phone || !password || !confirmPassword) {
        showNotification('Please fill in all required fields', 'error');
        return;
      }
      
      if (password !== confirmPassword) {
        showNotification('Passwords do not match', 'error');
        return;
      }
      
      if (password.length < 8) {
        showNotification('Password must be at least 8 characters long', 'error');
        return;
      }
      
      if (!terms) {
        showNotification('Please agree to the Terms of Service and Privacy Policy', 'error');
        return;
      }
      
      // Show loading state
      const submitBtn = document.querySelector('.auth-btn');
      const originalText = submitBtn.textContent;
      submitBtn.textContent = 'Creating Account...';
      submitBtn.classList.add('loading');
      
      // Create FormData object
      const formData = new FormData(this);
      
      // Send data to PHP backend
      fetch('register.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.classList.remove('loading');
        
        if (data.success) {
          showNotification(data.message, 'success');
          
          // Redirect to login page after successful registration
          setTimeout(() => {
            window.location.href = 'login.php';
          }, 2000);
        } else {
          showNotification(data.message, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        submitBtn.textContent = originalText;
        submitBtn.classList.remove('loading');
        showNotification('An error occurred. Please try again.', 'error');
      });
    });

    // Notification system function
    function showNotification(message, type = 'info') {
      // Remove existing notifications to prevent overlap
      const existingNotification = document.querySelector('.notification');
      if (existingNotification) {
        existingNotification.remove();
      }

      const notification = document.createElement('div');
      notification.className = `notification ${type}`;
      notification.innerHTML = `
        <div class="notification-content">
          <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
          <span>${message}</span>
        </div>
      `;

      document.body.appendChild(notification);

      // Auto remove notification after 3 seconds
      setTimeout(() => {
        notification.remove();
      }, 3000);
    }

    // Add floating labels effect (for input fields)
    document.querySelectorAll('.form-group input').forEach(input => {
      input.addEventListener('focus', function() {
        const group = this.closest('.form-group');
        if (group) group.classList.add('focused');
      });
      
      input.addEventListener('blur', function() {
        const group = this.closest('.form-group');
        if (group && !this.value) {
          group.classList.remove('focused');
        }
      });
    });
  </script>
</body>
</html>
