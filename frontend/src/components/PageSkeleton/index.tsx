import React from 'react';

import { Container, Typography } from '@material-ui/core';

import useStyles from './useStyles';

interface Props {
  title: string;
}

const Page: React.FC<Props> = ({ title }) => {
  const classes = useStyles();

  return (
    <Container>
      <Typography className={classes.title}>{title}</Typography>
    </Container>
  );
};

export default Page;
