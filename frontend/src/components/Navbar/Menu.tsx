import React, { useCallback, useMemo, useState } from 'react';
import { IconButton, Menu, MenuItem } from '@material-ui/core';
import { Menu as MenuIcon } from '@material-ui/icons';
import Link from 'next/link';

import routes from '../../routes';

const listRoutes = ['dashboard', 'categories.list'];

const MenuComponent: React.FC = () => {
  const [anchorEl, setAnchorEl] = useState(null);

  const menuRoutes = useMemo(() => routes.filter((route) => listRoutes.includes(route.name)), []);

  const handleMenuOpen = useCallback((event) => setAnchorEl(event.currentTarget), []);
  const handleMenuClose = useCallback(() => setAnchorEl(null), []);

  const renderMenuItem = useCallback(() => {
    return listRoutes.map((routeName) => {
      const route = menuRoutes.find((routeData) => routeData.name === routeName);

      return (
        <Link key={route?.name} passHref href={route?.path as string}>
          <MenuItem  onClick={handleMenuClose}>
            {route?.label}
          </MenuItem>
        </Link>
      );
    });
  }, []);

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
        {renderMenuItem()}
      </Menu>
    </>
  );
};

export default MenuComponent;
