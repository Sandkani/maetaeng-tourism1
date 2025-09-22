import React from 'react';

function RatingStars({ rating, onRate }) {
  return (
    <div>
      <label>ให้คะแนน: </label>
      {[1, 2, 3, 4, 5].map((i) => (
        <span
          key={i}
          onClick={() => onRate(i)}
          style={{
            cursor: 'pointer',
            color: rating >= i ? '#ffc107' : '#e4e5e9', // สีทองถ้าได้คะแนน, สีเทาถ้าไม่ได้
            fontSize: '24px',
            marginRight: 5,
            userSelect: 'none',
          }}
          role="button"
          aria-label={`${i} star`}
          tabIndex={0}
          onKeyDown={(e) => {
            if (e.key === 'Enter' || e.key === ' ') {
              onRate(i);
            }
          }}
        >
          ★
        </span>
      ))}
    </div>
  );
}

export default RatingStars;
