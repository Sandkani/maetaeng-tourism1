// frontend/src/App.js
import React, { useState, useEffect } from 'react';

const API_URL = 'http://localhost:5000/api';

function App() {
  const [places, setPlaces] = useState([]);
  const [token, setToken] = useState(localStorage.getItem('token') || '');
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [comment, setComment] = useState('');
  const [selectedPlace, setSelectedPlace] = useState(null);
  const [comments, setComments] = useState([]);
  const [rating, setRating] = useState(0);

  // ดึงข้อมูลสถานที่
  useEffect(() => {
    fetch(`${API_URL}/places`)
      .then(res => res.json())
      .then(data => setPlaces(data))
      .catch(console.error);
  }, []);

  // ดึงความคิดเห็นของสถานที่
  useEffect(() => {
    if (selectedPlace) {
      fetch(`${API_URL}/places/${selectedPlace.id}/comments`)
        .then(res => res.json())
        .then(data => setComments(data))
        .catch(console.error);

      fetch(`${API_URL}/places/${selectedPlace.id}/ratings`)
        .then(res => res.json())
        .then(data => setRating(data.avg_rating || 0))
        .catch(console.error);
    }
  }, [selectedPlace]);

  // เข้าสู่ระบบ
  const handleLogin = async (e) => {
    e.preventDefault();
    try {
      const res = await fetch(`${API_URL}/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
      });
      const data = await res.json();
      if (res.ok) {
        setToken(data.token);
        localStorage.setItem('token', data.token);
        alert('เข้าสู่ระบบสำเร็จ');
      } else {
        alert(data.message);
      }
    } catch (error) {
      alert('เกิดข้อผิดพลาด');
    }
  };

  // ออกจากระบบ
  const handleLogout = () => {
    setToken('');
    localStorage.removeItem('token');
  };

  // เพิ่มความคิดเห็น
  const addComment = async () => {
    if (!comment) return alert('กรุณากรอกความคิดเห็น');
    try {
      const res = await fetch(`${API_URL}/places/${selectedPlace.id}/comments`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({ comment })
      });
      const data = await res.json();
      if (res.ok) {
        alert('เพิ่มความคิดเห็นสำเร็จ');
        setComment('');
        // ดึงความคิดเห็นใหม่
        fetch(`${API_URL}/places/${selectedPlace.id}/comments`)
          .then(res => res.json())
          .then(data => setComments(data));
      } else {
        alert(data.message);
      }
    } catch {
      alert('เกิดข้อผิดพลาด');
    }
  };

  // ให้คะแนนสถานที่
  const giveRating = async (rate) => {
    try {
      const res = await fetch(`${API_URL}/places/${selectedPlace.id}/rate`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({ rating: rate })
      });
      const data = await res.json();
      if (res.ok) {
        alert('ให้คะแนนสำเร็จ');
        setRating(rate);
      } else {
        alert(data.message);
      }
    } catch {
      alert('เกิดข้อผิดพลาด');
    }
  };

  return (
    <div style={{ maxWidth: 800, margin: 'auto', padding: 20 }}>
      <h1>สถานที่ท่องเที่ยว อำเภอแม่แตง</h1>

      {!token ? (
        <form onSubmit={handleLogin}>
          <h2>เข้าสู่ระบบ</h2>
          <input placeholder="ชื่อผู้ใช้" value={username} onChange={e => setUsername(e.target.value)} />
          <br />
          <input type="password" placeholder="รหัสผ่าน" value={password} onChange={e => setPassword(e.target.value)} />
          <br />
          <button type="submit">เข้าสู่ระบบ</button>
        </form>
      ) : (
        <div>
          <button onClick={handleLogout}>ออกจากระบบ</button>
        </div>
      )}

      <hr />

      <h2>รายการสถานที่</h2>
      <ul>
        {places.map(place => (
          <li key={place.id} onClick={() => setSelectedPlace(place)} style={{ cursor: 'pointer', marginBottom: 10 }}>
            <b>{place.name}</b>
          </li>
        ))}
      </ul>

      {selectedPlace && (
        <div style={{ border: '1px solid #ddd', padding: 10 }}>
          <h3>{selectedPlace.name}</h3>
          <p>{selectedPlace.description}</p>

          {selectedPlace.image && <img src={`http://localhost:5000/uploads/${selectedPlace.image}`} alt={selectedPlace.name} width={300} />}
          <br />

          {selectedPlace.video && (
            <video width="300" controls>
              <source src={`http://localhost:5000/uploads/${selectedPlace.video}`} type="video/mp4" />
              วิดีโอไม่รองรับ
            </video>
          )}

          {selectedPlace.audio && (
            <audio controls>
              <source src={`http://localhost:5000/uploads/${selectedPlace.audio}`} type="audio/mpeg" />
              เสียงไม่รองรับ
            </audio>
          )}

          <p>
            <a href={selectedPlace.map_url} target="_blank" rel="noreferrer">แผนที่</a>
          </p>

          <hr />
          <h4>คะแนนเฉลี่ย: {rating.toFixed(2)} / 5</h4>
          {token && (
            <div>
              <label>ให้คะแนน: </label>
              {[1,2,3,4,5].map(i => (
                <button key={i} onClick={() => giveRating(i)} style={{ fontWeight: rating >= i ? 'bold' : 'normal' }}>{i}</button>
              ))}
            </div>
          )}

          <hr />
          <h4>ความคิดเห็น</h4>
          <ul>
            {comments.map(c => (
              <li key={c.id}><b>{c.username}</b>: {c.comment} <i>({new Date(c.created_at).toLocaleString()})</i></li>
            ))}
          </ul>

          {token && (
            <div>
              <textarea rows={3} value={comment} onChange={e => setComment(e.target.value)} placeholder="เขียนความคิดเห็น..."></textarea>
              <br />
              <button onClick={addComment}>เพิ่มความคิดเห็น</button>
            </div>
          )}
        </div>
      )}
    </div>
  );
}

export default App;
