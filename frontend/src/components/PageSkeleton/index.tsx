import React from 'react';

import { Container, Typography } from '@material-ui/core';

import useStyles from './useStyles';

interface Props {
  title: string;
}

const PageSkeleton: React.FC<Props> = ({ children, title }) => {
  const classes = useStyles();

  return (
    <Container>
      <Typography className={classes.title} component="h1" variant="h5">
        {title}
      </Typography>

      {children}
    </Container>
  );
};

export default PageSkeleton;
