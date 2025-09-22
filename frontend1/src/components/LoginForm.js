// LoginPage.js
import React, { useState } from 'react';
import LoginForm from './LoginForm';

function LoginPage() {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);

  const handleLogin = async (e) => {
    e.preventDefault();
    setLoading(true);

    try {
      const response = await fetch('http://localhost/maetaeng-tourism/backend/login.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ username, password })
      });

      const data = await response.json();

      if (response.ok) {
        alert('เข้าสู่ระบบสำเร็จ');
        // ทำอย่างอื่น เช่น redirect
      } else {
        alert(data.message || 'เข้าสู่ระบบไม่สำเร็จ');
      }

    } catch (error) {
      console.error('Login error:', error);
      alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div style={{ maxWidth: 400, margin: 'auto', marginTop: 100 }}>
      <LoginForm
        username={username}
        password={password}
        onUsernameChange={setUsername}
        onPasswordChange={setPassword}
        onLogin={handleLogin}
        loading={loading}
      />
    </div>
  );
}

export default LoginPage;
