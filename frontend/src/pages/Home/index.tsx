import React from 'react';

import Breadcrumb from '../../components/Breadcrumb';
import PageSkeleton from '../../components/PageSkeleton';

const Home: React.FC = () => {
  return (
    <>
      <Breadcrumb />
      <PageSkeleton title="Categorias" />
    </>
  );
};

export default Home;
