import React, { useState, useEffect } from 'react';
import './App.css';
import { Button, Typography } from '@mui/material';

import LoginForm from './components/LoginForm';
import PlaceDetail from './components/PlaceDetail';

const API_URL = 'http://localhost:5000/api';

function App() {
  const [places, setPlaces] = useState([]);
  const [selectedPlace, setSelectedPlace] = useState(null);
  const [token, setToken] = useState(localStorage.getItem('token') || '');
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [comment, setComment] = useState('');
  const [comments, setComments] = useState([]);
  const [rating, setRating] = useState(0);

  useEffect(() => {
    fetch(`${API_URL}/places`)
      .then(res => res.json())
      .then(data => {
        if (Array.isArray(data)) setPlaces(data);
      })
      .catch(() => alert('โหลดข้อมูลสถานที่ไม่สำเร็จ'));
  }, []);

  useEffect(() => {
    if (!selectedPlace) return;

    fetch(`${API_URL}/places/${selectedPlace.id}/comments`)
      .then(res => res.json())
      .then(data => setComments(data))
      .catch(() => setComments([]));

    fetch(`${API_URL}/places/${selectedPlace.id}/rating`)
      .then(res => res.json())
      .then(data => setRating(data.average || 0))
      .catch(() => setRating(0));
  }, [selectedPlace]);

  const handleLogin = async (e) => {
    e.preventDefault();
    try {
      const res = await fetch(`${API_URL}/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password }),
      });
      const data = await res.json();
      if (res.ok) {
        setToken(data.token);
        localStorage.setItem('token', data.token);
        alert('เข้าสู่ระบบสำเร็จ');
      } else {
        alert(data.message);
      }
    } catch {
      alert('เกิดข้อผิดพลาด');
    }
  };

  const handleLogout = () => {
    setToken('');
    localStorage.removeItem('token');
  };

  const addComment = async () => {
    if (!comment.trim()) return alert('กรุณากรอกความคิดเห็น');

    try {
      const res = await fetch(`${API_URL}/places/${selectedPlace.id}/comments`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ comment }),
      });
      const data = await res.json();
      if (res.ok) {
        setComments(prev => [...prev, data]);
        setComment('');
        alert('เพิ่มความคิดเห็นสำเร็จ');
      } else {
        alert(data.message);
      }
    } catch {
      alert('เกิดข้อผิดพลาด');
    }
  };

  const giveRating = async (rate) => {
    try {
      const res = await fetch(`${API_URL}/places/${selectedPlace.id}/rate`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ rating: rate }),
      });
      const data = await res.json();
      if (res.ok) {
        setRating(rate);
        alert('ให้คะแนนสำเร็จ');
      } else {
        alert(data.message);
      }
    } catch {
      alert('เกิดข้อผิดพลาด');
    }
  };

  return (
    <div className="App">
      <Typography variant="h4" gutterBottom>
        สถานที่ท่องเที่ยว อำเภอแม่แตง จังหวัดเชียงใหม่
      </Typography>

      {!token ? (
        <LoginForm
          username={username}
          password={password}
          onUsernameChange={setUsername}
          onPasswordChange={setPassword}
          onLogin={handleLogin}
        />
      ) : (
        <Button onClick={handleLogout} variant="outlined" color="error">
          ออกจากระบบ
        </Button>
      )}

      <hr />

      <Typography variant="h6">รายการสถานที่</Typography>
      <ul>
        {places.map(place => (
          <li
            key={place.id}
            onClick={() => setSelectedPlace(place)}
            style={{ cursor: 'pointer' }}
          >
            {place.name}
          </li>
        ))}
      </ul>

      {selectedPlace && (
        <PlaceDetail
          place={selectedPlace}
          rating={rating}
          comments={comments}
          token={token}
          onRate={giveRating}
          comment={comment}
          setComment={setComment}
          onAddComment={addComment}
        />
      )}
    </div>
  );
}

export default App;
