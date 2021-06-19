import React from 'react';
import { Box } from '@material-ui/core';

import Navbar from '../../components/Navbar';
import PageSkeleton from '../../components/PageSkeleton';

const Home: React.FC = () => {
  return (
    <>
      <Navbar />
      <Box paddingTop="70px">
        <PageSkeleton title="Categorias" />
      </Box>
    </>
  );
};

export default Home;
