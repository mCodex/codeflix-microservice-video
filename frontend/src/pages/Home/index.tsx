import React from 'react';
import { Button } from '@material-ui/core';

import { Title } from './styles';

const Home: React.FC = () => {
  return (
    <>
      <Title>Hello World, Full cycle!</Title>
      <Button color="primary" variant="contained">
        Button Text
      </Button>
    </>
  );
};

export default Home;
