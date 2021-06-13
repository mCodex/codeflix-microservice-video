import React from 'react';
import { AppBar, Button, Toolbar, Typography } from '@material-ui/core';

import useStyles from './useStyles';

const Navbar: React.FC = () => {
  const classes = useStyles();

  return (
    <AppBar>
      <Toolbar className={classes.toolbar}>
        <Typography className={classes.title}>
          <img className={classes.logo} src="/logo.png" alt="CodeFlix" />
        </Typography>

        <Button color="inherit">Login</Button>
      </Toolbar>
    </AppBar>
  );
};

export default Navbar;
