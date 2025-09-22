import React from 'react';
import { Box, Typography, Button, TextField } from '@mui/material';

function PlaceDetail({ place, rating, comments, token, onRate, comment, setComment, onAddComment, loading }) {
  return (
    <Box sx={{ border: '1px solid #ccc', p: 2, mt: 2 }}>
      <Typography variant="h5">{place.name}</Typography>
      <Typography>{place.description}</Typography>

      {place.image && <img src={`http://localhost:5000/uploads/${place.image}`} alt={place.name} width={300} />}
      {place.video && (
        <video width="300" controls>
          <source src={`http://localhost:5000/uploads/${place.video}`} type="video/mp4" />
        </video>
      )}
      {place.audio && (
        <audio controls>
          <source src={`http://localhost:5000/uploads/${place.audio}`} type="audio/mpeg" />
        </audio>
      )}

      <p>
        <a href={place.map_url} target="_blank" rel="noopener noreferrer">
          ดูแผนที่
        </a>
      </p>

      <Typography variant="subtitle1">คะแนนเฉลี่ย: {rating.toFixed(2)} / 5</Typography>

      {token && (
        <Box sx={{ my: 1 }}>
          {[1, 2, 3, 4, 5].map((i) => (
            <Button
              key={i}
              variant={rating >= i ? 'contained' : 'outlined'}
              color="secondary"
              onClick={() => onRate(i)}
              sx={{ mr: 1 }}
              disabled={loading}
            >
              {i}
            </Button>
          ))}
        </Box>
      )}

      <Typography variant="h6">ความคิดเห็น</Typography>
      <ul>
        {comments.map((c) => (
          <li key={c.id}>
            <b>{c.username}</b>: {c.comment} ({new Date(c.created_at).toLocaleString()})
          </li>
        ))}
      </ul>

      {token && (
        <Box>
          <TextField
            multiline
            rows={3}
            value={comment}
            onChange={(e) => setComment(e.target.value)}
            placeholder="เขียนความคิดเห็น..."
            fullWidth
          />
          <Button onClick={onAddComment} variant="contained" sx={{ mt: 1 }} disabled={loading}>
            เพิ่มความคิดเห็น
          </Button>
        </Box>
      )}
    </Box>
  );
}

export default PlaceDetail;
