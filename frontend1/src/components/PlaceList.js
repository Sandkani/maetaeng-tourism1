import React from 'react';
import { List, ListItem, ListItemButton, ListItemText } from '@mui/material';

function PlaceList({ places, onSelect }) {
  return (
    <List>
      {places.map((place) => (
        <ListItem key={place.id} disablePadding>
          <ListItemButton onClick={() => onSelect(place)}>
            <ListItemText primary={place.name} />
          </ListItemButton>
        </ListItem>
      ))}
    </List>
  );
}

export default PlaceList;
