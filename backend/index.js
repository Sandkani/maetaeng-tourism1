const express = require('express');
const mysql = require('mysql2/promise');
const cors = require('cors');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');

const app = express();
const port = 5000;

// เชื่อมต่อ MySQL ด้วย user ใหม่
const pool = mysql.createPool({
  host: 'localhost',
  user: 'maetaeng_user',          // ใช้ user ที่สร้างใหม่
  password: 'your_password_here', // ใช้รหัสผ่านที่ตั้งไว้
  database: 'maetaeng_tourism',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
});

app.use(cors());
app.use(express.json());

app.get('/', (req, res) => {
  res.send('Backend server is running');
});

// API Login
app.post('/api/login', async (req, res) => {
  const { username, password } = req.body;

  if (!username || !password) {
    return res.status(400).json({ message: 'กรุณากรอก username และ password' });
  }

  try {
    const [rows] = await pool.query('SELECT * FROM users WHERE username = ?', [username]);

    if (rows.length === 0) {
      return res.status(401).json({ message: 'ไม่พบผู้ใช้ หรือ รหัสผ่านไม่ถูกต้อง' });
    }

    const user = rows[0];

    const match = await bcrypt.compare(password, user.password_hash);
    if (!match) {
      return res.status(401).json({ message: 'ไม่พบผู้ใช้ หรือ รหัสผ่านไม่ถูกต้อง' });
    }

    const token = jwt.sign(
      { userId: user.id, username: user.username },
      'your_jwt_secret_key', // เปลี่ยนเป็น secret ของคุณ
      { expiresIn: '1h' }
    );

    return res.json({ token });
  } catch (error) {
    console.error('Login error:', error);
    res.status(500).json({ message: 'เกิดข้อผิดพลาดในระบบ' });
  }
});

app.listen(port, () => {
  console.log(`Backend server running at http://localhost:${port}`);
});
