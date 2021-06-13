import React, { useCallback, useState } from 'react';
import { IconButton, Menu, MenuItem } from '@material-ui/core';
import { Menu as MenuIcon } from '@material-ui/icons';

const MenuComponent: React.FC = () => {
  const [anchorEl, setAnchorEl] = useState(null);

  const handleMenuOpen = useCallback((event) => setAnchorEl(event.currentTarget), []);
  const handleMenuClose = useCallback(() => setAnchorEl(null), []);

  return (
    <>
      <IconButton
        edge="start"
        color="inherit"
        aria-label="open drawer"
        aria-controls="menu-appbar"
        aria-haspopup="true"
        onClick={handleMenuOpen}
      >
        <MenuIcon />
      </IconButton>

      <Menu
        id="menu-appbar"
        open={!!anchorEl}
        anchorEl={anchorEl}
        onClose={handleMenuClose}
        anchorOrigin={{ vertical: 'bottom', horizontal: 'center' }}
        transformOrigin={{ vertical: 'top', horizontal: 'center' }}
        getContentAnchorEl={null}
      >
        <MenuItem onClick={handleMenuClose}>Categorias</MenuItem>
      </Menu>
    </>
  );
};

export default MenuComponent;
